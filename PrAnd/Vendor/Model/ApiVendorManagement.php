<?php

namespace PrAnd\Vendor\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;

use PrAnd\Vendor\Model\Vendor;
use PrAnd\Vendor\Api\ApiVendorManagementInterface;
use PrAnd\Vendor\Api\VendorRepositoryInterface;

class ApiVendorManagement implements ApiVendorManagementInterface
{
    /** @var VendorRepositoryInterface */
    protected $vendorRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        VendorRepositoryInterface $vendorRepository
    )
    {
        $this->vendorRepository = $vendorRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return Vendor[]
     */
    public function getList(): array
    {
        /** @var SearchResultsInterface $vendors */
        $vendors = $this->vendorRepository->getList($this->searchCriteriaBuilder->create());
        $vendors = (array)$vendors->getItems();

        return $vendors;
    }
}