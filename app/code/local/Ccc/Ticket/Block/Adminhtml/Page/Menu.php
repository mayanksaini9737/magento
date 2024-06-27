<?php

class Ccc_Ticket_Block_Adminhtml_Page_Menu extends Mage_Adminhtml_Block_Page_Menu
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ticket/page/menu.phtml');
    }

    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addCss('css/ticket/createForm.css');
        $this->getLayout()->getBlock('head')->addJs('lib/jquery/jquery-1.10.2.js');
        $this->getLayout()->getBlock('head')->addJs('ticket/createTicket.js');
        return parent::_prepareLayout();
    }

    public function getCurrentUser(){
        $user = Mage::getSingleton('admin/session')->getUser();
        return $user->getId();
    }

    public function getAdminUsers(){
        $collection = Mage::getModel('admin/user')->getCollection();
        return $collection;
    }

    public function getAllStatus(){
        $collection = Mage::getModel('ticket/status')->getCollection();
        return $collection;
    }

    public function getSaveUrl()
    {
        return $this->getUrl('adminhtml/ticket/saveTicket');
    }
}
