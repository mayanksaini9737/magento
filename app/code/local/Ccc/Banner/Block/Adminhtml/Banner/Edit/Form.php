<?php
class Ccc_Banner_Block_Adminhtml_Banner_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('banner')->__('Banner Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('banner');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('banner_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('banner')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getBannerId()) {
            $fieldset->addField('banner_id', 'hidden', array(
                'name' => 'banner_id',
            ));
        }

        $fieldset->addField('banner_image', 'image', array(
            'name' => 'banner_image',
            'label' => Mage::helper('banner')->__('Banner Image'),
            'title' => Mage::helper('banner')->__('Banner Image'),
            'required' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('banner')->__('Status'),
            'title' => Mage::helper('banner')->__('Status'),
            'name' => 'status',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('banner')->__('Enabled'),
                '2' => Mage::helper('banner')->__('Disabled'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $fieldset->addField('show_on', 'editor', array(
            'name' => 'show_on',
            'label' => Mage::helper('banner')->__('Show On'),
            'title' => Mage::helper('banner')->__('Show On'),
            'required' => true,
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}