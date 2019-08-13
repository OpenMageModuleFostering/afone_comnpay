<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Model
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */


/**
 * Config model
 */
class Afone_Comnpay_Model_Config extends Mage_Payment_Model_Config
{
    const CONFIG_PATH = 'payment/comnpay_debit/';
    const VERSION = "1.0.0";


    /**
     * Return comnpay extension name
     *
     */
    public function getExtensionName()
    {
        return "magento-comnpay-".self::VERSION;
    }

    /**
     * Return comnpay config information
     *
     * @param string $path
     * @param int $storeId
     * @return Simple_Xml
     */
    public function getConfigData($path, $storeId=null)
    {
        if (!empty($path)) {
            return Mage::getStoreConfig(self::CONFIG_PATH . $path, $storeId);
        }
        return false;
    }


    /**
     * Return config value for TPE Number
     *
     */
    public function getTpeNo()
    {
        return $this->getConfigData('tpe_no');
    }

    /**
     * Return config value for Devise
     *
     */
    public function getDevise()
    {
        return $this->getConfigData('devise');
    }

    /**
     * Return config value for Langue
     *
     */
    public function getLangue()
    {
        return $this->getConfigData('langue');
    }


    /**
     * Return config value for Secret Key
     *
     */
    public function getSecretKey()
    {
        return $this->getConfigData('secret_key');
    }

    /**
     * Return Production Gateway URL
     *
     */
    public function getProductionGatewayURL()
    {
        return $this->getConfigData('url_gateway_production');
    }

    /**
     * Return Homologation Gateway URL
     *
     */
    public function getHomologationGatewayURL()
    {
        return $this->getConfigData('url_gateway_homologation');
    }

    /**
     * Return Gateway Config
     *
     */
    public function getGatewayConfig()
    {
        return $this->getConfigData('url_gateway_config');
    }


    /**
     * Return Gateway URL (depends Gateway URL Config : PRODUCTION or HOMOLOGATION)
     *
     */
    public function getGatewayURL($config=null)
    {
        if ($config==null)
        {
            $config=$this->getGatewayConfig();
        }

        if ($config=="HOMOLOGATION")
        {
            return $this->getHomologationGatewayURL();
        }
        else
        {
            return $this->getProductionGatewayURL();
        }
    }

    /**
     * Return Port
     *
     */
    public function getPort()
    {
        return $this->getConfigData('port');
    }

    /**
     * Return API Path debit
     *
     */
    public function getPathD()
    {
        return $this->getConfigData('path_d');
    }

    /**
     * Return API Path PNF
     *
     */
    public function getPathP3f()
    {
        return $this->getConfigData('path_p3f');
    }

}
?>