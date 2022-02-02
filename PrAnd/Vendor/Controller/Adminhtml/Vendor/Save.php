<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use PrAnd\Vendor\Controller\Adminhtml\Vendor\Edit;
use PrAnd\Vendor\Controller\Adminhtml\Vendor\AddNew;

use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Api\Data\VendorInterfaceFactory;
use PrAnd\Vendor\Api\VendorRepositoryInterface;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var VendorRepositoryInterface
     */
    protected $vendorRepository;

    /**
     * @var VendorInterfaceFactory
     */
    protected $vendorDtoFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param VendorRepositoryInterface $vendorRepository
     * @param VendorInterfaceFactory $vendorDtoFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        VendorRepositoryInterface $vendorRepository,
        VendorInterfaceFactory $vendorDtoFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);

        $this->vendorRepository = $vendorRepository;
        $this->vendorDtoFactory = $vendorDtoFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirect = $this->resultRedirectFactory->create();
        $post = $this->getRequest()->getParam('vendor');
        $isUpdate = !empty($post[VendorInterface::ID]);

        /** @var VendorInterface|AbstractExtensibleModel $vendorDTO */
        $vendorDTO = $this->vendorDtoFactory->create();
        $this->dataPersistor->set('vendor', $post);
        $post['image'] = $post['image']['0']['name'] ?? '';

        try {
            $vendorDTO->setData($post);
            $this->vendorRepository->saveOrUpdate($vendorDTO);
            $this->messageManager->addSuccessMessage(__('Data saved successfully.'));
            $this->dataPersistor->clear('vendor');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            if (!$isUpdate) {
                return $redirect->setPath('*/*/addNew');
            }
        }

        return $redirect->setPath('*/*/edit', [
            'id' => $vendorDTO->getId()
        ]);
    }

    /**
     * @return bool
     */
    public function _isAllowed(): bool
    {
        $isAllowed = ($this->_authorization->isAllowed(Edit::ADMIN_RESOURCE) ||
                    $this->_authorization->isAllowed(AddNew::ADMIN_RESOURCE));

        return $isAllowed;
    }
}
