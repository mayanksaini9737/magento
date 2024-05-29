<?php 

class Ccc_Promotions_Model_ActiveTag
{
    static public function getOptionArray()
    {
        $options = array();
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'active_tag');
        if ($attribute && $attribute->getFrontendInput() == 'select') {
            $attributeOptions = $attribute->getSource()->getAllOptions(false);
            foreach ($attributeOptions as $option) {
                if (!empty($option['value'])) {
                    $options[$option['value']] = $option['label'];
                }
            }
        }
        return $options;
    }
}