<?php
/**
 * Main payment model
 *
 * @category    Model
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */
class Afone_Comnpay_Model_ComnpayDebit extends Afone_Comnpay_Model_ModelAbstract {
	
	protected $_code = 'comnpay_debit';
	protected $_formBlockType = 'comnpay/formDebit';
	protected $_infoBlockType = 'comnpay/infoDebit';
	
	public $typeTr = "d";

	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
}
?>