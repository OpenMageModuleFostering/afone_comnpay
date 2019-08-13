<?php
/**
 * Created by PhpStorm.
 * User: bbilly
 * Date: 13/05/2015
 * Time: 16:17
 */

class Afone_Comnpay_Model_Validate extends Mage_Core_Model_Config_Data
{

    public function save()
    {
        if($this->checkLogin()) {
            Mage::getSingleton('core/session')->addSuccess('Merci d\'avoir choisi ComNpay !');
        }
        else {
            Mage::getSingleton('core/session')->addError('Attention, votre num&eacute;ro de TPE et/ou cl&eacute; secr&egrave;te sont incorrects.');
        }

        return parent::save();  //call original save method so whatever happened
    }

    public function getFieldsetDataValue($key)
    {
        $data = $this->_getData('fieldset_data');
        return (is_array($data) && isset($data[$key])) ? $data[$key] : null;
    }

    public function checkLogin() {
        $tpe_no = $this->getFieldsetDataValue("tpe_no");
        $key =  $this->getFieldsetDataValue("secret_key");

        $config = Mage::getModel('comnpay/config');
        $data ="serialNumber=".$tpe_no."&key=".$key;
        $url = $config->getGatewayURL().":".$config->getPort().$config->getPathD();
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'content' => $data
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        $json = json_decode($result);
        if($json == null)
            return false;
        else
            return true;
    }

}