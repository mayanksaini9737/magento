<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('repricerGrid');
        $this->setDefaultSort('repricer_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('repricer/matching')->getCollection();

        $collection->getSelect()
            ->join(
                array('cpev' => Mage::getSingleton('core/resource')->getTableName('repricer/competitors')),
                'main_table.competitor_id = cpev.competitor_id'
            )
            ->join(
                array('ev' => 'catalog_product_entity_varchar'),
                'ev.entity_id = main_table.product_id AND ev.attribute_id = 71 AND ev.store_id = 0'
            )
            ->join(
                array('CP' => Mage::getModel('core/resource')->getTableName('catalog/product')),
                'CP.entity_id = main_table.product_id'
            );

        $columns = [
            'product_id' => 'CP.entity_id',
            'product_name' => 'ev.value',
            'product_sku' => 'CP.sku',
            'competitor_id' => 'cpev.competitor_id',
            'competitor_name' => 'cpev.name',
            'repricer_id' => 'main_table.repricer_id',
            'competitor_url' => 'main_table.competitor_url',
            'competitor_sku' => 'main_table.competitor_sku',
            'competitor_price' => 'main_table.competitor_price',
            'reason' => 'main_table.reason',
            'updated_date' => 'main_table.updated_date',
            'pc_comb' => 'GROUP_CONCAT(CONCAT(main_table.product_id, "-", main_table.competitor_id) SEPARATOR ",")'
        ];

        $select = $collection->getSelect();
        $select
            ->group('main_table.product_id')
            ->order('CP.entity_id ASC')
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        // echo $select;
        // die;

        // print_r($collection->getSize());
        // print_r(count($collection->getData()));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'product_name',
            array(
                'header' => Mage::helper('repricer')->__('Product Info'),
                'align' => 'left',
                'index' => 'product_name',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_productinfo',
            )
        );

        $this->addColumn(
            'competitor_name',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Name'),
                'index' => 'competitor_name',
                'type' => 'options',
                'options' => Mage::getModel('repricer/competitors')->getCompetitors(),
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
            )
        );

        $this->addColumn(
            'competitor_url',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Url'),
                'align' => 'left',
                'index' => 'competitor_url',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
            )
        );

        $this->addColumn(
            'competitor_sku',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Sku'),
                'align' => 'left',
                'index' => 'competitor_sku',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
            )
        );

        $this->addColumn(
            'competitor_price',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Price'),
                'type' => 'number',
                'align' => 'left',
                'index' => 'competitor_price',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
            )
        );

        $this->addColumn(
            'reason',
            array(
                'header' => Mage::helper('repricer')->__('Reason'),
                'index' => 'reason',
                'type' => 'options',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
                'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
            )
        );

        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('repricer')->__('Updated Date'),
                'align' => 'left',
                'index' => 'updated_date',
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
                'type' => 'datetime',
            )
        );

        $this->addColumn(
            'edit',
            array(
                'header' => Mage::helper('repricer')->__('Edit'),
                'align' => 'left',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('repricer')->__('Edit'),
                        'url' => array(
                            'base' => '*/*/edit',
                        ),
                        'field' => 'repricer_id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'edit',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitorinfo',
            )
        );
        return parent::_prepareColumns();
    }


    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $fieldName = $column->getFilterIndex() ?: $column->getIndex();

        switch ($fieldName) {
            case 'product_name':
                $this->_filterProductNameOrSkuCondition($collection, $column, $value);
                break;
            case 'competitor_name':
                $this->_filterCompetitorNameCondition($collection, $column, $value);
                break;
            case 'competitor_url':
                $this->_filterCompetitorUrlCondition($collection, $column, $value);
                break;
            case 'competitor_sku':
                $this->_filterCompetitorSkuCondition($collection, $column, $value);
                break;
            case 'competitor_price':
                $this->_filterCompetitorPriceCondition($collection, $column, $value);
                break;
            case 'updated_date':
                $this->_filterUpdatedDateCondition($collection, $column, $value);
                break;
        }
    }

    protected function _filterProductNameOrSkuCondition($collection, $column, $value)
    {
        if (!$value) {
            return;
        }
        $collection->getSelect()->where(
            "ev.value LIKE '%$value%' OR CP.sku LIKE '%$value%'"
        );
    }

    protected function _filterCompetitorNameCondition($collection, $column, $value)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->getSelect()->where(
            "main_table.competitor_id LIKE '%$value%'"
        );
    }
    protected function _filterCompetitorUrlCondition($collection, $column, $value)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->getSelect()->where(
            "competitor_url LIKE '%$value%'"
        );
    }
    protected function _filterCompetitorSkuCondition($collection, $column, $value)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->getSelect()->where(
            "competitor_sku LIKE '%$value%'"
        );
    }

    protected function _filterCompetitorPriceCondition($collection, $column, $value)
    {
        if (!$value['from'] && !$value['to']) {
            return;
        }
        $fromPrice = !empty($value['from']) ? (float) $value['from'] : 1;
        $toPrice = !empty($value['to']) ? (float) $value['to'] : null;
        if ($fromPrice && $toPrice) {
            $collection->getSelect()->where("competitor_price BETWEEN $fromPrice AND $toPrice");
        } elseif ($fromPrice) {
            $collection->getSelect()->where("competitor_price >= $fromPrice");
        } elseif ($toPrice) {
            $collection->getSelect()->where("competitor_price <= $toPrice");
        }
    }

    protected function _filterUpdatedDateCondition($collection, $column, $value)
    {
        if (isset($value['from'])) {
            $value['from'] = date('Y-m-d 00:00:00', strtotime($value['from']));
            $collection->addFieldToFilter('main_table.updated_date', array('from' => $value['from'], 'datetime' => true));
        }
        if (isset($value['to'])) {
            $value['to'] = date('Y-m-d 23:59:59', strtotime($value['to']));
            $collection->addFieldToFilter('main_table.updated_date', array('to' => $value['to'], 'datetime' => true));
        }
    }

    

    // public function getRowUrl($row)
    // {
    //     return $this->getUrl('*/*/edit', array('repricer_id' => $row->getId()));
    // }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('pc_comb');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('pc_comb'); 
        $reasonArr = Mage::getModel('repricer/matching')->getReasonOptionArray();

        array_unshift($reasonArr, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'reason',
            array(
                'label' => Mage::helper('repricer')->__('Change Reason'),
                'url' => $this->getUrl('*/*/massReason', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'reason',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('repricer')->__('Reason'),
                        'values' => $reasonArr
                    )
                )
            )
        );
        return $this;
    }

}
