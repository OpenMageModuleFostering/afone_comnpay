<?php
/**
 * Main payment model
 *
 * @category    Model
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */
class Afone_Comnpay_Model_ComnpayP3f extends Afone_Comnpay_Model_ModelAbstract {
	
	protected $_code = 'comnpay_p3f';
	protected $_formBlockType = 'comnpay/formP3f';
	protected $_infoBlockType = 'comnpay/infoP3f';

	public $typeTr = "p3f";
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
}
?>