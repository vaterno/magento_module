<?php

namespace PrAnd\Vendor\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use PrAnd\Vendor\Model\Vendor as VendorModel;
use Magento\Eav\Model\Entity\Type;

class Vendor extends AbstractEntity
{
    /**
     * @var string
     */
    protected $_entityIdField = VendorModel::ID;

    /**
     * @return Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType(): Type
    {
        if (empty($this->_type)) {
            $this->setType(VendorModel::ENTITY);
        }

        return parent::getEntityType();
    }
}
