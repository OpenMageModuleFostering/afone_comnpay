<?php
class Afone_Comnpay_Block_Info extends Mage_Payment_Block_Info {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/info.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/logo-comnpay.png'));	
    }


}
