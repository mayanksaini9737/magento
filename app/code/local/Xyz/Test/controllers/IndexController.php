<?php

class Xyz_Test_IndexController extends Mage_Core_Controller_Front_Action
{
   public function indexAction()
   {
        $new = Mage::getModel('xyz_test/abc');
        var_dump(get_class($new));
   }
   public function viewAction()
   {
      echo 3828;
   }
}
