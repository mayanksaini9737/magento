<?php

class Nofrills_Booklayout_Block_Helloworld extends Mage_Core_Block_Template
{
    public function __construct()
    {
        echo 78328;
        $this->setTemplate('helloworld.phtml');
        parent::__construct();
    }

    public function fetchView($fileName)
    {
        //ignores file name, just uses a simple include with template name
        $this->setScriptPath(
            Mage::getModuleDir('', 'Nofrills_Booklayout') .
            DS .
            'design'
        );
        return parent::fetchView($this->getTemplate());
    }
    protected function _beforeToHtml()
    {
        $block1 = new Mage_Core_Block_Text();
        $block1->setText('The first sentence. ');
        $this->setChild('the_first', $block1);

        $block2 = new Mage_Core_Block_Text();
        $block2->setText('The second sentence. ');
        $this->setChild('the_second', $block2);
    }

    public function fetchTitle()
    {
        return 'Hello Fancy World';
    }
}
;