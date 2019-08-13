<?php
/**
 * Main payment model
 *
 * @category    Model
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */
class Afone_Comnpay_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	
	protected $_code = 'comnpay';
	protected $_formBlockType = 'comnpay/form';
	protected $_infoBlockType = 'comnpay/info';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('comnpay/payment/redirect', array('_secure' => true));
	}
}
?>