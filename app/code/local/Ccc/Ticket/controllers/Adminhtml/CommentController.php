<?php 

class Ccc_Ticket_Adminhtml_CommentController extends Mage_Adminhtml_Controller_Action
{
    public function saveAction(){
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            // print_r($data);

            $comment = Mage::getModel('ticket/comment');
            $comment->setData($data);
            $comment->save();
            
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ticket')->__('Comment saved Successfully.')
            );
            $this->_redirect('*/ticket/view/id/'. $data['ticket_id']);
        }
    }
}