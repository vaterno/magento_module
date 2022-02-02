<?php

namespace PrAnd\Vendor\Model\ResourceModel\Vendor;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;

use PrAnd\Vendor\Model\Vendor;
use PrAnd\Vendor\Model\ResourceModel\Vendor as ResourceVendor;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = Vendor::ID;

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            Vendor::class,
            ResourceVendor::class
        );
    }
}
