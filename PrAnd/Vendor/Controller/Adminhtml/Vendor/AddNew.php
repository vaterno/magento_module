<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Vendor;

use Magento\Framework\App\Action\HttpGetActionInterface;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use PrAnd\Vendor\Controller\Adminhtml\Index\Index;

class AddNew extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'PrAnd_Vendor::vendors_addNew';

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu(Index::MENU)
            ->getConfig()
            ->getTitle()
            ->prepend(__('Add a new vendor.'));

        return $page;
    }
}
