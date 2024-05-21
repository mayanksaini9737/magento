<?php

class Ccc_Help_Block_Adminhtml_Contact_Edit_Tabs_SecondTab extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('contact');

        $form = new Varien_Data_Form();
        
        $fieldset = $form->addFieldset('status_form', array('legend' => Mage::helper('help')->__('Status Information'), 'class' => 'fieldset-wide'));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('help')->__('Status'),
            'title' => Mage::helper('help')->__('Status'),
            'name' => 'status',
            'required' => true,
            'values' => Mage::getModel('help/contact')->getStatusOptionArray(),
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
