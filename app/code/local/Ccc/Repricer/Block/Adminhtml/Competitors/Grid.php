<?php
class Ccc_Repricer_Block_Adminhtml_Competitors_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('competitorsGrid');
        $this->setDefaultSort('competitor_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('repricer/competitors')->getCollection();

        // if (!(Mage::getSingleton('admin/session')->isAllowed('banner/banner/actions/show_all'))) {
        //     $collection->setOrder('banner_id', 'DESC');
        //     $collection->getSelect()->limit('5'); 
        //     foreach ($collection as $record){}
        // }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'competitor_id',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Id'),
                'align' => 'left',
                'index' => 'competitor_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Name'),
                'align' => 'left',
                'index' => 'name',
            )
        );

        $this->addColumn(
            'url',
            array(
                'header' => Mage::helper('repricer')->__('Competitor URL'),
                'align' => 'left',
                'index' => 'url',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('repricer')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' =>  Mage::getModel('repricer/status')->getOptionArray(),
            )
        );

        $this->addColumn(
            'filename',
            array(
                'header' => Mage::helper('repricer')->__('File Name'),
                'align' => 'left',
                'index' => 'filename',
            )
        );

        $this->addColumn(
            'created_date',
            array(
                'header' => Mage::helper('repricer')->__('Created Date'),
                'align' => 'left',
                'index' => 'created_date',
                'type'=>'datetime',
                'renderer'=> 'repricer/adminhtml_competitors_grid_renderer_datetime'
            )
        );

        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('repricer')->__('Updated Date'),
                'align' => 'left',
                'index' => 'updated_date',
                'type'=>'datetime',
                'renderer'=> 'repricer/adminhtml_competitors_grid_renderer_datetime'
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('competitor_id');
        $this->getMassactionBlock()->setFormFieldName('competitor_id'); 

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => Mage::helper('repricer')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('repricer')->__('Are you sure you want to delete selected competitors?')
            )
        );

        $statuses = Mage::getSingleton('repricer/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label' => Mage::helper('repricer')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('repricer')->__('Status'),
                        'values' => $statuses
                    )
                )
            )
        );

        // Mage::dispatchEvent('banner_adminhtml_banner_grid_prepare_massaction', array('block' => $this));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('competitor_id' => $row->getId()));
    }
}
