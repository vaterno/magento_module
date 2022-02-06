<?php

namespace PrAnd\Training\Plugin\Model;

use \Magento\Catalog\Model\Product as ProductEntity;
use \PrAnd\Training\Helper\Config;

class Product
{
    /** @var Config  */
    protected $moduleConfig;

    public function __construct(
        Config $moduleConfig
    )
    {
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * @param ProductEntity $productEntity
     * @param float $price
     * @return float|int
     */
    public function afterGetPrice(
        ProductEntity $productEntity,
        float $price
    ): float
    {
        if ($this->moduleConfig->isPremiumPriceEnabled()) {
            $price += $this->moduleConfig->getPremiumPrice();
        }

        return $price;
    }
}