<?php

namespace PrAnd\Training\Block\Rewrite;

class ProductDescription extends \Magento\Catalog\Block\Product\View\Description
{
    protected function _beforeToHtml()
    {
        $this->getProduct()->setDescription('aarrrrrrrrrrrrrrrrrrrrrrrrt54t54y54ya');
        return parent::_beforeToHtml();
    }
}
