<?php

class Nofrills_Booklayout_IndexController extends Mage_Core_Controller_Front_Action
{
    // public function indexAction()
    // {
    //     $block_1 = new Mage_Core_Block_Text();
    //     $block_1->setText('Original Text');

    //     $block_2 = new Mage_Core_Block_Text();
    //     $block_2->setText('The second sentence.');

    //     $main_block = new Mage_Core_Block_Template();
    //     $main_block->setTemplate('helloworld.phtml');

    //     $main_block->setChild('the_first', $block_1);
    //     $main_block->setChild('the_second', $block_2);

    //     $block_1->setText('Wait, I want this text instead.');
    //     echo $main_block->toHtml();
    // }

    public function helloblockAction()
    {
        $main_block = new Nofrills_Booklayout_Block_Helloworld();
        echo $main_block->toHtml();
    }

    public function layoutAction()
    {
        $layout = Mage::getSingleton('core/layout');
        $block = $layout->createBlock('nofrills_booklayout/helloworld', 'root');
    }
}