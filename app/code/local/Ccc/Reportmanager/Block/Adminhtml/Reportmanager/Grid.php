<?php
class Ccc_Reportmanager_Block_Adminhtml_Reportmanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('reportmanager_grid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('reportmanager/reportmanager')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => Mage::helper('reportmanager')->__('Report Filter Id'),
                'index' => 'id',
            )
        );

        $this->addColumn(
            'user_id',
            array(
                'header' => Mage::helper('reportmanager')->__('User Id'),
                'index' => 'user_id',
            )
        );

        $this->addColumn(
            'report_type',
            array(
                'header' => Mage::helper('reportmanager')->__('Report Type'),
                'type' => 'options',
                'index' => 'report_type',
                'options' => Mage::getModel('reportmanager/reportmanager')->getReportType(),
            )
        );

        $this->addColumn(
            'filter_data',
            array(
                'header' => Mage::helper('reportmanager')->__('Filter Data'),
                'type' => 'text',
                'index' => 'filter_data',
            )
        );

        $this->addColumn(
            'is_active',
            array(
                'header' => Mage::helper('reportmanager')->__('Active'),
                'index' => 'is_active',
                'type' => 'options',
                'options' =>  Mage::getModel('reportmanager/reportmanager')->getStatusArray(),
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('reportmanager')->__('Created At'),
                'index' => 'created_at',
                'type'=>'datetime',
            )
        );

        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('reportmanager')->__('Updated Date'),
                'index' => 'updated_date',
                'type'=>'datetime',
            )
        );

        $this->addColumn(
            'delete',
            array(
                'header' => Mage::helper('reportmanager')->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('reportmanager')->__('Delete'),
                        'url' => array(
                            'base' => '*/*/delete',
                        ),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                // 'index' => 'delete',
            )
        );

        return parent::_prepareColumns();
    }

    // public function getRowUrl($row)
    // {
    //     return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    // }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('reportmanager');
        $this->getMassactionBlock()->setFormFieldName('reportmanager'); 
        $activeArr = Mage::getModel('reportmanager/reportmanager')->getStatusArray();

        $this->getMassactionBlock()->addItem(
            'is_active',
            array(
                'label' => Mage::helper('reportmanager')->__('Change Active State'),
                'url' => $this->getUrl('*/*/massActive', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'is_active',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('reportmanager')->__('Is Active'),
                        'values' => $activeArr
                    )
                )
            )
        );
        return $this;
    }
}