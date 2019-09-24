<?php

namespace Webkul\Seller\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Seller Data Grid Class
 *
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
Class SellerDataGrid extends DataGrid
{
    /**
     *
     * @var integer
    */
    public $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder  = DB::table('sellers')
                ->select('id', 'email', 'sellers.created_at',  DB::raw('CONCAT(sellers.first_name, " ", sellers.last_name) as seller_name'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('seller::app.admin.seller.id'),
            'type' => 'number',
            'searchable' => false,
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
            'index' => 'email',
            'label' => trans('seller::app.admin.seller.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'created at',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'pay',
            'label' => trans('seller::app.admin.seller.product'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'closure' => true,
            'wrapper' => function($row) {
                return '<a href = "' . route('admin.seller.products.create',
                [
                    $row->id
                ]) . '" class="btn btn-sm btn-primary pay-btn" seller-id="' . $row->id .'">' . trans('seller::app.admin.seller.add-product') . '</a>';
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'sellers.admin.seller.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'sellers.admin.seller.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Shipping Method']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('seller::app.admin.seller.delete'),
            'action' => route('sellers.admin.seller.mass-delete'),
            'method' => 'DELETE'
        ]);

        $this->enableMassAction = true;
    }
}