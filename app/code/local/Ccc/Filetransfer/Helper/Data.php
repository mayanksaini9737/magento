<?php 

class Ccc_Filetransfer_Helper_Data extends  Mage_Core_Helper_Abstract
{
    protected $_rows = [
        'partnumber'=>'items.item.itemIdentification.itemIdentifier:itemNumber',
        'depth'=>'items.item.itemIdentification.itemCharacteristics.itemDimensions.depth:value',
        'height'=>'items.item.itemIdentification.itemCharacteristics.itemDimensions.height:value',
        'length'=>'items.item.itemIdentification.itemCharacteristics.itemDimensions.length:value',
        'weight'=>'items.item.itemIdentification.itemCharacteristics.itemDimensions.weight:value'
    ];

    public function getRows()
    {
        $rows = [];
        foreach ($this->_rows as $key => $row) {
            $parts = explode(':', $row);
            $rows[$key] = ['path' => $parts[0], 'attribute' => $parts[1]];
        }
        return $rows;
    }

}