<?php
class Ccc_Filemanager_Block_Adminhtml_Filemanager extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_filemanager';
        $this->_blockGroup = 'filemanager';
        $this->_headerText = Mage::helper('filemanager')->__('Manage Filemanager');
        parent::__construct();
        $this->_removeButton('add');
        $this->setTemplate('filemanager/container.phtml');
    }

    public function getPaths()
    {
        $configData = Mage::getStoreConfig('filemanager/general/enable_textarea');
        $paths = preg_split('/\s+/', $configData);
        return $paths;
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('*/*/index');
    }
}