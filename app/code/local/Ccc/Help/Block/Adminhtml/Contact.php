<?php
class Ccc_Help_Block_Adminhtml_Contact extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_contact';
        $this->_blockGroup = 'help';
        $this->_headerText = Mage::helper('help')->__('Manage Contact');
        parent::__construct();
    }
}
