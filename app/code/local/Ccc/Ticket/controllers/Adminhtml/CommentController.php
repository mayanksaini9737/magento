<?php
use Mage\Checkout\Test\Block\Multishipping\Success;

class Ccc_Ticket_Adminhtml_CommentController extends Mage_Adminhtml_Controller_Action
{
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            // print_r($data);
            $data['level'] = 1;
            $data['is_lock'] = 1;

            $comment = Mage::getModel('ticket/comment');
            $comment->setData($data);
            $comment->save();

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ticket')->__('Comment saved Successfully.')
            );
            $this->_redirect('*/ticket/view/id/' . $data['ticket_id']);
        }
    }

    public function saveReplyAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $user = Mage::getSingleton('admin/session')->getUser();
            $userId = $user->getId();

            $data = [
                'parent_id' => $data['parentId'],
                'level' => $data['level'],
                'ticket_id' => $data['id'],
                'comment' => $data['reply'],
                'user_id' => $userId
            ];

            $comment = Mage::getModel('ticket/comment');
            $comment->setData($data);
            $comment->save();

            $block = $this->getLayout()->createBlock('ticket/adminhtml_comment');
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function lockAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $ticketId = $data['id'];
            $level = $data['level'];

            $model = Mage::getModel('ticket/comment');

            $commentCollection = $model->getCollection()
                ->addFieldToFilter('ticket_id', $ticketId)
                ->addFieldToFilter('level', array('gteq' => $level));

            $commentIds = [];
            foreach ($commentCollection as $comment) {
                $comment->setIsLock(1)->save();
                if ($comment->getLevel() == $level){
                    $commentIds[] = $comment->getId();
                }
            }

            $checkCompleteCollection = $model->getCollection()
                ->addFieldToFilter('parent_id', array('in' => $commentIds));
            $checkCompleteCollection->getSelect()->group('parent_id');
            $checkCompleteCollection->addFieldToSelect('parent_id');

            $_hasChild = [];
            foreach ($checkCompleteCollection as $_comment) {
                $_hasChild[] = $_comment->getParentId();
            }

            $result = array_diff($commentIds, $_hasChild);
            foreach ($result as $parentId) {
                $model->load($parentId)->setComplete(1)->save();
                $model->checkParentComplete($model->getParentId());
            }

            $block = $this->getLayout()->createBlock('ticket/adminhtml_comment');
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function completeAction()
    {
        if ($this->getRequest()->isPost()) {
            $commentId = $this->getRequest()->getPost('comment_id');
            $model = Mage::getModel('ticket/comment');
            $model->load($commentId)->setComplete(1)->save();
            $parentId = $model->getParentId();
            $model->checkParentComplete($parentId);
        }
    }

    public function addQuestionAction()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            $user = Mage::getSingleton('admin/session')->getUser();
            $userId = $user->getId();

            $data = [
                'ticket_id' => $postData['ticketId'],
                'level' => $postData['level'],
                'comment' => $postData['reply'],
                'user_id' => $userId
            ];

            $comment = Mage::getModel('ticket/comment')
                ->setData($data)->save();
            $response = [];
            $response['comment_id'] = $comment->getId();
            $response['complete_url'] = $this->getUrl('adminhtml/comment/complete');
            $response['reply_url'] = $this->getUrl('adminhtml/comment/saveReply');
            // print_r($info);
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        }
    }
}