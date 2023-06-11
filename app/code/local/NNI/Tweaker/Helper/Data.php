<?php

class NNI_Tweaker_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $systemConfigHintValues = [];

    /**
     * get hint for system-configuration by path
     * @param $path
     * @return mixed|string
     */
    public function getSystemConfigHintValue($path)
    {
        if (!$this->systemConfigHintValues) {
            $data = Mage::getStoreConfig('nni_tweaker/hint_configuration/system_config');

            if ($data) {
                $data = unserialize($data);
                $this->systemConfigHintValues = array_combine(
                    array_column($data, 'config_path'),
                    array_column($data,'hint')
                );
            }
        }

        if (isset($this->systemConfigHintValues[$path])) {
            return $this->systemConfigHintValues[$path];
        }

        return '';
    }
}