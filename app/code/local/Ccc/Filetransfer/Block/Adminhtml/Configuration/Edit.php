<?php
class Ccc_Filetransfer_Block_Adminhtml_Configuration_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_configuration';
        $this->_blockGroup = 'filetransfer';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('filetransfer')->__('Save Configuration'));
        $this->_updateButton('delete', 'label', Mage::helper('filetransfer')->__('Delete Configuration'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('edit_form') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'promotions_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'promotions_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('config')->getConfigId()) {
            return Mage::helper('filetransfer')->__('Edit Configuration of %s', $this->escapeHtml(Mage::registry('config')->getUserId()));
        } else {
            return Mage::helper('filetransfer')->__('New Configuration');
        }
    } 
}
