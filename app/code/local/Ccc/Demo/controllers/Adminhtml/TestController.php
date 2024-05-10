<?php 

class Ccc_Demo_Adminhtml_TestController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Manage Test'));
        $this->_initAction();
        $this->renderLayout();
    }
}
