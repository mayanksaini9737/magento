<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('outlook')->__('Configuration Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('tab_first', array(
            'label' => Mage::helper('outlook')->__('General Information'),
            'title' => Mage::helper('outlook')->__('General Information'),
            'content' => $this->getLayout()->createBlock('outlook/adminhtml_configuration_edit_tabs_general')->toHtml()
        ));

        $this->addTab('tab_second', array(
            'label' => Mage::helper('outlook')->__('Event'),
            'title' => Mage::helper('outlook')->__('Event'),
            'content' => $this->getLayout()->createBlock('outlook/adminhtml_configuration_edit_tabs_event')->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}