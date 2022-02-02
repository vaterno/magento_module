<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;

use Magento\Framework\Controller\ResultInterface;
use PrAnd\Vendor\Api\VendorRepositoryInterface;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'PrAnd_Vendor::vendors_delete';
    const REQUEST_FIELD_NAME = 'id';

    /**
     * @var VendorRepositoryInterface
     */
    protected $vendorRepository;

    /**
     * @var string[]
     */
    protected $_publicActions = [
        'delete'
    ];

    /**
     * @param Context $context
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(
        Context $context,
        VendorRepositoryInterface $vendorRepository
    ) {
        parent::__construct($context);
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultRedirectFactory->create();
        $deleteId = $this->getRequest()->getParam(self::REQUEST_FIELD_NAME, 0);

        if (!empty($deleteId)) {
            try {
                $this->vendorRepository->deleteById($deleteId);

                $this->messageManager->addSuccessMessage(__('The vendor successfully deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Can`t delete the vendor.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Can`t find the vendor'));
        }

        return $redirect->setPath('*/index/index');
    }
}
