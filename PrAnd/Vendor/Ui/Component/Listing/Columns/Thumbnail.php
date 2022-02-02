<?php

namespace PrAnd\Vendor\Ui\Component\Listing\Columns;

use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

use PrAnd\Vendor\Api\VendorImageServiceInterface;

/**
 * Class Thumbnail
 *
 * @api
 * @since 100.0.2
 */
class Thumbnail extends Column
{

    /** @var VendorImageServiceInterface  */
    protected $vendorImage;

    /** @var UrlInterface  */
    protected $urlBuilder;

    /** @var \Magento\Catalog\Helper\Image */
    protected $imageHelper;

    /**
     * Thumbnail constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param VendorImageServiceInterface $vendorImage
     * @param UrlInterface $urlBulder
     * @param ImageFactory $imageHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        VendorImageServiceInterface $vendorImage,
        UrlInterface $urlBuilder,
        ImageFactory $imageHelper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->imageHelper = $imageHelper->create();
        $this->urlBuilder = $urlBuilder;
        $this->vendorImage = $vendorImage;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!empty($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as &$item) {
                $imageName = $item['image'] ?? '';
                $urlToImage = $this->vendorImage->getUrl($imageName);

                $item[$fieldName . '_src'] = $urlToImage ?: $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
                $item[$fieldName . '_alt'] = $imageName;
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl('vendors/vendor/edit', [
                    'id' => $item['entity_id']
                ]);
                $item[$fieldName . '_orig_src'] = $urlToImage ?: $this->imageHelper->getDefaultPlaceholderUrl('image');
            }
        }

        return $dataSource;
    }
}
