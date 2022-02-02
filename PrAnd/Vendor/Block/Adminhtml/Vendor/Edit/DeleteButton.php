<?php

namespace PrAnd\Vendor\Block\Adminhtml\Vendor\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

class DeleteButton implements ButtonProviderInterface
{
    const REQUEST_FIELD_NAME = 'id';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];
        $id = $this->request->getParam(static::REQUEST_FIELD_NAME);

        if (!empty($id)) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this vendor?')
                    . '\', \'' . $this->getDeleteUrl($id) . '\', {data: {}})',
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * @param $carId
     * @return string
     */
    public function getDeleteUrl(int $id): string
    {
        return $this->urlBuilder->getUrl('*/*/delete', [
            static::REQUEST_FIELD_NAME => $id
        ]);
    }
}
