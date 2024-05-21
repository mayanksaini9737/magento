<?php
class Ccc_Help_Block_Adminhtml_Contact_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ContactGrid');
        $this->setDefaultSort('contact_id');
        $this->setDefaultDir('ASC');

        // for use ajax
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
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
                'index' => 'contact_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('help')->__('Name'),
                'index' => 'name',
                'column_css_class' => 'editable',
            )
        );

        $this->addColumn(
            'number',
            array(
                'header' => Mage::helper('help')->__('Contact Number'),
                'index' => 'number',
                'column_css_class' => 'editable',
            )
        );
        $this->addColumn(
            'help',
            array(
                'header' => Mage::helper('help')->__('Medium'),
                'index' => 'help',
                'type' => 'options',
                'options' => Mage::getModel('help/contact')->getMediumArray(),
                'column_css_class' => 'editable',
            )
        );

        $this->addColumn(
            'city',
            array(
                'header' => Mage::helper('help')->__('City'),
                'index' => 'city',
                'column_css_class' => 'editable',
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

        $this->addColumn(
            'edit',
            array(
                'header' => Mage::helper('help')->__('Edit'),
                'align' => 'left',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('help')->__('Edit'),
                        'url' => array(
                            'base' => '#',
                        ),
                        // 'field' => 'contact_id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'edit',
                'renderer' => 'Ccc_Help_Block_Adminhtml_Contact_Grid_Renderer_Row',
            )
        );

        return parent::_prepareColumns();
    }

    // public function getRowUrl($row)
    // {
    //     return $this->getUrl('*/*/edit', array('contact_id' => $row->getId()));
    // }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('help');
        $this->getMassactionBlock()->setFormFieldName('help'); 
        $mediumArr = Mage::getModel('help/contact')->getMediumArray();
        $statusArr = Mage::getModel('help/contact')->getStatusOptionArray();

        $this->getMassactionBlock()->addItem(
            'help',
            array(
                'label' => Mage::helper('help')->__('Change Medium'),
                'url' => $this->getUrl('*/*/massMedium', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'medium',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('help')->__('Medium'),
                        'values' => $mediumArr
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label' => Mage::helper('help')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('help')->__('Status'),
                        'values' => $statusArr
                    )
                )
            )
        );
        return $this;
    }
}