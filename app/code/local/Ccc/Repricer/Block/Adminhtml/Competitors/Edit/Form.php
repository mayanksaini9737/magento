<?php 
class Ccc_Repricer_Block_Adminhtml_Competitors_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('repricer')->__('Competitor Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('competitor');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('repricer_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('repricer')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getCompetitorId()) {
            $fieldset->addField('competitor_id', 'hidden', array(
                'name' => 'competitor_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('repricer')->__('Competitor Name'),
            'title' => Mage::helper('repricer')->__('Competitor Name'),
            'required' => true,
        ));

        $fieldset->addField('url', 'text', array(
            'name' => 'url',
            'label' => Mage::helper('repricer')->__('Competitor Url'),
            'title' => Mage::helper('repricer')->__('Competitor Url'),
            'required' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('repricer')->__('Status'),
            'title' => Mage::helper('repricer')->__('Status'),
            'name' => 'status',
            'required' => true,
            'options' => Mage::getModel('repricer/status')->getOptionArray(),
        ));
        if (!$model->getId()) {
            $model->setData('status', Mage::getModel('repricer/status')::STATUS_DISABLED);
        }

        $fieldset->addField('filename', 'text', array(
            'name' => 'filename',
            'label' => Mage::helper('repricer')->__('Filename'),
            'title' => Mage::helper('repricer')->__('Filename'),
            // 'required' => true,
        ));

        if (!$model->getId()) {
            $fieldset->addField('created_date', 'hidden', array(
                'name' => 'created_date',
                'label' => Mage::helper('repricer')->__('Created Date'),
                'title' => Mage::helper('repricer')->__('Created Date'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ));
        }


        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}