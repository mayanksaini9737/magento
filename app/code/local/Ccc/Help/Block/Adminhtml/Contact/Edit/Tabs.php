<?php 

class Ccc_Help_Block_Adminhtml_Contact_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent:: __construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('help')->__('Contact Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('tab_first', array(
            'label' => Mage::helper('help')->__('General Information'),
            'title' => Mage::helper('help')->__('General Information'),
            'content' => $this->getLayout()->createBlock('help/adminhtml_contact_edit_tabs_form')->toHtml()
        ));
        
        $this->addTab('tab_second', array(
            'label' => Mage::helper('help')->__('status'),
            'title' => Mage::helper('help')->__('Status'),
            'content' => $this->getLayout()->createBlock('help/adminhtml_contact_edit_tabs_secondtab')->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}