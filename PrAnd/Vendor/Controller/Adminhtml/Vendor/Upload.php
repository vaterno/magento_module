<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\Controller\ResultInterface;
use PrAnd\Vendor\Api\VendorImageServiceInterface;

class Upload extends Action implements HttpPostActionInterface
{
    /**
     * @var ImageUploader
     */
    protected $uploader;

    /**
     * Upload constructor.
     * @param Context $context
     * @param ImageUploader $uploader
     */
    public function __construct(
        Context $context,
        ImageUploader $uploader
    ) {
        $this->uploader = $uploader;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $imageId = $this->_request->getParam('param_name', 'image');
        $this->uploader->setAllowedExtensions(array_keys(VendorImageServiceInterface::ALLOWED_TYPES));
        $this->uploader->setBaseTmpPath(VendorImageServiceInterface::BASE_TMP_PATH);

        try {
            $result = $this->uploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        $isAllowed = ($this->_authorization->isAllowed(Edit::ADMIN_RESOURCE) || $this->_authorization->isAllowed(AddNew::ADMIN_RESOURCE));
        return $isAllowed;
    }
}
