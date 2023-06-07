<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit
{
    /**
     * add additional button after back-button to open product in frontend
     * @return string
     */
    public function getBackButtonHtml()
    {
        if (
            !$this->getProduct() ||
            Mage::getStoreConfig("nni_tweaker/product_settings/show_open_in_frontend") !=1
         ) {
            return parent::getBackButtonHtml();
        }

        $productUrl = $this->getProduct()->getProductUrl();
        $openInFrontendButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData([
                'label'     => Mage::helper('catalog')->__('Open in Frontend'),
                'onclick'   => 'window.open(\'' . $productUrl . '\', \'_blank\')',
                'class'     => 'action_button_lightblue go',
                'title'     => 'Open product in frontend for current storeview'
            ]);
        return $this->getChildHtml('back_button') . $openInFrontendButton->toHtml();
    }
}