<?php

class Ccc_Test_IndexController extends Mage_Core_Controller_Front_Action
{
   public function indexAction()
   {
        $new = Mage::getModel('test/abc');
        var_dump(get_class($new));
   }
   public function viewAction()
   {
        echo 200000;
   }
}
