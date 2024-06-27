<?php

class Ccc_Ticket_Model_Comment extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('ticket/comment');
    }

    public function checkParentComplete($commentId)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('parent_id', $commentId)
            ->addFieldToFilter('complete', 0);
        if(!$collection->getSize()){
            $this->load($commentId)->setComplete(1)->save();
            $this->checkParentComplete($this->getParentId());
        }
        return;
    }

}