<?php

namespace PrAnd\Vendor\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor\Collection;
use PrAnd\Vendor\Model\ResourceModel\Vendor\CollectionFactory;

class AvailableVendors extends AbstractSource
{
    /** @var CollectionFactory  */
    protected $vendorCollectionFactory;

    /**
     * AvailableVendors constructor.
     * @param CollectionFactory $vendorCollectionFactory
     */
    public function __construct(
        CollectionFactory $vendorCollectionFactory
    ) {
        $this->vendorCollectionFactory = $vendorCollectionFactory;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAllOptions(): array
    {
        $result = [];

        /** @var Collection $vendorCollection */
        $vendorCollection = $this->vendorCollectionFactory->create();
        $vendorCollection->addAttributeToSelect('*');
        $items = $vendorCollection->getItems();

        if (!empty($items)) {
            $result = array_reduce($items, function ($carry, $item) {
                /** @var VendorInterface $item */
                $carry[$item->getId()] = [
                    'label' => $item->getName(),
                    'value' => $item->getId()
                ];

                return $carry;
            });
        }

        return $result;
    }
}
