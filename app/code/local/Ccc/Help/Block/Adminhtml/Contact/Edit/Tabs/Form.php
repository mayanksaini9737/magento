<?php

class Ccc_Help_Block_Adminhtml_Contact_Edit_Tabs_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('contact');

        $form = new Varien_Data_Form();
        
        $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('help')->__('General Information'), 'class' => 'fieldset-wide'));

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
        
        $fieldset->addField('city', 'text', array(
            'name' => 'city',
            'label' => Mage::helper('help')->__('City'),
            'title' => Mage::helper('help')->__('City'),
            'required' => true,
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
