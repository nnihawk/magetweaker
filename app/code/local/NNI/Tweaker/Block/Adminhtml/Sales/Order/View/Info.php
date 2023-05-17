<?php
/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order history block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class NNI_Tweaker_Block_Adminhtml_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_View_Info
{
    protected function _toHtml()
    {
        if (Mage::getStoreConfig("nni_tweaker/general_settings/allow_order_email_change") !=1) {
            return  parent::_toHtml();
        }

        $orderUrl = Mage::helper("adminhtml")->getUrl(
            'adminhtml/tweaker/changeOrderEmail',
            ['order_id' => $this->getRequest()->getParam('order_id'), 'email' => '{new_email}'],
        );

        $script = <<<HTML
            <script>
             function addUpdateEmailButton()
             {
                let accountBox = document.querySelector('.box-right .icon-head.head-account').parentElement.parentElement;
                let emailField = accountBox.querySelector('.fieldset .value a[href^="mailto:"]');
                if (emailField) {
                    let changeLink = document.createElement('a');
                    changeLink.onclick = updateEmail;
                    changeLink.innerHTML = '(change)';
                    changeLink.style.marginLeft = '10px';
                    changeLink.title = 'Change email of this order';
                    changeLink.style.cursor ='pointer';
                    emailField.parentElement.appendChild(changeLink);
                }
             }
             
             function updateEmail()
             {
                 let newEmail = prompt('Enter new email:', '');
                 let url = '{$orderUrl}';
                
                 if (newEmail != null && newEmail != '') {
                     let newUrl = url.replace('{new_email}', newEmail);
                     window.location.href = newUrl;
                 }
             }
             
             addUpdateEmailButton();
            </script>
        HTML;


        return parent::_toHtml() . $script;
    }
}
