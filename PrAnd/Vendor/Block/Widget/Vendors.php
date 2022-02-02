<?php

namespace PrAnd\Vendor\Block\Widget;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

use PrAnd\Vendor\Api\VendorRepositoryInterface;

class Vendors extends Template implements BlockInterface
{
    /** @var VendorRepositoryInterface */
    protected $vendorRepository;

    /** @var string $_template */
    protected $_template = 'widgets/vendor_list/index.phtml';

    /**
     * @param Template\Context $context
     * @param VendorRepositoryInterface $vendorRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        VendorRepositoryInterface $vendorRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->vendorRepository = $vendorRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return SearchResultsInterface
     */
    public function getVendors(): SearchResultsInterface
    {
        static $vendorsList  = null;

        if ($vendorsList !== null) {
            return $vendorsList;
        }

        /** @var SearchResultsInterface $vendorsList */
        $vendorsList = $this->vendorRepository->getList($this->searchCriteriaBuilder->create());

        return $vendorsList;
    }
}