<?php 

class Ccc_Filetransfer_Block_Adminhtml_Configuration_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ConfigurationGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('filetransfer/config')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'config_id',
            array(
                'header' => Mage::helper('filetransfer')->__('Config ID'),
                'index' => 'config_id',
            )
        );

        $this->addColumn(
            'host',
            array(
                'header' => Mage::helper('filetransfer')->__('Host'),
                'index' => 'host',
            )
        );

        $this->addColumn(
            'user_id',
            array(
                'header' => Mage::helper('filetransfer')->__('User Id'),
                'index' => 'user_id',
            )
        );

        $this->addColumn(
            'password',
            array(
                'header' => Mage::helper('filetransfer')->__('Password'),
                'index' => 'password',
            )
        );

        $this->addColumn(
            'remote_path',
            array(
                'header' => Mage::helper('filetransfer')->__('Remote Path'),
                'index' => 'remote_path',
            )
        );

        $this->addColumn(
            'port',
            array(
                'header' => Mage::helper('filetransfer')->__('Port'),
                'index' => 'port',
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('config_id' => $row->getConfigId()));
    }

}