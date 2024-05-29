<?php
class Ccc_Promotions_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        $this->_headerText = Mage::helper('promotions')->__('Report');
        parent::__construct();
    }

    protected function _prepareLayout()
    {
        $this->setTemplate('promotions/report.phtml');
        return parent::_prepareLayout();
    }
    public function getPromotions()
    {
        $collection = Mage::getModel('promotions/promotions')->getCollection();
        $collection->addFieldToSelect('tag_name');
        return $collection->getData();
    }


}