<?php 

class Ccc_Filetransfer_Block_Adminhtml_Files extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_files';
        $this->_blockGroup = 'filetransfer';
        $this->_headerText = Mage::helper('filetransfer')->__('Manage Files');
        parent::__construct();
    }
}