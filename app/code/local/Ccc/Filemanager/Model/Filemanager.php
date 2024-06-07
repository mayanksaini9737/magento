<?php

class Ccc_Filemanager_Model_Filemanager extends Varien_Data_Collection_Filesystem
{
    protected function _construct()
    {
        parent::_construct();
    }

    protected function _generateRow($filename)
    {
        $relativePath = str_replace($this->_targetDirs, '', dirname($filename));
        $relativePath = $relativePath === '' ? '/' : $relativePath . '/';
        // print_r(pathinfo($filename));
        return array(
            'folder_path' => $relativePath,
            'dirname' => pathinfo($filename)['dirname'],
            'fullpath' => $filename,
            'basename' => pathinfo($filename)['basename'],
            'name' => $filename,
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'creation_date' => date('Y-m-d H:i:s', filectime($filename)),
            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
        );
    }
}

// class Ccc_Filemanager_Model_Filemanager extends Varien_Data_Collection_Filesystem
// {
//     protected $_targetDirs;
//     protected $_filters = array();

//     protected function _construct()
//     {
//         parent::_construct();
//     }

//     public function addTargetDir($dir)
//     {
//         $this->_targetDirs = (array) $dir;
//         $this->loadData();
//         return $this;
//     }

//     public function addFieldToFilter($field, $condition)
//     {
//         Mage::log("Filtering by field: {$field} with condition: " . print_r($condition, true), null, 'filters.log');
//         $this->_filters[] = array('field' => $field, 'condition' => $condition);
//         return $this;
//     }

//     protected function _generateRow($filename)
//     {
//         $relativePath = str_replace($this->_targetDirs, '', dirname($filename));
//         $relativePath = $relativePath === '' ? '/' : $relativePath . '/';

//         return array(
//             'folder_path' => $relativePath,
//             'dirpath' => dirname($filename),
//             'fullpath' => $filename,
//             'basename' => pathinfo($filename)['basename'],
//             'name' => $filename,
//             'filename' => pathinfo($filename, PATHINFO_FILENAME),
//             'creation_date' => date('Y-m-d H:i:s', filectime($filename)),
//             'extension' => pathinfo($filename, PATHINFO_EXTENSION),
//         );
//     }

//     protected function _loadData()
//     {
//         if (empty($this->_targetDirs)) {
//             return $this;
//         }

//         $files = array();
//         foreach ($this->_targetDirs as $dir) {
//             $files = array_merge($files, $this->_collectFiles($dir));
//         }

//         foreach ($files as $file) {
//             $row = $this->_generateRow($file);
//             if ($this->_applyFilters($row)) {
//                 $this->addItem(new Varien_Object($row));
//             }
//         }

//         return $this;
//     }

//     protected function _collectFiles($dir)
//     {
//         $files = array();
//         if (is_dir($dir)) {
//             $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
//             foreach ($iterator as $file) {
//                 if ($file->isFile()) {
//                     $files[] = $file->getPathname();
//                 }
//             }
//         }
//         return $files;
//     }

//     protected function _applyFilters($row)
//     {
//         foreach ($this->_filters as $filter) {
//             $field = $filter['field'];
//             $condition = $filter['condition'];

//             if (isset($row[$field])) {
//                 $value = $row[$field];
//                 if (is_array($condition)) {
//                     if (isset($condition['like'])) {
//                         $likeValue = $condition['like'];
//                         if (stripos($value, $likeValue) === false) {
//                             return false;
//                         }
//                     }
//                     if (isset($condition['eq']) && $value != $condition['eq']) {
//                         return false;
//                     }
//                 } else {
//                     if ($value != $condition) {
//                         return false;
//                     }
//                 }
//             } else {
//                 return false;
//             }
//         }
//         return true;
//     }


// }


