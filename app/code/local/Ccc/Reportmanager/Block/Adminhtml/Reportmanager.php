<?php
class Ccc_Reportmanager_Block_Adminhtml_Reportmanager extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_reportmanager';
        $this->_blockGroup = 'reportmanager';
        $this->_headerText = Mage::helper('reportmanager')->__('Report manager');
        parent::__construct();
    }
}