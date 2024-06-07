<?php

class Ccc_Outlook_Model_Email extends Mage_Core_Model_Abstract
{
    private $_configObject = null;
    protected function _construct()
    {
        $this->_init('outlook/email');
    }

    public function setConfigObject($configObj)
    {
        $this->_configObject = $configObj;
        return $this;
    }

    public function checkEvents()
    {
        $configId = $this->_configObject->getConfigId();
        $eventModel = Mage::getModel('outlook/events');
        $eventCollection = $eventModel->getCollection()
            ->addFieldToFilter('config_id', $configId)
            ->addFieldToSelect('group_id');
            
        $eventCollection->getSelect()->group('group_id');
        
        foreach ($eventCollection as $_event){
            $dispatch_event = false;
            $newId = $_event->getGroupId();
            
            $collection = Mage::getModel('outlook/events')->getCollection()
                ->addFieldToFilter('group_id', $newId);

                foreach ($collection as $_rule){
                    $condition = $_rule->getConditionOn();
                    $operator = $_rule->getOperator();
                    $value = $_rule->getValue();
                    if($condition == 'from'){
                        $checkRuleCollection = $eventModel->getCollection()
                            ->addFieldToFilter('group_id', $newId);
                        if ($operator == '='){
                            $checkRuleCollection->addFieldToFilter('value', $value );
                            $records = $checkRuleCollection->getSize();
                            if ($records){
                                $dispatch_event = true;
                                continue;
                            } else{
                                $dispatch_event = false;
                                break;
                            }
                        }
                        if ($operator == 'like'){
                            $checkRuleCollection->addFieldToFilter('value', ['like' => '%'. $value .'%']);
                            $records = $checkRuleCollection->getSize();
                            if ($records){
                                $dispatch_event = true;
                                continue;
                            } else{
                                $dispatch_event = false;
                                break;
                            }
                        }
                    }
                    if($condition == 'subject'){
                        $checkRuleCollection = $eventModel->getCollection()
                            ->addFieldToFilter('group_id', $newId);
                        if ($operator == '='){
                            $checkRuleCollection->addFieldToFilter('value', $value );
                            $records =$checkRuleCollection->getSize();
                            if ($records){
                                $dispatch_event = true;
                                continue;
                            } else{
                                $dispatch_event = false;
                                break;
                            }
                        }
                        if ($operator == 'like'){
                            $checkRuleCollection->addFieldToFilter('value', ['like' => '%'. $value .'%']);
                            $records =$checkRuleCollection->getSize();
                            if ($records){
                                $dispatch_event = true;
                                continue;
                            } else{
                                $dispatch_event = false;
                                break;
                            }
                        }
                    }
                }
            $eventName = $collection->getFirstItem()->getEvent();
            
            if ($dispatch_event) {
                Mage::dispatchEvent($eventName, ['model'=>$this]);
            }

        }
    }

    public function saveAttachments($allAttachment)
    {
        $attachmentModel = Mage::getModel('outlook/attachment');
        foreach ($allAttachment  as $_attachment) {
            $fileName = $_attachment['name'];
            $fileData = base64_decode($_attachment['contentBytes']);
            $path = Mage::helper('outlook')->getBasePath();
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $uniqueFilename = $attachmentModel->getUniqueName($path,$fileName);

            $filePath = $path . DS . $uniqueFilename;
            file_put_contents($filePath, $fileData);

            $data = [
                'name' => $uniqueFilename,
                'email_id' => $this->getId(),
            ];
            $attachmentModel->setData($data)->save();
        }
    }
}