<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Ui\Component\MassAction\Filter;
use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Api\VendorRepositoryInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor\Collection;
use PrAnd\Vendor\Model\ResourceModel\Vendor\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'PrAnd_Vendor::vendors_massDelete';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var VendorRepositoryInterface
     */
    protected $vendorRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionVendorFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param VendorRepositoryInterface $vendorRepository
     * @param CollectionFactory $collectionVendorFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        VendorRepositoryInterface $vendorRepository,
        CollectionFactory $collectionVendorFactory
    ) {
        $this->filter = $filter;
        $this->vendorRepository = $vendorRepository;
        $this->collectionVendorFactory = $collectionVendorFactory;

        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     * @throws NotFoundException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResultInterface
    {
        /** @var Collection $vendorCollection */
        $vendorCollection = $this->collectionVendorFactory->create();
        $this->filter->getCollection($vendorCollection);
        $vendors = $vendorCollection->getItems();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($vendors)) {
            $successfulDeletions = 0;
            $unsuccessfulDeletions = 0;

            /** @var VendorInterface|AbstractExtensibleModel $vendor */
            foreach ($vendors as $vendor) {
                $this->vendorRepository->delete($vendor);

                ($vendor->isDeleted()) ? $successfulDeletions++ : $unsuccessfulDeletions++;
            }

            if (!empty($unsuccessfulDeletions)) {
                $this->messageManager->addErrorMessage(
                    __('Failed to remove %1 vendors', $unsuccessfulDeletions)
                );
            }

            if (!empty($successfulDeletions)) {
                $this->messageManager->addSuccessMessage(
                    __('Successfully removed %1 vendors', $successfulDeletions)
                );
            }
        }

        return $resultRedirect->setPath('*/index');
    }
}
