<?php
class Afone_Comnpay_Block_FormDebit extends Mage_Payment_Block_Form {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/formDebit.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/comnpay/comnpay_debit.png'));
    }
    
}