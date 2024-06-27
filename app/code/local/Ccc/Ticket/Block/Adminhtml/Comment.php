<?php 

class Ccc_Ticket_Block_Adminhtml_Comment extends Mage_Adminhtml_Block_Widget_Container
{
    protected $_level = 0;
    protected $_ticketId = null;
    protected $_has_child = 0;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ticket/ticket/comment.phtml');
    }

    public function getCommentCollection()
    {
        $this->_ticketId = $this->getRequest()->getParam('id');
        
        $collection = Mage::getModel('ticket/comment')
            ->getCollection()
            ->addFieldToFilter('ticket_id', $this->_ticketId);

        $filter = $this->getRequest()->getParam('filter');
        if ($filter){
            $collection->addFieldToFilter('complete', 0);
        }
        $comments = [];
        foreach ($collection as $_comment) {
            $comments[] = $_comment->getData();
            if (($_comment->getLevel() > $this->_level) && ($_comment->getIsLock() == 1) ){
                $this->_level = $_comment->getLevel();
            }
        }
        return $this->buildHierarchy($comments);
    }

    public function buildHierarchy(array $comments, $parentId = 0) 
    {
        $result = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId ) {
                $children = $this->buildHierarchy($comments, $comment['comment_id']);
                if ($children) {
                    $comment['reply'] = $children;
                    $comment['rowspan'] = array_reduce($children, function($carry, $item) {
                        return $carry + $item['rowspan'];
                    }, 0)+1;
                } else {
                    $comment['rowspan'] = 1;    
                }   
                $result[] = $comment;
            }
        }
        return $result;
    }

    public function renderComments($comments) 
    {
        $html = '';
        foreach ($comments as $comment) {
            $html .= '<tr>';
        
            if(($comment['parent_id'] == 0) && ($comment['level'] > 1)){
                $colspanLevel = $comment['level'] - 1;
                $html .= '<td colspan="' . $colspanLevel . '" rowspan="'.$comment['rowspan'].'"></td>';
            }
            
            $html .= '<td rowspan="' . $comment['rowspan'] . '">';
            $html .= $comment['comment'];
            // $html .= $comment['comment_id'];
            if ($comment['complete'] && !isset($comment['reply'])){
                $html .= '<span class="complete-tag">Completed</span>';
            }
            
            if ((($comment['level'] == $this->_level)) && $comment['complete'] != 1 ) 
            {
                $html .= '<button class="reply-btn" 
                    data-commentid="'.$comment['comment_id'].'" 
                    data-level="'.$comment['level'].'" 
                    data-ticketid="'.$this->_ticketId.'" 
                    data-url="'.$this->getUrl('adminhtml/comment/saveReply').'" 
                    >Add Reply</button>';
                if (!isset($comment['reply'])){
                    $html .= '<button class="complete-btn" 
                        data-commentid="'.$comment['comment_id'].'"
                        data-ticketid="'.$this->_ticketId.'"
                        data-url="'.$this->getUrl('adminhtml/comment/complete') .'"
                        >Complete</button>';
                }
            }
            
            $html .= '</td>';
            $html .= '</tr>';
    
            if (!empty($comment['reply'])) {
                $html .= $this->renderComments($comment['reply']);
            }
        }
        return $html;
    }

    public function renderLockAndAddQue()
    {
        $colspanLevel = $this->_level - 1;
        
        if ($this->_level == 1){
            $html = '<tr>';
            $html .= '<td><button class="add-question-btn" 
                data-level="'.$this->_level .'" 
                data-ticketid="'.$this->_ticketId.'"
                data-url="'.$this->getUrl('adminhtml/comment/addQuestion').'"
                >Add Question</button></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td><button class="lock-btn"
                data-url="'.$this->getUrl('adminhtml/comment/lock').'"
                data-ticketid="'.$this->_ticketId.'" 
                data-level = "'.$this->_level.'"
                >Lock</button></td>';
            $html .= '</tr>';
            return $html;
        } elseif ($this->_level>1) {
            $html = '<tr>';
            $html .= '<td colspan="'.$colspanLevel.'"></td>';
            $html .= '<td><button class="add-question-btn" 
                data-level="'.$this->_level.'" 
                data-ticketid="'.$this->_ticketId.'"
                data-url="'.$this->getUrl('adminhtml/comment/addQuestion').'"
                >Add Question</button></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="'.$colspanLevel.'"></td>';
            $html .= '<td><button class="lock-btn"
                data-url="'.$this->getUrl('adminhtml/comment/lock').'"
                data-ticketid="'.$this->_ticketId.'" 
                data-level = "'.$this->_level.'"
                >Lock</button></td>';
            $html .= '</tr>';
            return $html;
        }
    }
    
}