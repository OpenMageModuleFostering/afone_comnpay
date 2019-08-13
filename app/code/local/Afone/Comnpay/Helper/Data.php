<?php
class Afone_Comnpay_Helper_Data extends Mage_Core_Helper_Abstract{

	/**
     * Validates the private key value by comparing it to an empty string
     * @return boolean
     */
    public function isTpeNoSet()
    {
        $config = Mage::getModel('comnpay/config');
        return $config->getTpeNo();
   }

    /**
     * Validates the public key value by comparing it to an empty string
     * @return boolean
     */
    public function isSecretKeySet()
    {
        $config = Mage::getModel('comnpay/config');
        return $config->getSecretKey() !== "";
    }
}
?>