<?php
/**
 * Main payment model
 *
 * @category    Model
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */
class Afone_Comnpay_Model_ModelAbstract extends Mage_Payment_Model_Method_Abstract {

	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('comnpay/payment/redirect', array('_secure' => true));
	}

    public function isAvailable($quote = null)
    {
        $keysAreSet = Mage::helper("comnpay")->isTpeNoSet() && Mage::helper("comnpay")->isSecretKeySet();
        return parent::isAvailable($quote) && $keysAreSet;
    }    
}
?>