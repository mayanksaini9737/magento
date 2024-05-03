<?php

class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Renderer_Productinfo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $productName = $row->getData('product_name');
        $productSku = $row->getData('product_sku');

        $displayText = 'Product Name:'.$productName.'</br>SKU:'.$productSku;

        return $displayText;
    }
}