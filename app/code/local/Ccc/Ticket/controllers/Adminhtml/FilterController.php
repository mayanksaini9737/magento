<?php 

class Ccc_Ticket_Adminhtml_FilterController extends Mage_Adminhtml_Controller_Action
{
    public function saveAction(){
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $status = implode(',',$data['status']);
            $assignedTo = implode(',', $data['assigned_to']);
            $data['status'] = $status;
            $data['assigned_to'] = $assignedTo;

            $filter = Mage::getModel('ticket/filter');
            $filter->setData($data);
            $filter->save();
            
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ticket')->__('Filter saved Successfully.')
            );
            $this->_redirect('*/ticket/index');
        }
    }
}