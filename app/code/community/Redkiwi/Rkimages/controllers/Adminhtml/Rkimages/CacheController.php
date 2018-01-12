<?php

class Redkiwi_Rkimages_Adminhtml_Rkimages_CacheController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Clean files cache.
     */
    public function cleanImagesAction()
    {
        try {
            Mage::getModel('rkimages/image')->clearCache();

            $this->_getSession()->addSuccess(
                Mage::helper('rkimages')->__('Rkimages image cache cleaned.')
            );
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('rkimages')->__('An error occurred while clearing Rkimages image cache.')
            );
        }

        $this->_redirect('*/cache/index');
    }

    /**
     * Check if cache management is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/cache');
    }
}
