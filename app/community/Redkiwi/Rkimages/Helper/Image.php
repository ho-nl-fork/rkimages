<?php

class Redkiwi_Rkimages_Helper_image extends Mage_Catalog_Helper_Image 
{
    /**
     * Initialize Helper to work with Image
     *
     * @param string $source
     * @param string $cachedir
     * @return Redkiwi_Rkimages_Helper_image
     */
    public function run($source, $cachedir=null)
    {
        $this->_reset();
		
		$model = Mage::getModel('rkimages/image')
					->setDestinationSubdir($cachedir);
		
        $this->_setModel($model);
		
        $this->setWatermark(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")
        );
        $this->setWatermarkImageOpacity(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity")
        );
        $this->setWatermarkPosition(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position")
        );
        $this->setWatermarkSize(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size")
        );

        //$this->setImageFile($source);
		$this->_getModel()->setBaseFile($source);
		
		$this->setProduct(Mage::getModel('catalog/product'));
		
        return $this;
    }

    /**
     * Get Placeholder
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/catalog/product/placeholder/'.$attr.'.jpg';
        }
        return $this->_placeholder;
    }

    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $model = $this->_getModel();
			
			$model->setBaseFile($model->getBaseFile());
			
            if ($model->isCached()) {
                return $model->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                if ($this->getWatermark()) {
                    $model->setWatermark($this->getWatermark());
                }

                $url = $model->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }
	
}
