<?php
class Afone_Comnpay_Block_InfoDebit extends Mage_Payment_Block_Info {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/infoDebit.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/comnpay/logo-comnpay.png'));
    }

}