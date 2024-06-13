<?php

class Ccc_Filetransfer_Model_Ftp extends Varien_Io_Ftp
{
    protected $_config = null;

    public function setConfigObj($obj)
    {
        $this->_config = $obj;
        return $this;
    }

    // public function getAllFile()
    // {
    //     $configId = $this->_config->getConfigId();
    //     $host = $this->_config->getHost();
    //     $userId = $this->_config->getUserId();
    //     $password = $this->_config->getPassword();
    //     // $remotePath = $this->_config->getRemotePath();


    //     $connection = $this->open(
    //         array(
    //             'host' => $host,
    //             'user' => $userId,
    //             'password' => $password,
    //         )
    //     );

    //     if ($connection) {
    //         // $this->cd($remotePath);
    //         $completeDir = 'completed';
    //         $files = $this->ls();
    //         // echo "<pre>";
    //         // print_r($files);
    //         // die;
    //         $allFiles = []; 
    //         foreach ($files as $file) {
    //             if ($file['text'] !== './completed'){
    //                 $fileName = pathinfo($file['text'], PATHINFO_FILENAME);
    //                 $extension = pathinfo($file['text'], PATHINFO_EXTENSION);
    //                 if (!$extension){
    //                     continue;
    //                 }
    //                 $timestamp = date('Ymd_His');
    //                 $localFilePath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . $fileName . '_' . $timestamp.'.'.$extension;
    //                 if (!file_exists(dirname($localFilePath))) {
    //                     mkdir(dirname($localFilePath), 0755, true);
    //                 }
    //                 $fileName =  basename($file['text']);
    //                 $remoteFilePath = '/' . $file['text']; 
    //                 $modifiedTime = ftp_mdtm($this->_conn, $remoteFilePath); 

    //                 if ($modifiedTime != -1) {
    //                     $modifiedDate = date('Y-m-d H:i:s', $modifiedTime);
    //                 } else {
    //                     $modifiedDate = 'Unknown'; 
    //                 }
    //                 $this->read($file['text'], $localFilePath);
    //                 $this->mv($file['text'], $completeDir . '/' . $file['text']);   


    //                 $allFiles[] = array(
    //                     'filename' => $fileName,
    //                     'modified_date' => $modifiedDate,
    //                     'config_id' => $configId,
    //                     'host' => $host
    //                 );
    //             }
    //         }
    //         $this->close();
    //         return $allFiles;
    //     }
    // }



    public function getAllFile()
    {
        $configId = $this->_config->getConfigId();
        $host = $this->_config->getHost();
        $userId = $this->_config->getUserId();
        $password = $this->_config->getPassword();

        $connection = $this->open(
            array(
                'host' => $host,
                'user' => $userId,
                'password' => $password,
            )
        );

        if ($connection) {
            $completedDir = 'completed';
            $files = ftp_mlsd($this->_conn, '.');
            echo "<pre>";
            // print_r($files);
            // die;

            $allFiles = [];
            $this->processFilesRecursively($files, '.', $completedDir, $allFiles, $configId, $host);

            $this->close();
            return $allFiles;
        }
    }

    private function processFilesRecursively($files, $currentPath, $completedDir, &$allFiles, $configId, $host)
    {
        foreach ($files as $file) {
            if ($file['name'] !== 'completed') {
                $filePath = ltrim($currentPath . '/' . $file['name'], './');
                $fileName = pathinfo($filePath, PATHINFO_FILENAME);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                if ($file['type'] == 'dir') {
                    $localDirPath = Mage::getBaseDir('var') . DS . 'filetransfer' . DS . $filePath;
                    $completedDirPath = $completedDir . '\\' . $filePath;
                    
                    if (!file_exists($localDirPath)) {
                        mkdir($localDirPath, 0755, true);
                    }
                    if (!($this->ftpDirectoryExists($this->_conn, $completedDirPath))) {
                        ftp_mkdir($this->_conn, $completedDirPath);
                    }

                    $subFiles = ftp_mlsd($this->_conn, $filePath);
                    $this->processFilesRecursively($subFiles, $filePath, $completedDir, $allFiles, $configId, $host);
                } else {
                    // It's a file
                    $timestamp = date('Ymd_His');
                    $localDir = Mage::getBaseDir('var') . DS . 'filetransfer';
                    $localFilePath = $localDir . DS . $filePath;
                    $newLocalFilePath = str_replace($file['name'], '', $localFilePath). $fileName . '_' . $timestamp . '.' . $extension;;

                    if (!file_exists(dirname($newLocalFilePath))) {
                        mkdir(dirname($newLocalFilePath), 0755, true);
                    }

                    // $name = basename($filePath);
                    $name = str_replace($localDir, '', $newLocalFilePath);
                    $remoteFilePath = '/' . $filePath;
                    $modifiedTime = ftp_mdtm($this->_conn, $remoteFilePath);

                    if ($modifiedTime != -1) {
                        $modifiedDate = date('Y-m-d H:i:s', $modifiedTime);
                    } else {
                        $modifiedDate = 'Unknown';
                    }
                    $this->read($filePath, $newLocalFilePath);
                    $this->mv($filePath, $completedDir . '/' . $filePath);

                    $allFiles[] = [
                        'filename' => $name,
                        'modified_date' => $modifiedDate,
                        'config_id' => $configId,
                        'host' => $host
                    ];
                }
            }
        }
    }
    private function ftpDirectoryExists($ftp_conn, $dir)
    {
        $list = ftp_nlist($ftp_conn, $dir);
        if (is_array($list)){
            return true;
        }
        return false;
    }
}