<?php
class Afone_Comnpay_Block_InfoP3f extends Mage_Payment_Block_Info {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/infoP3f.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/comnpay/logo-comnpay.png'));
    }

}