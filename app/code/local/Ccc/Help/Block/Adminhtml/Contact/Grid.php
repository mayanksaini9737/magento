<?php
class Ccc_Help_Block_Adminhtml_Contact_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ContactGrid');
        $this->setDefaultSort('contact_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('help/contact')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'contact_id',
            array(
                'header' => Mage::helper('help')->__('Contact ID'),
                'align' => 'left',
                'index' => 'contact_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('help')->__('Name'),
                'align' => 'left',
                'index' => 'name',
            )
        );

        $this->addColumn(
            'number',
            array(
                'header' => Mage::helper('help')->__('Contact Number'),
                'align' => 'left',
                'index' => 'number',
            )
        );

        $this->addColumn(
            'city',
            array(
                'header' => Mage::helper('help')->__('City'),
                'align' => 'left',
                'index' => 'city',
            )
        );

        $this->addColumn(
            'created_date',
            array(
                'header' => Mage::helper('help')->__('Created At'),
                'index' => 'created_date',
                'type' => 'datetime',
            )
        );

        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('help')->__('Updated At'),
                'index' => 'updated_date',
                'type' => 'datetime',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('help')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Mage::getModel('help/contact')->getStatusOptionArray()
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('contact_id' => $row->getId()));
    }
}