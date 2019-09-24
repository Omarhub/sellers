<?php

namespace Webkul\Seller\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Seller Product Data Grid Class
 *
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
Class ProductDataGrid extends DataGrid
{
    /**
     *
     * @var integer
    */
    public $index = 'seller_product_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder  = DB::table('product_flat')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->join('seller_products', 'product_flat.product_id', '=', 'seller_products.product_id')
                ->leftJoin('sellers', 'seller_products.seller_id', '=', 'sellers.id')
                ->select('seller_products.id as seller_product_id',
                    'product_flat.product_id', 'product_flat.sku',
                    'product_flat.name','seller_products.price','seller_products.id',
                    'seller_products.condition','sellers.created_at',
                     DB::raw('CONCAT(sellers.first_name, " ", sellers.last_name) as seller_name'));


        $queryBuilder = $queryBuilder->leftJoin('product_inventories', function($qb) {
        $qb->on('product_flat.product_id', 'product_inventories.product_id')
            ->where('product_inventories.vendor_id', '<>', 0);
        });

        $queryBuilder
            ->groupBy('seller_products.id')
            ->addSelect(DB::raw('SUM(product_inventories.qty) as quantity'));

        $this->addFilter('seller_name', DB::raw('CONCAT(sellers.first_name, " ", sellers.last_name)'));
        $this->addFilter('sku', 'product_flat.sku');
        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('price', 'seller_products.price');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('seller::app.admin.seller.products.product-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('seller::app.admin.seller.products.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('seller::app.admin.seller.products.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'seller_name',
            'label' => trans('seller::app.admin.seller.seller-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('seller::app.admin.seller.products.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('seller::app.admin.seller.products.quantity'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false
        ]);

        $this->addColumn([
                 'index' => 'condition',
                'label' => trans('seller::app.admin.seller.products.condition'),
                'type' => 'string',
                'searchable' => true,
                'sortable' => true,
                'filterable' => true,
                'wrapper' => function($value) {
                    if ($value->condition == 'old')
                        return 'Used';
                    else
                        return 'New';
                }
            ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'admin.seller.products.assign.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'seller.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete'),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('seller::app.admin.seller.delete'),
            'action' => route('seller.catalog.products.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->enableMassAction = true;
    }
}