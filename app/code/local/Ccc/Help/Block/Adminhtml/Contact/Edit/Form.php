<?php
class Ccc_Help_Block_Adminhtml_Contact_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_form');
        $this->setTitle(Mage::helper('help')->__('Contact Information'));
    }

    // protected function _prepareLayout()
    // {
    //     parent::_prepareLayout();
    //     return $this;
    // }
    protected function _prepareForm()
    {
        $model = Mage::registry('contact');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('help')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getId()) {
            $fieldset->addField('contact_id', 'hidden', array(
                'name' => 'contact_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('help')->__('Contact Name'),
            'title' => Mage::helper('help')->__('Contact Name'),
            'required' => true,
        ));

        $fieldset->addField('number', 'text', array(
            'name' => 'number',
            'label' => Mage::helper('help')->__('Number'),
            'title' => Mage::helper('help')->__('Number'),
            'required' => true,
        ));
        
        $fieldset->addField('city', 'editor', array(
            'name' => 'city',
            'label' => Mage::helper('help')->__('City'),
            'title' => Mage::helper('help')->__('City'),
            'required' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('help')->__('Status'),
            'title' => Mage::helper('help')->__('Status'),
            'name' => 'status',
            'required' => true,
            'options' => Mage::getModel('help/contact')->getStatusOptionArray(),
        ));
        if (!$model->getContactId()) {
            $model->setData('status', '2');
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}