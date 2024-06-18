<?php

class Ccc_Ticket_Adminhtml_TicketController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
    }
    public function indexAction()
    {
        $page = $this->getRequest()->getParam('page', 1);
        $pageSize = 3;

        $ticketCollection = Mage::getModel('ticket/ticket')->getCollection();
        $ticketCollection->setPageSize($pageSize);
        $ticketCollection->setCurPage($page);

        Mage::register('current_page', $page);
        Mage::register('total_pages', ceil($ticketCollection->getSize() / $pageSize));
        Mage::register('ticket_collection', $ticketCollection);

        $this->_initAction();
        $this->_setActiveMenu('ticket');
        $this->renderLayout();
    }

    public function saveTicketAction()
    {
        if ($this->getRequest()->isPost()) {
            $ticketData = $this->getRequest()->getPost();

            $ticket = Mage::getModel('ticket/ticket');
            $ticket->setData($ticketData);
            $ticket->save();

            $response = [
                'success' => true,
                'message' => 'Ticket created successfully!'
            ];
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function viewAction()
    {
        $this->_title($this->__('Ticket'))->_title($this->__('Grid'))->_title($this->__('View'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function updateTicketAction()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            $data = [
                'ticket_id' => $postData['ticket_id'],
                $postData['field'] => $postData['val']
            ];
            // print_r($data);

            $ticket = Mage::getModel('ticket/ticket');
            $ticket->setData($data);
            $ticket->save();

            if ($postData['field'] == 'status'){
                $colorCode = Mage::getModel('ticket/status')->load($postData['val'], 'code')->getColorCode();
                $response = [
                    'success' => true,
                    'colorCode' => $colorCode,
                    'message' => 'Ticket Updated successfully!'
                ];
            } else{
                $response = [
                    'success' => true,
                    'message' => 'Ticket Updated successfully!'
                ];
            }

        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}