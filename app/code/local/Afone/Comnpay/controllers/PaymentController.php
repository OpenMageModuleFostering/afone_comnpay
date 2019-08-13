<?php
/**
 * Comnpay payment controller
 * Documentation : http://docs.comnpay.com/
 *
 * @category    Controller
 * @package     Afone_Comnpay
 * @author      Afone
 * @license     GPL http://opensource.org/licenses/gpl-license.php
 */
class Afone_Comnpay_PaymentController extends Mage_Core_Controller_Front_Action {

    /**
     * Redirects the user to the success page
     */
    public function successAction() {
        $this->_redirect('checkout/onepage/success');
    }

    /**
     * Redirects the user to the error page
     */
    public function errorAction() {
        $this->_redirect('checkout/onepage/failure');
    }

    /*
     * The redirect action is triggered when someone places an order
     */
    public function redirectAction () {

        $config = Mage::getModel('comnpay/config');

        $order = new Mage_Sales_Model_Order();
        $order_id = Mage::getSingleton( 'checkout/session' )->getLastRealOrderId();
        $order->loadByIncrementId( $order_id );

        $payment = $order->getPayment()->getMethodInstance();

        $country = Mage::getModel('directory/country');

        $customer = $order->getBillingAddress()->getData();

        $comnpay['montant'] = number_format( $order->base_grand_total, 2 , '.', '');
        $comnpay['idTPE'] = $config->getTpeNo();
        $comnpay['idTransaction'] = $order_id;
        $comnpay['devise'] = $config->getDevise();
        $comnpay['lang'] = $config->getLangue();
        $comnpay['nom_produit'] = Mage::app()->getStore()->getName();
        $comnpay['source'] = $_SERVER['SERVER_NAME'];
        $comnpay['urlRetourOK'] = Mage::getUrl( 'comnpay/payment/success' , array('_secure' => true) );
        $comnpay['urlRetourNOK'] = Mage::getUrl( 'comnpay/payment/error' , array('_secure' => true));
        $comnpay['urlIPN'] = Mage::getUrl( 'comnpay/payment/ipn' , array('_secure' => true) );
        $comnpay['extension'] = $config->getExtensionName();
        $comnpay['typeTr'] =  $payment->typeTr;
        $comnpay['data'] = $payment->typeTr;
        if(  $payment->typeTr == "p3f" ) {
            $comnpay["user_nom"] = $customer["lastname"];
            $comnpay["user_prenom"] = $customer["firstname"];
            $comnpay["user_email"] = $customer["email"];
            $comnpay["user_telephone"] = $customer["telephone"];
            $comnpay["user_adresse"] = $customer["street"];
            $comnpay["user_codePostal"] =  $customer["postcode"];
            $comnpay["user_ville"] = $customer["city"];
            $comnpay["user_pays"] = $country->loadByCode($customer["country_id"])->getName();
        }
        $comnpay['key'] = $config->getSecretKey();

        // Encoding
        $comnpayWithKey = base64_encode(implode("|", $comnpay));
        unset($comnpay['key']);
        $comnpay['sec'] = hash("sha512",$comnpayWithKey);

        // Generate Form
        $form = "";
        foreach ($comnpay as $key => $value) {
            $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
        }
        $comnpay_config['form'] = $form;
        $comnpay_config['url-config'] = $config->getGatewayConfig();
        $comnpay_config['form-action'] = $config->getGatewayURL($comnpay_config['url-config']);


        // Save information
        Mage::register( 'comnpay', $comnpay_config );
        $order->setState(
            Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
        );
        $order->addStatusHistoryComment(Mage::helper('comnpay')->__('Customer was redirected to ComNpay'));
        $order->save();

        // Load and render the layout

        $this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','comnpay',array('template' => 'comnpay/redirect.phtml'));
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    /*
     * The ipn action is triggered when your gateway sends back a response after processing the customer's payment
     */
    public function ipnAction() {
        Mage::log("Appel de l'ipn ...", Zend_Log::DEBUG);
        Mage::log($_REQUEST, Zend_Log::DEBUG);

        $config = Mage::getModel('comnpay/config');

        if($this->getRequest()->isPost())
        {
            if (!$this->validSec($_REQUEST, $config->getSecretKey()))
            {
                Mage::log("Comnpay : Erreur lors de la validation des informations transmises !", Zend_Log::ERR);
                $this->cancelAction();
                header("Status: 400 Bad Request", false, 400);
                exit();
            }

            $validated="";
            if (isset($_REQUEST['result']))
                $validated = $_REQUEST['result'];


            if($validated == "OK") {
                // Payment was successful, so update the order's state, send order email and move to the success page
                $orderId="";
                if (isset($_REQUEST['idTransaction']))
                    $orderId = $_REQUEST['idTransaction'];

                $order = Mage::getModel('sales/order');
                $order->loadByIncrementId($orderId);
                if($_REQUEST["data"] == "p3f") {
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, Mage::helper('comnpay')->__("ComNpay has authorized the payment in 3 times."));
                }
                else
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, Mage::helper('comnpay')->__("ComNpay has authorized the payment."));

                $order->sendNewOrderEmail();
                $order->setEmailSent(true);
                $order->save();

                //Create an invoice for validate order
                if(!$order->canInvoice())
                {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
                }

                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

                if (!$invoice->getTotalQty()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                }

                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                $transactionSave->save();

                Mage::getSingleton('checkout/session')->unsQuoteId();
            }
            else
            {
                // There is a problem in the response we got
                Mage::log("Comnpay : Paiement refusé !", Zend_Log::DEBUG);
                $this->cancelAction();
            }
        }
        else
        {
            Mage::log("Comnpay : Aucun paramètre POST reçu !", Zend_Log::ERR);
            header("Status: 400 Bad Request", false, 400);
        }

        exit();
    }

    /*
     * The cancel action is triggered when an order is to be cancelled
     */
    public function cancelAction() {
        if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
            if($order->getId()) {
                // Flag the order as 'cancelled' and save it
                $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
            }
        }
    }

    /*
     * Secret Validation
     */
    private function validSec($values, $secret_key){
        if (isset($values['sec']) && $values['sec'] != "")
        {
            $sec = $values['sec'];
            unset($values['sec']);
            return strtoupper(hash("sha512", base64_encode(implode("|",$values)."|".$secret_key))) == strtoupper($sec);
        }
        else
        {
            return false;
        }
    }

}