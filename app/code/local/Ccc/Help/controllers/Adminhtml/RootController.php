<?php
class Ccc_Help_Adminhtml_RootController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        Mage::dispatchEvent('event_practice', []);
    }
}