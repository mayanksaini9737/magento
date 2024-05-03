<?php
class Ccc_Repricer_Model_Observer
{
    // const TIME_SPAN = 24 * 3600;
    public function repricerMatchingUpdate()
    {
        $tmp = [];
        $data = [];
        $competitorIds = Mage::getModel('repricer/competitors')
            ->getCollection()
            ->getAllIds();

        foreach ($competitorIds as $_competitorId) {

            $collection = Mage::getModel('catalog/product')
                ->getCollection();
            $collection->getSelect()
                ->columns('e.entity_id')
                ->joinLeft(
                    ['CRM' => 'ccc_repricer_matching'],
                    "e.entity_id = CRM.product_id AND CRM.competitor_id = {$_competitorId}",
                    ['competitor_id']
                )
                ->where('CRM.competitor_id IS NULL');

            $columns = [
                'product_id' => 'e.entity_id',
            ];
            $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
                ->columns($columns);
            foreach ($collection->getData() as $_data) {
                $_data['competitor_id'] = $_competitorId;
                $data[] = $_data;
            }
            $tmp[$_competitorId] = $collection->getColumnValues('product_id');
            if (!is_null($tmp[$_competitorId]) && !empty($tmp[$_competitorId])) {
                // print_r($tmp[$_competitorId]);
            }

        }
        // print_r($data);
        // die;
        if (!is_null($data) && !empty($data)) {
            $model = Mage::getSingleton('core/resource')->getConnection('core_write');
            $tableName = Mage::getSingleton('core/resource')->getTableName('repricer/matching');
            $result = $model->insertMultiple($tableName, $data);
        }
        if ($result) {
            echo "$result Rows Inserted successfully.";
        } else {
            echo "There is no new Product Id and Competitor Id...";
        }
    }

