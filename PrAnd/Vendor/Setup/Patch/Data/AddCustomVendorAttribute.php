<?php

namespace PrAnd\Vendor\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use PrAnd\Vendor\Model\Entity\Attribute\Source\AvailableVendors;
use PrAnd\Vendor\Model\ResourceModel\Vendor as ResourceVendor;
use PrAnd\Vendor\Model\Vendor;
use Magento\Eav\Setup\EavSetupFactory;

class AddCustomVendorAttribute implements DataPatchInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->vendorSetup = $eavSetupFactory->create(['setup' => $moduleDataSetup]);
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->vendorSetup->installEntities([
            Vendor::ENTITY => [
                'entity_model' => ResourceVendor::class,
                'table' => Vendor::ENTITY . '_entity',
                'attributes' => [
                    Vendor::NAME => [
                        'group' => 'General',
                        'type' => 'text',
                        'label' => 'Name',
                        'input' => 'text',
                        'required' => true,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'used_in_product_listing' => true
                    ],
                    Vendor::DESCRIPTION => [
                        'group' => 'General',
                        'type' => 'text',
                        'label' => 'Name',
                        'input' => 'textarea',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'used_in_product_listing' => true
                    ],
                    Vendor::CREATED_AT => [
                        'group' => 'General',
                        'type' => 'static',
                        'input' => 'date',
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'visible' => 0,
                        'required' => false
                    ],
                    Vendor::UPDATED_AT => [
                        'group' => 'General',
                        'type' => 'static',
                        'input' => 'date',
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'visible' => 0,
                        'required' => false
                    ],
                    Vendor::IMAGE => [
                        'group' => 'General',
                        'type' => 'static',
                        'label' => 'Image',
                        'input' => 'image',
                        'required' => false,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => true
                    ],
                ]
            ]
        ]);

        $this->vendorSetup->addAttribute(Product::ENTITY, Vendor::ENTITY, [
            'group' => 'General',
            'label' => 'Vendor',
            'type' => 'text',
            'input' => 'multiselect',
            'source' => AvailableVendors::class,
            'required' => false,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'used_in_product_listing' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'backend' => ArrayBackend::class
        ]);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }
}