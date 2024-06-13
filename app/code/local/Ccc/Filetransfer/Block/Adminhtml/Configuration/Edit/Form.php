<?php 
class Ccc_Filetransfer_Block_Adminhtml_Configuration_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_form');
        $this->setTitle(Mage::helper('filetransfer')->__('Configuration Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('config');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('filetransfer')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getConfigId()) {
            $fieldset->addField('config_id', 'hidden', array(
                'name' => 'config_id',
            ));
        }

        $fieldset->addField('user_id', 'text', array(
            'name' => 'user_id',
            'label' => Mage::helper('filetransfer')->__('User Id'),
            'title' => Mage::helper('filetransfer')->__('User Id'),
            'required' => true,
        ));

        $fieldset->addField('password', 'text', array(
            'name' => 'password',
            'label' => Mage::helper('filetransfer')->__('Password'),
            'title' => Mage::helper('filetransfer')->__('Password'),
            'required' => true,
        ));

        $fieldset->addField('host', 'text', array(
            'name' => 'host',
            'label' => Mage::helper('filetransfer')->__('Host'),
            'title' => Mage::helper('filetransfer')->__('Host'),
            'required' => true,
        ));

        $fieldset->addField('port', 'text', array(
            'name' => 'port',
            'label' => Mage::helper('filetransfer')->__('Port'),
            'title' => Mage::helper('filetransfer')->__('Port'),
            'required' => true,
        ));
        
        $fieldset->addField('remote_path', 'text', array(
            'name' => 'remote_path',
            'label' => Mage::helper('filetransfer')->__('Remote Path'),
            'title' => Mage::helper('filetransfer')->__('Remote Path'),
            // 'required' => true,
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}