    public function downloadCsv()
    {
        $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';
        $files = glob($folderPath . DS . '*_pending.csv');

        $model = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tableName = Mage::getSingleton('core/resource')->getTableName('repricer/matching');

        foreach ($files as $file) {
            if (($handle = fopen($file, 'r')) !== FALSE) {
                $parsedData = [];
                $header = [];

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (empty($header)) {
                        $header = array_map(function ($item) {
                            return str_replace(' ', '_', strtolower($item));
                        }, $data);
                        continue;
                    }

                    $row = array_combine($header, $data);
                    $competitorName = $row['competitor_name'];
                    $competitorId = Mage::getModel('repricer/competitors')->load($competitorName, 'name')->getId();
                    $row['competitor_id'] = $competitorId;

                    unset($row['product_sku'], $row['competitor_name']);
                    $parsedData[] = $row;
                }
                fclose($handle);

                $model->insertOnDuplicate($tableName, $parsedData);

                $filename = basename($file);
                $fileDirectory = dirname($file);
                $newFilePath = $fileDirectory . DS . str_replace('_pending', '_completed', $filename);
                echo 'Repricer updated successfully';
                rename($file, $newFilePath);
            }
        }
    }

    public function uploadCsv()
    {
        $columns = [
            'product_id' => 'product_id',
            'product_sku' => 'CPE.sku',
            'competitor_name' => 'CRC.name',
            'competitor_url' => 'competitor_url',
            'competitor_sku' => 'competitor_sku',
        ];

        $collection = Mage::getModel('repricer/matching')->getCollection()
            ->join(
                array('CRC' => 'repricer/competitors'),
                'main_table.competitor_id = CRC.competitor_id',
                array()
            )
            ->join(
                array('CPE' => 'catalog/product'),
                'main_table.product_id = CPE.entity_id',
                array()
            );

        $collection->getSelect()->order('repricer_id ASC')->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $dataArray = $collection->getData();

        $competitorData = [];
        foreach ($dataArray as $data) {
            $competitorName = $data['competitor_name'];
            $competitorData[$competitorName][] = $data;
        }

        $filePaths = [];
        foreach ($competitorData as $competitorName => $data) {
            $csv = '';
            $headerRow = array_keys($data[0]);
            $csv .= implode(',', $headerRow) . "\n";
            foreach ($data as $index => $row) {
                $csvRow = array_map(function ($value) {
                    return '"' . str_replace('"', '""', $value) . '"';
                }, $row);
                $csv .= implode(',', $csvRow) . "\n";
            }
            $csv = rtrim($csv, "\n");
            $filePath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload' . DS . $competitorName . '_upload_' . time() . '.csv';
            file_put_contents($filePath, $csv);
            $filePaths[] = $filePath;
        }
        return $filePaths;
    }

    protected function oldfunctions()
    {

        // public function saveForNewCompetitor()
        // {
        //     $timestamp = time();
        //     $sqlTimestamp = date('Y-m-d H:i:s', $timestamp);
        //     $pdata = [];
        //     $competitorCollection = (Mage::getModel('repricer/competitors')->getCollection());

        //     foreach ($competitorCollection as $_data) {
        //         $difference = strtotime($sqlTimestamp) - strtotime($_data->getCreatedDate());

        //         if ($difference <= Ccc_Repricer_Model_Observer::TIME_SPAN) {
        //             $productCollection = Mage::getModel('catalog/product')->getCollection()
        //                 ->addAttributeToSelect(['name', 'status'])
        //                 ->addAttributeToFilter('status', 1);

        //             foreach ($productCollection as $_product) {
        //                 $pdata[] = [
        //                     'competitor_id' => $_data->getCompetitorId(),
        //                     'product_id' => $_product->getId(),
        //                 ];
        //             }
        //         }
        //     }
        //     if (!empty($pdata)) {
        //         $matchingModel = Mage::getModel('repricer/matching');
        //         foreach ($pdata as $data) {
        //             $matchingModel->setData($data);
        //             $matchingModel->save();
        //         }
        //     } else {
        //         echo "No data to save.";
        //     }
        // }

        // public function saveForNewProduct()
        // {
        //     $timestamp = time();
        //     $sqlTimestamp = date('Y-m-d H:i:s', $timestamp);
        //     $pdata = [];
        //     $productCollection = (Mage::getModel('catalog/product')->getCollection())
        //         ->addAttributeToSelect(['name', 'status'])
        //         ->addAttributeToFilter('status', 1);

        //     foreach ($productCollection as $_product) {
        //         $difference = strtotime($sqlTimestamp) - strtotime($_product->getCreatedAt());
        //         if ($difference <= Ccc_Repricer_Model_Observer::TIME_SPAN) {
        //             $competitorCollection = Mage::getModel('repricer/competitors')->getCollection();
        //             foreach ($competitorCollection as $_data) {
        //                 $timediff = strtotime($sqlTimestamp) - strtotime($_data->getCreatedDate());
        //                 if ($timediff >= Ccc_Repricer_Model_Observer::TIME_SPAN) {
        //                     $pdata[] = [
        //                         'competitor_id' => $_data->getCompetitorId(),
        //                         'product_id' => $_product->getId(),
        //                         'competitor_url' => $_data->getUrl(),
        //                     ];
        //                 }
        //             }
        //         }
        //     }
        //     if (!empty($pdata)) {
        //         $matchingModel = Mage::getModel('repricer/matching');
        //         foreach ($pdata as $data) {
        //             $matchingModel->setData($data);
        //             $matchingModel->save();
        //         }
        //     } else {
        //         echo "No data to save.";
        //     }
        // }

        // public function downloadCsv()
        // {
        //     $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';

        //     $files = glob($folderPath . DS . '*_pending.csv');

        //     $matchingModel = Mage::getModel('repricer/matching');

        //     foreach ($files as $file) {

        //         $parsedData = $this->_parseCsv($file);
        //         $competitorName = $parsedData[0]['competitor_name'];
        //         $competitorId = Mage::getModel('repricer/competitors')
        //             ->load($competitorName, 'name')->getId();

        //         $productIds = [];

        //         foreach ($parsedData as $row) {
        //             $row['competitor_id'] = $competitorId;
        //             $productIds[] = $row['product_id'];
        //         }

        //         $collection = $matchingModel->getCollection()
        //             ->addFieldToFilter('product_id', array('in' => $productIds))
        //             ->addFieldToFilter('competitor_id', $competitorId);

        //         $productIdToRepricerIdMap = array();

        //         foreach ($collection as $item) {
        //             $productIdToRepricerIdMap[$item->getProductId()] = $item->getRepricerId();
        //         }

        //         foreach ($parsedData as &$singleRow) {
        //             $singleRow['repricer_id'] = $productIdToRepricerIdMap[$singleRow['product_id']];
        //         }

        //         $saveData = [];

        //         foreach ($parsedData as $data) {
        //             unset($data['product_sku']);
        //             unset($data['competitor_name']);
        //             $saveData[] = $data;
        //         }

        //         $model = Mage::getSingleton('core/resource')->getConnection('core_write');
        //         $tableName = Mage::getSingleton('core/resource')->getTableName('repricer/matching');

        //         $model->insertOnDuplicate($tableName, $saveData);

        //         $filename = basename($file);
        //         $fileDirectory = dirname($file);
        //         $newFilePath = $fileDirectory . DS . str_replace('_pending', '_completed', $filename);
        //         echo 'repricer updated succesfully';
        //         rename($file, $newFilePath);
        //     }
        // }


        // protected function _parseCsv($csvFile)
        // {
        //     $row = 0;
        //     $parsedData = [];
        //     $header = [];

        //     if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        //         while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        //             if (!$row) {
        //                 foreach ($data as &$item) {
        //                     $item = str_replace(' ', '_', strtolower($item));
        //                 }
        //                 $header = $data;
        //                 $row++;
        //                 continue;
        //             }

        //             $parsedData[] = array_combine($header, $data);
        //         }
        //         fclose($handle);
        //     }
        //     return $parsedData;
        // }


        // public function uploadCsv()
        // {
        //     $columns = [
        //         'product_id' => 'ev.entity_id',
        //         'product_sku' => 'ev.sku',
        //         'competitor_name' => 'cp.name',
        //         'competitor_url' => 'competitor_url',
        //         'competitor_sku' => 'competitor_sku',
        //     ];

        //     $competitorCollection = Mage::getModel('repricer/competitors')->getCollection()
        //         ->addFieldToSelect(['name']);

        //     $competitorNames = [];
        //     foreach ($competitorCollection as $competitor) {
        //         $competitorNames[] = $competitor->getName();
        //     }

        //     foreach ($competitorNames as $competitor) {

        //         $matchingCollection = Mage::getModel('repricer/matching')->getCollection();
        //         $matchingCollection->getSelect()
        //             ->join(
        //                 array('cp' => Mage::getSingleton('core/resource')
        //                     ->getTableName('repricer/competitors')),
        //                 'main_table.competitor_id = cp.competitor_id',
        //                 ['']
        //             )
        //             ->join(
        //                 array('ev' => Mage::getSingleton('core/resource')
        //                     ->getTableName('catalog/product')),
        //                 'ev.entity_id = main_table.product_id ',
        //                 ['']
        //             )
        //             ->reset(Zend_Db_Select::COLUMNS)
        //             ->columns($columns);

        //         $competitorData = $matchingCollection->addFieldToFilter('name', $competitor)->getData();

        //         $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload';
        //         mkdir($folderPath, 0777, true);

        //         $csvFilePath = $folderPath . DS . $competitor . '_upload' . '.csv';

        //         $csvFile = fopen($csvFilePath, 'w');

        //         $header = array_keys($competitorData[0]);
        //         fputcsv($csvFile, $header);

        //         foreach ($competitorData as $row) {
        //             fputcsv($csvFile, $row);
        //         }
        //         fclose($csvFile);
        //     }
        //     return $csvFilePath;
        // }




    }






}