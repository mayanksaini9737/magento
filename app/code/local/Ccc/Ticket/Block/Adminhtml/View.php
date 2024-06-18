<?php 

class Ccc_Ticket_Block_Adminhtml_View extends Mage_Adminhtml_Block_Widget_Container
{

    public function __construct()
    {
        $this->_headerText = Mage::helper('ticket')->__('View');
        parent::__construct();
        $this->setTemplate('ticket/ticket/view.phtml');
    }
    public function getTicketDetails()
    {
        $id = $this->getRequest()->getParam('id');
        return Mage::getModel('ticket/ticket')->load($id);
    }

    public function getCurrentUser(){
        $user = Mage::getSingleton('admin/session')->getUser();
        return $user->getId();
    }

    public function getAdminUsers(){
        return Mage::getModel('admin/user')->getCollection();
    }

    public function getAllStatus(){
        return Mage::getModel('ticket/status')->getCollection();
    }
    public function getStatusColorCode($code)
    {
        return Mage::getModel('ticket/status')->load($code, 'code')
            ->getColorCode();
        
    }

    public function getUpdateUrl()
    {
        return $this->getUrl('adminhtml/ticket/updateTicket');
    }
    public function getSaveCommentUrl()
    {
        return $this->getUrl('adminhtml/comment/save');
    }
    public function getAllComments($id)
    {
        return Mage::getModel('ticket/comment')
            ->getCollection()
            ->addFieldToFilter('ticket_id', $id);
    }
    public function getUserName($id)
    {
        return Mage::getModel('admin/user')->load($id)->getUsername();
    }

}