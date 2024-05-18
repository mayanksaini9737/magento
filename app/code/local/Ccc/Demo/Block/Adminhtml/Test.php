<?php
class Ccc_Demo_Block_Adminhtml_Test extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_test';
        $this->_blockGroup = 'demo';
        $this->_headerText = Mage::helper('demo')->__('Manage test');

        parent::__construct();
       $this->_removeButton('add');
    }
}
