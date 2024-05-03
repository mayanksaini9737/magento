<?php
class Ccc_Banner_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_banner';
        $this->_blockGroup = 'banner';
        $this->_headerText = Mage::helper('banner')->__('Banner');
        
        parent::__construct();
    }
    public function _prepareLayout()
    {
        if(!(Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/add_new'))){
            $this->_removeButton('add');
        }
        return parent::_prepareLayout();
    }
}