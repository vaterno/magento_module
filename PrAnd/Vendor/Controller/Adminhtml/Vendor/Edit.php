<?php

namespace PrAnd\Vendor\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;

use PrAnd\Vendor\Controller\Adminhtml\Index\Index;

class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'PrAnd_Vendor::vendors_edit';

    /**
     * @var string[]
     */
    protected $_publicActions = [
        'edit'
    ];

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
            ->prepend('Edit');

        return $page;
    }
}
