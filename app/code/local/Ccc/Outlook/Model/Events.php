<?php 

class Ccc_Outlook_Model_Events extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('outlook/events');
    }

    public function saveEvents($postData, $configModel)
    {
        $configId = $configModel->getConfigId();
        $eventsData=[];

        $eventModel = Mage::getModel('outlook/events');

        $groupId = $eventModel->getCollection()
            ->addFieldToSelect('group_id')
            ->setOrder('group_id', 'Desc')
            ->getFirstItem()
            ->getGroupId();

        foreach ($postData as $_event) {
            if (!empty($_event['group_id'])){
                foreach ($_event['rules'] as $_rules) {
                    $_rules['group_id'] = $_event['group_id'];
                    $_rules['config_id'] = $configId;
                    $_rules['event'] = $_event['event'];
                    if(empty($_rules['event_id'])){
                        unset($_rules['event_id']);
                    }
                    $eventsData[] = $_rules;
                }
            } else {
                $groupId++;
                foreach ($_event['rules'] as $_rules) {
                    unset($_rules['event_id']);
                    $_rules['group_id'] = $groupId;
                    $_rules['config_id'] = $configId;
                    $_rules['event'] = $_event['event'];
                    $eventsData[] = $_rules;
                }
            }
        }
        foreach ($eventsData as $_row) {
            $eventModel->setData($_row);
            $eventModel->save();
        }
    }


}   