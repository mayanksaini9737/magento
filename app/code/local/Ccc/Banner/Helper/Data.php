<?php
class Ccc_Banner_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_NODE_BANNER_TEMPLATE_FILTER = 'global/banner/banner/template_filter';

    /**
     * Retrieve Template processor for Banner Content
     *
     * @return Varien_Filter_Template
     */
    public function getBannerTemplateProcessor()
    {
        $model = (string)Mage::getConfig()->getNode(self::XML_NODE_BANNER_TEMPLATE_FILTER);
        return Mage::getModel($model);
    }
}
