<?php
class Ccc_Repricer_Block_Adminhtml_Competitors extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_competitors';
        $this->_blockGroup = 'repricer';
        $this->_headerText = Mage::helper('repricer')->__('Manage Competitors');
        
        parent::__construct();
    }
}