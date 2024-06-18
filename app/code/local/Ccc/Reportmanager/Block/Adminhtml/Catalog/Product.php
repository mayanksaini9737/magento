<?php 

class Ccc_Reportmanager_Block_Adminhtml_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $url = $this->getUrl('*/*/saveReport');
        $this->_addButton('report', array(
            'label'   => Mage::helper('catalog')->__('Save Report'),
            'id'      => 'save_report_button',
            'data-url' => $url,
            'onclick' => "setLocation('{$this->getUrl('*/*/saveReport')}')",
        ));
        return $this;
    }
}