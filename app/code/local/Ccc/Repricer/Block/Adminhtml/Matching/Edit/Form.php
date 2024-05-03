<?php 
class Ccc_Repricer_Block_Adminhtml_Matching_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('repricer')->__('Repricer Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    protected function _prepareForm()
    {
        $model = Mage::registry('repricer');
        $productId = $model->getProductId();
        $competitorId = $model->getCompetitorId();

        $catalogProduct = Mage::getModel('catalog/product')->load($productId);
        $competitor = Mage::getModel('repricer/competitors')->load($competitorId);
        
        $productName = $catalogProduct->getName(); 
        $competitorName = $competitor->getName(); 


        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('repricer_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('repricer')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getRepricerId()) {
            $fieldset->addField('repricer_id', 'hidden', array(
                'name' => 'repricer_id',
            ));
        }

        $fieldset->addField('product_id', 'hidden', array(
            'name' => 'product_id',
            'label' => Mage::helper('repricer')->__('Product Id'),
            'title' => Mage::helper('repricer')->__('Product Id'),
            'required' => true,
        ));

        $fieldset->addField('product_name', 'text', array(
            'name' => 'product_name',
            'label' => Mage::helper('repricer')->__('Product Name'),
            'title' => Mage::helper('repricer')->__('Product Name'),
            'readonly'=> true,
            // 'required' => true,
        ));

        $fieldset->addField('competitor_id', 'hidden', array(
            'name' => 'competitor_id',
            'label' => Mage::helper('repricer')->__('Competitor Id'),
            'title' => Mage::helper('repricer')->__('Competitor Id'),
            'required' => true,
        ));

        $fieldset->addField('competitor_name', 'text', array(
            'name' => 'competitor_name',
            'label' => Mage::helper('repricer')->__('Competitor Name'),
            'title' => Mage::helper('repricer')->__('Competitor Name'),
            'readonly'=> true,
            // 'required' => true,
        ));

        $fieldset->addField('competitor_url', 'text', array(
            'name' => 'competitor_url',
            'label' => Mage::helper('repricer')->__('Competitor Url'),
            'title' => Mage::helper('repricer')->__('Competitor Url'),
            // 'required' => true,
        ));
        
        $fieldset->addField('competitor_sku', 'text', array(
            'name' => 'competitor_sku',
            'label' => Mage::helper('repricer')->__('Competitor Sku'),
            'title' => Mage::helper('repricer')->__('Competitor Sku'),
            // 'required' => true,
        ));
        
        $fieldset->addField('competitor_price', 'text', array(
            'name' => 'competitor_price',
            'label' => Mage::helper('repricer')->__('Competitor Price'),
            'title' => Mage::helper('repricer')->__('Competitor Price'),
            // 'required' => true,
        ));

        $fieldset->addField('reason', 'select', array(
            'label' => Mage::helper('repricer')->__('Reason'),
            'title' => Mage::helper('repricer')->__('Reason'),
            'name' => 'reason',
            // 'required' => true,
            'disabled' => true,
            'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
        ));
        
        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $form->setValues($model->getData());
        $form->addValues(['product_name' => $productName, 'competitor_name'=> $competitorName]);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}