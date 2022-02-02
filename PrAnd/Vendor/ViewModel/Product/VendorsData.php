<?php

namespace PrAnd\Vendor\ViewModel\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\Api\AttributeValue;
use Magento\Framework\View\Element\Block\ArgumentInterface;

use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Api\VendorImageServiceInterface;
use PrAnd\Vendor\Api\VendorRepositoryInterface;

class VendorsData implements ArgumentInterface
{
    /** @var VendorRepositoryInterface  */
    protected $vendorRepository;

    /** @var VendorImageServiceInterface  */
    protected $vendorImageService;

    public function __construct(
        VendorRepositoryInterface $vendorRepository,
        VendorImageServiceInterface $vendorImageService
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->vendorImageService = $vendorImageService;
    }

    /**
     * @param Product $product
     * @return VendorInterface[]
     */
    public function getData(Product $product): array
    {
        $result = [];
        /** @var AttributeValue|null $vendorAttrs */
        $vendorAttrs = $product->getCustomAttribute(\PrAnd\Vendor\Api\Data\VendorInterface::ENTITY);

        if (!empty($vendorAttrs) && !empty($vendorAttrs->getValue())) {
            $vendorAttrsIds = explode(',', $vendorAttrs->getValue());

            /** @var VendorInterface[] $result */
            $result = $this->vendorRepository->getByIds($vendorAttrsIds) ?: [];
            $result = $this->modifyImage($result);
        }

        return $result;
    }

    /**
     * @param VendorInterface[] $vendorModels
     * @return VendorInterface[]
     */
    protected function modifyImage(array $vendorModels): array
    {
        if (!empty($vendorModels)) {
            $vendorModels = array_reduce($vendorModels, function ($carry, $item) {
                /** @var VendorInterface $item */
                if (!empty($item->getImage())) {
                    $imageUrl = $this->vendorImageService->getUrl($item->getImage());
                    $item->setImage($imageUrl);
                }
                $carry[] = $item;

                return $carry;
            });
        }

        return $vendorModels;
    }
}
