<?php
class Afone_Comnpay_Block_FormP3f extends Mage_Payment_Block_Form {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('comnpay/formP3f.phtml');
        $this->setBannerSrc($this->getSkinUrl('images/comnpay/comnpay_p3f.png'));
    }

}