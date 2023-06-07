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

    public function serverViewAction()
    {
        $this->loadLayout();
        $infoParams = ['DOCUMENT_ROOT', 'SERVER_NAME', 'SERVER_PROTOCOL', 'REMOTE_ADDR', 'SERVER_SOFTWARE',
            'HTTP_USER_AGENT','HTTP_HOST'];
        $serverInfo = '';

        $serverInfo .= '<tr><td class="label">' . $this->__('MySQL-Version') . '</td>
            <td class="value">' . $this->getMySQLVersion() .'</td></tr>';
        $serverInfo .= '<tr><td class="label">' . $this->__('PHP-Version') . '</td>
            <td class="value">' . phpversion() .'</td></tr>';
        $serverInfo .= '<tr><td class="label">' . $this->__('memory_limit') . '</td>
            <td class="value">' . ini_get('memory_limit') .'</td></tr>';
        $serverInfo .= '<tr><td class="label">' . $this->__('upload_max_filesize') . '</td>
            <td class="value">' . ini_get('upload_max_filesize') .'</td></tr>';
        $serverInfo .= '<tr><td class="label">' . $this->__('max_execution_time') . '</td>
            <td class="value">' . ini_get('max_execution_time') .'</td></tr>';
        $serverInfo .= '<tr><td class="label">' . $this->__('OS') . '</td>
            <td class="value">' . php_uname() .'</td></tr>';

        foreach ($_SERVER as $key => $item) {
            if (in_array($key, $infoParams)) {
                $serverInfo .= '<tr><td class="label">' . $key . '</td><td class="value">' . $item .'</td></tr>';
            }
        }

        $html = '<div>
            <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width:50%;"><h3 class="icon-head head-cms-page">Server-Info</h3></td>
                            <td class="form-buttons"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><div class="serverinfo-block"><table cellspacing="0" class="simple-list-table">
        <tbody>' . $serverInfo . '</tbody>
        </table></div>';

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
        <table class="openmage-info-table simple-list-table">';

        $fields = ['Openmage-Version:', 'Magento-Version:', 'Edition:', 'Path:', 'Stores:', 'Localisation:',
            'Installation-Date:', 'Zend-Version:'];
        foreach ($fields as $field) {
            $html .= '<tr><td class="label">' . $this->__($field) . '</td><td class="value">%s</td></tr>';
        }
        $html .= '</table>';

        $stores = [];
        foreach(Mage::app()->getStores() as $store) {
            $stores[] = str_pad($store->getId(),5, ' ', STR_PAD_RIGHT) . $store->getName() . ' [' . $store->getCode(). '] -> '.
                '<a href="' . $store->getBaseUrl() . '">' . $store->getBaseUrl() . '</a>';
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
            zend_version()
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

    public function browserViewAction()
    {
        $this->loadLayout();
        $html = '
        <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width:50%%;"><h3 class="icon-head head-cms-page">Browser Information</h3></td>
                            <td class="form-buttons"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table id="browser-list-table" class="simple-list-table"></table>
            <script>
                var tweakerInfo = new TweakerInfo();
                tweakerInfo.decorateInfoTable("browser-list-table");
            </script>';


        $block = $this->getLayout()->createBlock('core/text')->setText($html);
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    private function getMySQLVersion()
    {
        $output = shell_exec('mysql -V');
        preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
        return $version[0];
    }
}
