<?php 

class Ccc_Ticket_Block_Adminhtml_Ticket extends Mage_Adminhtml_Block_Widget_Container
{

    public function __construct()
    {
        $this->_headerText = Mage::helper('ticket')->__('Grid');
        parent::__construct();
        $this->setTemplate('ticket/ticket/table.phtml');
    }

    public function getTicketCollection()
    {
        return Mage::registry('ticket_collection');
    }

    public function getCurrentPage()
    {
        return Mage::registry('current_page');
    }

    public function getTotalPages()
    {
        return Mage::registry('total_pages');
    }

    public function getStatusColorCode($code)
    {
        $colorCode = Mage::getModel('ticket/status')->load($code, 'code')->getColorCode();
        return $colorCode;
    }

    public function getLabelOfCode($code)
    {
        $label = Mage::getModel('ticket/status')->load($code, 'code')->getLabel();
        return $label;
    }
    public function getUserName($id)
    {
        return Mage::getModel('admin/user')->load($id)->getUsername();
    }
    public function getAdminUsers(){
        $collection = Mage::getModel('admin/user')->getCollection();
        return $collection;
    }
    public function getCurrentFilters()
    {
        return $this->getRequest()->getParam('filter', []);
    }
    public function getAllStatus(){
        return Mage::getModel('ticket/status')->getCollection();
    }
    public function getAllFilters(){
        return Mage::getModel('ticket/filter')->getCollection();
    }
}