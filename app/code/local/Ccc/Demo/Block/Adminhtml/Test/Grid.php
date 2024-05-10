<?php
class Ccc_Demo_Block_Adminhtml_Test_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('TestGrid');
        $this->setDefaultSort('test_id');
        $this->setDefaultDir('ASC');
    }
}