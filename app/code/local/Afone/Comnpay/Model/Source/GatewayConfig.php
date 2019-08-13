<?php

/**
 * Afone GatewayConfig Action Dropdown source
 */
class Afone_Comnpay_Model_Source_GatewayConfig
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => "HOMOLOGATION",
                'label' => Mage::helper('comnpay')->__('Homologation')
            ),
            array(
                'value' => "PRODUCTION",
                'label' => Mage::helper('comnpay')->__('Production')
            ),
        );
    }
}
