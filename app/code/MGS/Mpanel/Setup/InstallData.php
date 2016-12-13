<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MGS\Mpanel\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{
	/**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
	
    private $eavSetupFactory; 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory, CustomerSetupFactory $customerSetupFactory, AttributeSetFactory $attributeSetFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
		$this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
		/* Create thumbnail attribute for category */
        /** @var EavSetup $eavSetup */
        $categorySetup = $this->eavSetupFactory->create(['setup' => $setup]);
		$entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        /**
         * Add attributes to the eav/attribute
         */ 
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
			'thumbnail', 
			[
				'type' => 'varchar',
				'label' => 'Thumbnail Image',
				'input' => 'image',
				'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
				'required' => false,
				'sort_order' => 1,
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
				'group' => 'Content',
			]
        );
		
		$idg =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Content');
		$categorySetup->addAttributeToGroup(
			$entityTypeId,
			$attributeSetId,
			$idg,
			'thumbnail',
			1
		);

		
		/* Create customer attribute is_builder_account for front-end builder*/
		/** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        
        $customerSetup->addAttribute(Customer::ENTITY, 'is_builder_account', [
            'type' => 'int',
            'label' => 'Is Front-end Builder Account',
            'input' => 'select',
            'required' => false,
            'visible' => true,
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'default' => '0',
            'system' => 0,
        ]);
        
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'is_builder_account')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer'],
        ]);
        
        $attribute->save();
    }
}