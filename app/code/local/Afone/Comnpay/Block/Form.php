<?php
class Afone_Comnpay_Block_Form extends Mage_Payment_Block_Form {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/form.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/logo-comnpay.png'));	
    }

}
