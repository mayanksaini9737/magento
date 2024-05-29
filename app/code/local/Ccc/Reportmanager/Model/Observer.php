<?php

class Ccc_Reportmanager_Model_Observer
{
    public function orderPlacedAfter(Varien_Event_Observer $observer)
    {
        Mage::log('Observer triggered', null, 'orderplaced.log');
        $order = $observer->getEvent()->getOrder();

        foreach ($order->getAllItems() as $item) {
            $productId = $item->getProductId();
            $quantityOrdered = $item->getQtyOrdered();

            $product = Mage::getModel('catalog/product')->load($productId);

            $currentSoldCount = $product->getSoldCount();

            if (!$currentSoldCount) {
                $currentSoldCount = 0;
            } else {
                $currentSoldCount = (int) $currentSoldCount;
            }

            $newSoldCount = $currentSoldCount + $quantityOrdered;

            $product->setData('sold_count', $newSoldCount);
            try {
                $product->getResource()->saveAttribute($product, 'sold_count');
            } catch (Exception $e) {
                Mage::log($e->getMessage(), null, 'soldcount.log');
                Mage::logException($e);
            }
        }
    }

    public function afterUserLogin(Varien_Event_Observer $observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $userId = $user->getId();

        $reportManagerModel = Mage::getModel('reportmanager/reportmanager');
        $collection = $reportManagerModel
            ->getCollection()
            ->addFieldToFilter('user_id', $userId)
            ->addFieldToFilter('report_type', 1)
            ->getFirstItem();

        $filterData = $collection->getFilterData();

        Mage::getSingleton('core/session')->setSavedFilters($filterData);
    }
}