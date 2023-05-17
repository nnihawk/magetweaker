<?php

class NNI_Tweaker_Adminhtml_TweakerController extends Mage_Adminhtml_Controller_Action
{
    public function exportCmsPageAction()
    {
        if (($pageId = $this->getRequest()->getParam('page_id', 0))) {
            $cmsPage = Mage::getModel('cms/page')->load($pageId);
            if (is_object($cmsPage) && $cmsPage->getId() > 0) {
                $data = $cmsPage->getData();

                $json = json_encode($data);
                $this->_prepareDownloadResponse('CMS_Page_' . $pageId . '.json', $json);
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid id!'));
        $this->_redirect('*/*/');
    }

    public function importCmsPageAction()
    {
        if (($pageId = $this->getRequest()->getParam('page_id', 0))) {
            $cmsPage = Mage::getModel('cms/page')->load($pageId);
            if (
                (is_object($cmsPage) && $cmsPage->getId() > 0) &&
                (isset($_FILES['jsonfile']['name']) && $_FILES['jsonfile']['name'] != '')
            ) {
                $saveDir = Mage::getBaseDir('var') . "/tmp/";
                $filename = str_replace(" ", '_', $_FILES['jsonfile']['name']);

                $uploader = new Varien_File_Uploader('jsonfile');
                $uploader->setAllowedExtensions(['json']);
                $uploader->setAllowCreateFolders(true);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($saveDir, $filename);

                $io = new Varien_Io_File();
                $io->open(['path' => $saveDir]);
                $json = $io->read($filename);
                $io->rm($saveDir . $filename);

                try {
                    $data = json_decode($json, true);
                    $data['page_id'] = $cmsPage->getId();
                    $data['identifier'] = $cmsPage->getIdentifier();
                    $data['stores'] = $cmsPage->getStores();
                    $cmsPage->setData($data);
                    $cmsPage->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Import successful!'));
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid id!'));
        }
    }

    public function changeOrderEmailAction()
    {
        $email = $this->getRequest()->getParam('email');
        $orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);

        if(!$order->getId()){
            Mage::getSingleton('adminhtml/session')->addError($this->__('Invalid order!'));
        } else {
            $order->setCustomerEmail($email)->save();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Email changed successfully!'));
        }

        return $this->_redirect('*/sales_order/view', array('order_id' => $orderId));
    }

    public function phpinfoViewAction()
    {
        $this->loadLayout();
        ob_start();
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT | INFO_VARIABLES);
        $phpinfo = ob_get_contents();
        ob_get_clean();

        $phpinfo = substr($phpinfo, strpos($phpinfo,'<body>')+6, strlen($phpinfo));
        $phpinfo = str_replace('</body></html>','', $phpinfo);

        $html = '<div>
            <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width:50%;"><h3 class="icon-head head-cms-page">PHP-Info</h3></td>
                            <td class="form-buttons"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><div class="phpinfo-block">' . $phpinfo . '</div>';

        $block = $this->getLayout()->createBlock('core/text')->setText($html);
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    public function openmageViewAction()
    {
        $this->loadLayout();
        $html = '
        <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width:50%%;"><h3 class="icon-head head-cms-page">Openmage-Info</h3></td>
                            <td class="form-buttons"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <table class="openmage-info-table">';

        $fields = ['Openmage-Version:', 'Magento-Version:', 'Edition:', 'Path:', 'Stores:', 'Localisation:', 'Installation-Date:'];
        foreach ($fields as $field) {
            $html .= '<tr><td class="label">' . $field . '</td><td class="value">%s</td></tr>';
        }
        $html .= '</table>';

        $stores = [];
        foreach(Mage::app()->getStores() as $store) {
            $stores[] = str_pad($store->getId(),5, ' ', STR_PAD_RIGHT) . $store->getName() . ' [' . $store->getCode(). '] -> '.
                $store->getBaseUrl();
        }

        $html = sprintf(
            $html,
            Mage::getOpenMageVersion(),
            Mage::getVersion(),
            Mage::getEdition(),
            Mage::getBaseDir(),
            implode('<br>', $stores),
            Mage::app()->getLocale()->getLocaleCode(),
            Mage::getConfig()->getNode('global/install/date'),
        );

        $block = $this->getLayout()->createBlock('core/text')->setText($html);
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    public function extensionViewAction()
    {
        $this->loadLayout();
        $html = '
        <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width:50%%;"><h3 class="icon-head head-cms-page">Installed Extensions</h3></td>
                            <td class="form-buttons"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="modules-list-table">';

        $modules = Mage::getConfig()->getNode('modules')->children();
        $html .= '<tr><td>Name</td><td>Active</td><td>Code-Pool</td><td>Version</td><td>Dependencies</td></tr>';

        foreach ($modules as $name => $module) {
            $depends = [];
            if ($module->depends) {
                foreach ($module->depends->children() as $name => $dep) {
                    $depends[] = $name;
                }
            }
            $html .= '<tr><td>' . $name .'</td><td>' . $module->active . '</td><td>' . $module->codePool .
                '</td><td>' . $module->version . '</td><td>' . implode('<br>', $depends) . '</td></tr>';
        }
        $html .= '</table>';

        $block = $this->getLayout()->createBlock('core/text')->setText($html);
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }
}
