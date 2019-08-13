<?php

/**
 * Afone PnfConfig Action Dropdown source
 */
class Afone_Comnpay_Model_Source_PnfConfig
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => "OUI",
                'label' => Mage::helper('comnpay')->__('Oui')
            ),
            array(
                'value' => "NON",
                'label' => Mage::helper('comnpay')->__('Non')
            ),
        );
    }
}