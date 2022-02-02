<?php

namespace PrAnd\Vendor\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

use PrAnd\Vendor\Model\Vendor;

class LogOfCreatedANewVendorObserver implements ObserverInterface
{
    /** @var LoggerInterface $logger */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Vendor $vendorEntity */
        $vendorEntity = $observer->getEvent()->getData('data_object');
        $isEntityNew = (is_null($vendorEntity->getOrigData('entity_id')) ||  $vendorEntity->isObjectNew());

        if ($isEntityNew) {
            $this->logger->info('New vendor with name - ' . $vendorEntity->getName());
        }
    }
}