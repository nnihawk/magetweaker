<?php

class NNI_Tweaker_Block_Adminhtml_Catalog_Product_Edit_Tab_Inventory
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory
{
    /**
     * show also attribute-code under label of attribute
     * @return string
     */
    protected function _toHtml()
    {
        Mage::dispatchEvent('adminhtml_block_html_before', ['block' => $this]);
        $html = parent::_toHtml();
        if (Mage::getStoreConfig("nni_tweaker/general_settings/show_attribute_codes")==1) {
            $html .= <<<HTML
               <script> 
                 function addAttributeCodeToLabels()
                  {
                      let table = document.getElementById("table_cataloginventory");
                      table.querySelectorAll("tr>td.label label").forEach(label => {
                          let attributeCode = "<span class='attribute-code-view'>[" + label.getAttribute("for") +"]</span>"; 
                          label.innerHTML = label.innerHTML + "<br>" +attributeCode;
                      })
                  }
                  
                  addAttributeCodeToLabels();
               </script>;
            HTML;
        }

        return $html;
    }
}
