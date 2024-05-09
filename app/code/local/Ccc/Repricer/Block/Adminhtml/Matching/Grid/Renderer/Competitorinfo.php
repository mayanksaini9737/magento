<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Renderer_Competitorinfo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected static $rowCounter = 0;
    public function render(Varien_Object $row)
    {
        $productId = $row->getData('product_id');
        $items = Mage::getModel('repricer/matching')
            ->getCollection()->addFieldToFilter('product_id', $productId);

        $items->getSelect()
            ->join(
                array('cpev' => Mage::getSingleton('core/resource')->getTableName('repricer/competitors')),
                'cpev.competitor_id = main_table.competitor_id',
                ['cpev.name AS competitor_name']
            );

        $this->getFilter($items);

        $reason = Mage::getModel('repricer/matching')->getReasonOptionArray();
        $columnIndex = $this->getColumn()->getIndex();

        $output = "<table style='border: 0;'>";

        foreach ($items as $item) {
            $rowId = 'row_' . self::$rowCounter;
            self::$rowCounter++;
            $productId = $item->getProductId();
            $_pc = $productId . '-' . $item->getCompetitorId();
            $item->addData(['pc_comb' => $_pc]);
            $output .= "<tr id='$rowId' height='23vh'>";

            switch ($columnIndex) {
                case 'competitor_name':
                    $output .= "<td width='150px'>";
                    $output .= "<input type='checkbox' name='massaction' class='competitor-checkbox' value='{$_pc}' />&nbsp";
                    $output .= $item->getCompetitorName();
                    $output .= "</td>";
                    break;

                case 'competitor_url':
                    $itemId = $item->getId();
                    $output .= "<td class='editable-{$itemId} competitor-url' data-field='competitor_url' width='150px'>";
                    $output .= $item->getCompetitorUrl();
                    $output .= "</td>";
                    break;

                case 'competitor_sku':
                    $itemId = $item->getId();
                    $output .= "<td class='editable-{$itemId}  competitor-sku' data-field='competitor_sku' width='150px'>";
                    $output .= $item->getCompetitorSku();
                    $output .= "</td>";
                    break;

                case 'competitor_price':
                    $itemId = $item->getId();
                    $output .= "<td class='editable-{$itemId}  competitor-price' data-field='competitor_price' width='150px'>";
                    $output .= $item->getCompetitorPrice();
                    $output .= "</td>";
                    break;

                case 'reason':
                    $itemId = $item->getId();
                    $output .= "<td class='editable-{$itemId}  reason' data-field='reason' width='10px' >";
                    $output .= $reason[$item->getReason()];
                    $output .= "</td>";
                    break;

                case 'updated_date':
                    $output .= "<td width='150px'>";
                    $output .= $item->getUpdatedDate();
                    $output .= "</td>";
                    break;

                case 'edit':
                    $formkey = Mage::getSingleton('core/session')->getFormKey();
                    $itemId = $item->getId();
                    $reasonJson = json_encode($reason);
                    $editUrl = $this->getUrl('*/*/edit', array('repricer_id' => $itemId));
                    $output .= "<td width='50px' class='editable' data-field='edit_link'>";
                    $output .= "<a href='#' class='edit-row' data-url='{$editUrl}' data-repricer-id='{$itemId}' data-reason='{$reasonJson}' data-form-key='{$formkey}'>Edit</a>";
                    $output .= "</td>";
                    break;
            }
            $output .= "</tr>";
        }

        $output .= "</table>";
        return $output;
    }

    public function getFilter($items)
    {
        $request = $this->getColumn()->getGrid()->getRequest();
        $filterEncoded = $request->getParam('filter');
        $filterDecoded = base64_decode($filterEncoded);
        parse_str($filterDecoded, $filterArray);    

        foreach ($filterArray as $key => $value) {
            switch ($key) {
                case 'competitor_name':
                    $items->getSelect()->where("main_table.competitor_id LIKE ?", "%{$value}%");
                    break;
                case 'competitor_url':
                    $items->getSelect()->where("main_table.competitor_url LIKE ?", "%{$value}%");
                    break;
                case 'competitor_sku':
                    $items->getSelect()->where("main_table.competitor_sku LIKE ?", "%{$value}%");
                    break;
                case 'competitor_price':
                    $from = isset($value['from']) ? $value['from'] : 0;
                    $to = isset($value['to']) ? $value['to'] : null;
                    if (!empty($from)) {
                        $items->getSelect()->where("main_table.competitor_price >= ?", $from);
                    }
                    if (!empty($to)) {
                        $items->getSelect()->where("main_table.competitor_price <= ?", $to);
                    }
                    break;
                case 'updated_date':
                    if (isset($value['from'])) {
                        $value['from'] = date('Y-m-d 00:00:00', strtotime($value['from']));
                        $items->addFieldToFilter('main_table.updated_date', array('from' => $value['from'], 'datetime' => true));
                    }
                    if (isset($value['to'])) {
                        $value['to'] = date('Y-m-d 23:59:59', strtotime($value['to']));
                        $items->addFieldToFilter('main_table.updated_date', array('to' => $value['to'], 'datetime' => true));
                    }
                    // echo $items->getSelect();
                    // die;
                    break;
                case 'reason':
                    $items->getSelect()->where("main_table.reason LIKE ?", "{$value}");
                    break;
            }
        }
    }

}