<?php

namespace Webkul\Seller\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Country Reposotory
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AreaRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Seller\Models\Area';
    }
}