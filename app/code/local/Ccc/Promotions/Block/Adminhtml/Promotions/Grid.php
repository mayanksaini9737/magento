<?php
class Ccc_Promotions_Block_Adminhtml_Promotions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('promotions_Grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('promotions/promotions')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => Mage::helper('promotions')->__('Promotion Id'),
                'index' => 'id',
            )
        );

        $this->addColumn(
            'tag_name',
            array(
                'header' => Mage::helper('promotions')->__('Tag Name'),
                'index' => 'tag_name',
            )
        );

        $this->addColumn(
            'percentage',
            array(
                'header' => Mage::helper('promotions')->__('Percentage'),
                'type' => 'number',
                'index' => 'percentage',
            )
        );

        $this->addColumn(
            'priority',
            array(
                'header' => Mage::helper('promotions')->__('Priority'),
                'type' => 'number',
                'index' => 'priority',
            )
        );

        $this->addColumn(
            'is_active',
            array(
                'header' => Mage::helper('promotions')->__('Active'),
                'index' => 'is_active',
                'type' => 'options',
                'options' =>  Mage::getModel('promotions/promotions')->getStatusArray(),
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('promotions')->__('Created At'),
                'index' => 'created_at',
                'type'=>'datetime',
            )
        );

        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('promotions')->__('Updated Date'),
                'index' => 'updated_date',
                'type'=>'datetime',
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('promotions');
        $this->getMassactionBlock()->setFormFieldName('promotions'); 
        $activeArr = Mage::getModel('promotions/promotions')->getStatusArray();

        $this->getMassactionBlock()->addItem(
            'is_active',
            array(
                'label' => Mage::helper('promotions')->__('Change Active State'),
                'url' => $this->getUrl('*/*/massActive', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'is_active',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('promotions')->__('Is Active'),
                        'values' => $activeArr
                    )
                )
            )
        );
        return $this;
    }
}