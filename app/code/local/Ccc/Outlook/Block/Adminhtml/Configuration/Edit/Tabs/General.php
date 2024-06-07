<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('configuration');

        $form = new Varien_Data_Form();
        
        $fieldset = $form->addFieldset('general_form', 
            array('legend' => Mage::helper('outlook')->__('General Information'), 'class' => 'fieldset-wide')
        );

        if ($model->getConfigId()) {
            $fieldset->addField('config_id', 'hidden', array(
                'name' => 'config[config_id]',
            ));
        }

        $fieldset->addField('client_id', 'text', array(
            'name' => 'config[client_id]',
            'label' => Mage::helper('outlook')->__('Client Id'),
            'title' => Mage::helper('outlook')->__('Client Id'),
            'required' => true,
        ));

        $fieldset->addField('secret_value', 'text', array(
            'name' => 'config[secret_value]',
            'label' => Mage::helper('outlook')->__('Client Secret Value'),
            'title' => Mage::helper('outlook')->__('Client Secret Value'),
            'required' => true,
        ));
        
        $fieldset->addField('access_token', 'text', array(
            'name' => 'config[access_token]',
            'label' => Mage::helper('outlook')->__('Access Token'),
            'title' => Mage::helper('outlook')->__('Access Token'),
        ));

        $fieldset->addField('is_active', 'select', array(
            'name' => 'config[is_active]',
            'label' => Mage::helper('outlook')->__('Is Active'),
            'title' => Mage::helper('outlook')->__('Is Active'),
            'options' => Mage::getModel('outlook/configuration')->getIsActiveArray(),
            'required' => true,
        ));

        $configId = null;
        $clientId = null;
        if(!empty($model->getClientId())){
            $configId = $model->getConfigId();
            $clientId = $model->getClientId();
        }
        
        $authorizationUrl = Mage::getModel('outlook/api')->getAuthorizationUrl($clientId, $configId);
        
        $fieldset->addField(
            'outlook_login',
            'note',
            array(
                'text' => $this->getButtonHtml(
                    Mage::helper('outlook')->__('Login'),
                    "window.location.href='{$authorizationUrl}'",
                    'ms-login-button',
                    'outlook_login'
                )
            )
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getButtonHtml($label, $onclick, $class = '', $id = '')
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => $label,
                'onclick' => $onclick,
                'class' => $class,
                'id' => $id
            ));
        return $button->toHtml();
    }
}
