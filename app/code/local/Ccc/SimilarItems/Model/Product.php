<?php

class Ccc_SimilarItems_Model_Product extends Mage_Catalog_Model_Product
{
    protected function _beforeSave()
    {
        $this->cleanCache();
        $this->setTypeHasOptions(false);
        $this->setTypeHasRequiredOptions(false);

        $this->getTypeInstance(true)->beforeSave($this);

        $hasOptions = false;
        $hasRequiredOptions = false;

        $this->canAffectOptions($this->_canAffectOptions && $this->getCanSaveCustomOptions());
        if ($this->getCanSaveCustomOptions()) {
            $options = $this->getProductOptions();
            if (is_array($options)) {
                $this->setIsCustomOptionChanged(true);
                foreach ($this->getProductOptions() as $option) {
                    $this->getOptionInstance()->addOption($option);
                    if ((!isset($option['is_delete'])) || $option['is_delete'] != '1') {
                        if (!empty($option['file_extension'])) {
                            $fileExtension = $option['file_extension'];
                            if (0 !== strcmp($fileExtension, Mage::helper('core')->removeTags($fileExtension))) {
                                Mage::throwException(Mage::helper('catalog')->__('Invalid custom option(s).'));
                            }
                        }
                        $hasOptions = true;
                    }
                }
                foreach ($this->getOptionInstance()->getOptions() as $option) {
                    if ($option['is_require'] == '1') {
                        $hasRequiredOptions = true;
                        break;
                    }
                }
            }
        }

        if ($hasOptions || (bool) $this->getTypeHasOptions()) {
            $this->setHasOptions(true);
            if ($hasRequiredOptions || (bool) $this->getTypeHasRequiredOptions()) {
                $this->setRequiredOptions(true);
            } elseif ($this->canAffectOptions()) {
                $this->setRequiredOptions(false);
            }
        } elseif ($this->canAffectOptions()) {
            $this->setHasOptions(false);
            $this->setRequiredOptions(false);
        }

        
        
        $similarItemCollection = Mage::getModel('similarItems/similarItems')->getCollection()
            ->addFieldToFilter('main_product_id', $this->getId())
            ->addFieldToFilter('is_deleted', 2);
        
        
        if($similarItemCollection->getSize()){
            $this->setHasSimilarItem(240);
        }
        
        parent::_beforeSave();
    }
}