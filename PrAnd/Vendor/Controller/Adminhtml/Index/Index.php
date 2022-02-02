<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    const MENU = 'PrAnd_Vendor::vendors';
    const ADMIN_RESOURCE = 'PrAnd_Vendor::vendors';

    /**
     * Index constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu(self::MENU)
            ->getConfig()
            ->getTitle()
            ->prepend(__('Vendors'));

        return $page;
    }
}
