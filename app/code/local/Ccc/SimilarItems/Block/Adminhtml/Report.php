<?php
class Ccc_SimilarItems_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        $this->_headerText = Mage::helper('similarItems')->__('Report');
        parent::__construct();
    }

    public function getProductArray()
    {
        return Mage::getModel('similarItems/similarItems')->getProductArray();
    }
}