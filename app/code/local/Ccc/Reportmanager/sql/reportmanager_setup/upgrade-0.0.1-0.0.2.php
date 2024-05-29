<?php

$installer = $this;
$installer->startSetup();

$attributeCode = 'sold_count';
$attributeLabel = 'Sold Count';

$data = array(
    'attribute_code'  => $attributeCode,
    'type'            => 'int', 
    'input'           => 'text', 
    'default'         => '0',
    'label'           => $attributeLabel, 
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required'        => false,
    'configurable'    => false,
    'apply_to'        => '', 
    'visible_on_front'=> true,
    'user_defined'    => true,
    'searchable'      => false,
    'filterable'      => false,
    'comparable'      => false,
    'used_for_promo_rules' => true,
    'is_html_allowed_on_front' => true,
);

$installer->addAttribute('catalog_product', $attributeCode, $data);

$installer->endSetup();
