<?php

namespace PrAnd\Vendor\Api;

use PrAnd\Vendor\Model\Vendor;

interface ApiVendorManagementInterface
{
    /**
     * @return Vendor[]
     */
    public function getList(): array;
}