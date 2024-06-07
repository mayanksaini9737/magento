<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit_Tabs_Event extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('event_form');
        $this->setTemplate('outlook/eventForm.phtml');
    }

    public function getRegisterData()
    {
        return json_encode(Mage::registry('events_data')->getData());
    }

    public function getEventRemoveUrl()
    {
        return $this->getUrl('*/*/removeForEvents');
    }
}
