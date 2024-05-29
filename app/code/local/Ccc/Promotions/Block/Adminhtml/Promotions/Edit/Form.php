<?php 
class Ccc_Promotions_Block_Adminhtml_Promotions_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_form');
        $this->setTitle(Mage::helper('promotions')->__('Promotion Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('promotions');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('promotions')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('tag_name', 'text', array(
            'name' => 'tag_name',
            'label' => Mage::helper('promotions')->__('Tag Name'),
            'title' => Mage::helper('promotions')->__('Tag Name'),
            'required' => true,
        ));

        $fieldset->addField('percentage', 'text', array(
            'name' => 'percentage',
            'label' => Mage::helper('promotions')->__('Percentage'),
            'title' => Mage::helper('promotions')->__('Percentage'),
            'required' => true,
        ));

        $fieldset->addField('priority', 'text', array(
            'name' => 'priority',
            'label' => Mage::helper('promotions')->__('Priority'),
            'title' => Mage::helper('promotions')->__('Priority'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('promotions')->__('Active'),
            'title' => Mage::helper('promotions')->__('Active'),
            'name' => 'is_active',
            'required' => true,
            'options' => Mage::getModel('promotions/promotions')->getStatusArray(),
        ));

        if (!$model->getId()) {
            $fieldset->addField('created_at', 'hidden', array(
                'name' => 'created_at',
                'label' => Mage::helper('promotions')->__('Created At'),
                'title' => Mage::helper('promotions')->__('Created At'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ));
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}