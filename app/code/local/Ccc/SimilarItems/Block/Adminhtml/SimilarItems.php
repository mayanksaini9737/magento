<?php
class Ccc_SimilarItems_Block_Adminhtml_SimilarItems extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'similarItems'; 
        $this->_controller = 'adminhtml_similarItems';
        $this->_headerText = Mage::helper('similarItems')->__('Manage Similar Items');
        
        parent::__construct();
    }
}
