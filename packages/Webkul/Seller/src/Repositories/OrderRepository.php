<?php

namespace Webkul\Seller\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Eloquent\Repository;
use Webkul\Seller\Repositories\OrderItemRepository;

/**
 * Order Reposotory
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderRepository extends Repository
{
    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Seller\Repositories\OrderItemRepository $orderItem
     * @return void
     */
    public function __construct(
        OrderItemRepository $orderItem,
        App $app
    )
    {
        $this->orderItem = $orderItem;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Seller\Models\Order';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            Event::fire('checkout.order.save.before', $data);

            if (isset($data['customer']) && $data['customer']) {
                $data['customer_id'] = $data['customer']->id;
                $data['customer_type'] = get_class($data['customer']);
            } else {
                unset($data['customer']);
            }

            if (isset($data['channel']) && $data['channel']) {
                $data['channel_id'] = $data['channel']->id;
                $data['channel_type'] = get_class($data['channel']);
                $data['channel_name'] = $data['channel']->name;
            } else {
                unset($data['channel']);
            }

            $data['status'] = 'pending';

            $order = $this->model->create(array_merge($data, ['increment_id' => $this->generateIncrementId()]));

            $order->payment()->create($data['payment']);

            $order->addresses()->create($data['shipping_address']);

            $order->addresses()->create($data['billing_address']);

            foreach ($data['items'] as $item) {
                $orderItem = $this->orderItem->create(array_merge($item, ['order_id' => $order->id]));

                if (isset($item['child']) && $item['child']) {
                    $orderItem->child = $this->orderItem->create(array_merge($item['child'], ['order_id' => $order->id, 'parent_id' => $orderItem->id]));
                }

                $this->orderItem->manageInventory($orderItem);
            }

            Event::fire('checkout.order.save.after', $order);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $order;
    }

    /**
     * @inheritDoc
     */
    public function generateIncrementId()
    {
        $lastOrder = $this->model->orderBy('id', 'desc')->limit(1)->first();

        $lastId = $lastOrder ? $lastOrder->id : 0;

        return $lastId + 1;
    }
}