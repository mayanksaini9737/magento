<?php 
class Ccc_SimilarItems_Block_Adminhtml_SimilarItems_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_form');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('similaritem');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', 
            array('legend' => Mage::helper('similarItems')->__('General Information'), 
            'class' => 'fieldset-wide'));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('main_product_id', 'select', array(
            'name' => 'main_product_id',
            'label' => Mage::helper('similarItems')->__('Main Product Sku'),
            'title' => Mage::helper('similarItems')->__('Main Product Sku'),
            'required' => true,
            'options' => Mage::getModel('similarItems/similarItems')->getProductArray()
        ));

        $fieldset->addField('similar_product_id', 'select', array(
            'name' => 'similar_product_id',
            'label' => Mage::helper('similarItems')->__('Similar Product Sku'),
            'title' => Mage::helper('similarItems')->__('Similar Product Sku'),
            'required' => true,
            'options' => Mage::getModel('similarItems/similarItems')->getProductArray()
        ));

        if (!$model->getId()) {
            $fieldset->addField('created_at', 'hidden', array(
                'name' => 'created_at',
                'label' => Mage::helper('similarItems')->__('Created At'),
                'title' => Mage::helper('similarItems')->__('Created At'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ));
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}