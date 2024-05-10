<?php
class Ccc_Repricer_Adminhtml_MatchingController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Repricer'));
        $this->_initAction();
        $this->renderLayout();
    }

    // public function editAction()
    // {
    //     $this->_title($this->__('Manage Repricer'));

    //     $id = $this->getRequest()->getParam('repricer_id');

    //     $model = Mage::getModel('repricer/matching');

    //     if ($id) {
    //         $model->load($id);
    //         if (!$model->getId()) {
    //             Mage::getSingleton('adminhtml/session')->addError(
    //                 Mage::helper('repricer')->__('This repricer no longer exists.')
    //             );
    //             $this->_redirect('*/*/');
    //             return;
    //         }
    //     }

    //     $this->_title($model->getId() ? $model->getTitle() : $this->__('Repricer'));

    //     $data = Mage::getSingleton('adminhtml/session')->getFormData();
    //     if (!empty($data)) {
    //         $model->setData($data);
    //     }

    //     Mage::register('repricer', $model);

    //     $this->_initAction();

    //     $this->renderLayout();
    // }

    // public function saveAction()
    // {
    //     if ($data = $this->getRequest()->getPost()) {
    //         $model = Mage::getModel('repricer/matching');

    //         if ($id = $this->getRequest()->getParam('repricer_id')) {
    //             $model->load($id);
    //         }

    //         if (!empty($data['competitor_sku']) && !empty($data['competitor_url']))
    //         {
    //             if ($data['competitor_price']>0){
    //                 $data['reason'] = 1;
    //             } else {
    //                 $data['reason'] = 3;
    //             }
    //         } else {
    //             $data['reason'] = 0;
    //         }

    //         $model->setData($data);

    //         Mage::dispatchEvent('repricer_prepare_save', array('repricer' => $model, 'request' => $this->getRequest()));

    //         try {
    //             $model->save();

    //             Mage::getSingleton('adminhtml/session')->addSuccess(
    //                 Mage::helper('repricer')->__('The Repricer has been saved.')
    //             );

    //             Mage::getSingleton('adminhtml/session')->setFormData(false);

    //             if ($this->getRequest()->getParam('back')) {
    //                 $this->_redirect('*/*/edit', array('repricer_id' => $model->getId(), '_current' => true));
    //                 return;
    //             }

    //             $this->_redirect('*/*/');
    //             return;

    //         } catch (Mage_Core_Exception $e) {
    //             $this->_getSession()->addError($e->getMessage());
    //         } catch (Exception $e) {
    //             $this->_getSession()->addException(
    //                 $e,
    //                 Mage::helper('repricer')->__('An error occurred while saving the Repricer.')
    //             );
    //         }

    //         $this->_getSession()->setFormData($data);
    //         $this->_redirect('*/*/edit', array('repricer_id' => $this->getRequest()->getParam('repricer_id')));
    //         return;
    //     }

    //     $this->_redirect('*/*/');
    // }

    public function editAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repricerId = $this->getRequest()->getPost('itemId');
            $editedData = $this->getRequest()->getPost('editedData');

            $repricer = Mage::getModel('repricer/matching');

            if ($repricerId) {

                $repricer->addData(['repricer_id' => $repricerId]);

                foreach ($editedData as $field => $value) {
                    $repricer->addData([$field => $value]);
                }
                $competitorSku = $repricer->getCompetitorSku();
                $competitorUrl = $repricer->getCompetitorUrl();
                $competitorPrice = $repricer->getCompetitorPrice();

                switch ($repricer->getReason()) {
                    case $repricer::CONST_REASON_NO_MATCH:
                    case $repricer::CONST_REASON_ACTIVE:
                        if (!empty($competitorUrl) && !empty($competitorSku)) {
                            if ($competitorPrice > 0) {
                                $repricer->addData(['reason' => $repricer::CONST_REASON_ACTIVE]);
                            } else {
                                $repricer->addData(['reason' => $repricer::CONST_REASON_NOT_AVAILABLE]);
                            }
                        } else {
                            $repricer->addData(['reason' => $repricer::CONST_REASON_NO_MATCH]);
                        }
                        break;
                    case $repricer::CONST_REASON_NOT_AVAILABLE:
                        $repricer->addData(['competitor_price' => 0.0]);
                        break;
                    case $repricer::CONST_REASON_WRONG_MATCH:
                        $repricerData = Mage::getModel('repricer/matching')->load($repricerId);
                        if (!empty($competitorUrl) && !empty($competitorSku)) {
                            if (
                                ($repricerData->getReason() == $repricer::CONST_REASON_WRONG_MATCH)
                                && (($repricer->getCompetitorUrl() != $repricerData->getCompetitorUrl())
                                    || ($repricer->getCompetitorSku() != $repricerData->getCompetitorSku()))
                            ) {
                                $repricer->addData(['competitor_price' => 0.0]);
                                $repricer->addData(['reason' => $repricer::CONST_REASON_NOT_AVAILABLE]);
                            }
                        } else {
                            $repricer->addData(['reason' => $repricer::CONST_REASON_NO_MATCH]);
                        }
                        break;
                }

                // if (!empty($editedData['competitor_sku']) && !empty($editedData['competitor_url']))
                // {
                //     if ($editedData['competitor_price']>0){
                //         $repricer->addData(['reason' => $repricer::CONST_REASON_ACTIVE]);
                //     } else {
                //         $repricer->addData(['reason' => $repricer::CONST_REASON_NOT_AVAILABLE]);
                //     }
                // } else {
                //     $repricer->addData(['reason' => $repricer::CONST_REASON_NO_MATCH]);
                // }

                $repricer->save();
            }

            $response = array(
                'success' => true,
                'message' => 'Data saved successfully'
            );
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('repricer/adminhtml_matching/grid')->getGridHtml()
        );
    }

    public function massReasonAction()
    {
        $requestedData = $this->getRequest()->getParam('pc_comb');
        $reason = $this->getRequest()->getParam('reason');

        $pcIds = [];
        // print_r($requestedData);
        // die;

        foreach ($requestedData as $pcId) {
            $pcIds[] = $pcId;
        }

        if (!is_array($pcIds)) {
            $pcIds = array($pcIds);
        }

        try {

            $repricerUpdatedRows = 0;

            foreach ($pcIds as $_repricer) {
                $values = explode("-", $_repricer);
                $productId = (int) $values[0];
                $competitorId = (int) $values[1];

                $model = Mage::getModel('repricer/matching');
                $matching = $model->getCollection()
                    ->addFieldToFilter('product_id', $productId)
                    ->addFieldToFilter('competitor_id', $competitorId)
                    ->getFirstItem();

                $repricerId = $matching->getId();

                if ($repricerId) {
                    $model->addData([
                        'product_id' => $productId,
                        'competitor_id' => $competitorId,
                        'repricer_id' => $repricerId
                    ]);
                    if ($matching->getReason() != $reason) {
                        $matching->addData(['reason' => $reason])->save();
                        $matching->save();
                        $repricerUpdatedRows++;
                    }
                }

            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', $repricerUpdatedRows)
            );

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'new':
                $aclResource = 'catalog/repricer/manage_repricer/actions/add_new'; 
                break;
            case 'edit':
                $aclResource = 'catalog/repricer/manage_repricer/actions/edit';
                break;
            case 'index':
                $aclResource = 'catalog/repricer/manage_repricer/actions/show_all';
                break;
            default:
                $aclResource = 'catalog/repricer/manage_repricer/actions/show_all';
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}
