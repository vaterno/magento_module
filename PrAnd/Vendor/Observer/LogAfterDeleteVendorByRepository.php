<?php

namespace PrAnd\Vendor\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use PrAnd\Vendor\Model\Vendor;
use Psr\Log\LoggerInterface;

class LogAfterDeleteVendorByRepository implements ObserverInterface
{
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
        $vendorEntity = $observer->getEvent()->getData('entity');

        $this->logger->info('After delete vendor with id - ' . $vendorEntity->getId());
    }
}