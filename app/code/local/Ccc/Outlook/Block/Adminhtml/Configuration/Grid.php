<?php 

class Ccc_Outlook_Block_Adminhtml_Configuration_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ConfigurationGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('outlook/configuration')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'config_id',
            array(
                'header' => Mage::helper('outlook')->__('Config ID'),
                'index' => 'config_id',
            )
        );

        $this->addColumn(
            'client_id',
            array(
                'header' => Mage::helper('outlook')->__('Client Id'),
                'index' => 'client_id',
            )
        );

        $this->addColumn(
            'secret_value',
            array(
                'header' => Mage::helper('outlook')->__('Client Secret Value'),
                'index' => 'secret_value',
            )
        );

        $this->addColumn(
            'access_token',
            array(
                'header' => Mage::helper('outlook')->__('Access Token'),
                'index' => 'access_token',
            )
        );

        $this->addColumn(
            'is_active',
            array(
                'header' => Mage::helper('outlook')->__('Is Active'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => Mage::getModel('outlook/configuration')->getIsActiveArray(),
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('config_id' => $row->getId()));
    }

}