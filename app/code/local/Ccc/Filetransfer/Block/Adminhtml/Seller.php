<?php 

class Ccc_Filetransfer_Block_Adminhtml_Seller extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_seller';
        $this->_blockGroup = 'filetransfer';
        $this->_headerText = Mage::helper('filetransfer')->__('Manage Seller');
        parent::__construct();
    }
}