<?php
/**
 * Created by PhpStorm.
 * User: bbilly
 * Date: 11/05/2015
 * Time: 10:56
 */
class Afone_Comnpay_Block_Adminhtml_Order_View_Tab_Infostransactions
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_chat = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('comnpay/infostransactions.phtml');
    }

    public function getTabLabel() {
        return $this->__('Transactions ComNpay');
    }

    public function getTabTitle() {
        return $this->__('Transactions ComNpay');
    }

    public function canShowTab() {
        return true;
    }

    /**
     * On vérifie si le paiement a été pass" avec comnpay
     * sinon on masque l'onglet détail des transactions comnpay
     */
    public function isHidden() {
        $type_payment = $this->getOrder()->getPayment()->getMethodInstance()->getCode();
        if($type_payment == "comnpay_p3f" || $type_payment == "comnpay_debit")
            return false;
        else
            return true;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }

    /**
    * On recupere les transactions en fonction de la méthode de paiement (debit ou pnf)
    */
    public function getTransactions() {

        $transaction_id = $this->getOrder()->getIncrementId();
        $type_payment = $this->getOrder()->getPayment()->getMethodInstance()->getCode();
        $config = Mage::getModel('comnpay/config');


        if($type_payment == "comnpay_p3f")
            return $this->getTransactionsPnf($transaction_id,$config);
        else if($type_payment == "comnpay_debit")
            return $this->getTransactionsDebit($transaction_id,$config);
        else
            return null;

    }

    public function getTransactionsPnf($transaction_id,$config) {
        $data="serialNumber=".$config->getTpeNo()."&key=".$config->getSecretKey()."&transactionRef=".$transaction_id;
        $url= $config->getGatewayURL().":".$config->getPort().$config->getPathP3f();
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'content' => $data
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        if($result != false) {
            $json = json_decode($result);
            $transac = array();
            foreach($json->pnfFile[0]->pnfTransactions as $transaction) {
                $transac[] = array(
                    'transacId'      => $transaction->pnfTransactionId,
                    'transacDate'    => $transaction->plannedDate,
                    'transacMontant' => $transaction->amount,
                    'transacOK'      => $transaction->message
                );
            }
            return $transac;
        }
        else {
            Mage::log("comnpay module: échec lors de l'appel au Web Service ! Commande :".$transaction_id, Zend_Log::DEBUG);
        }
    }

    public function getTransactionsDebit($transaction_id,$config) {
        $data="serialNumber=".$config->getTpeNo()."&key=".$config->getSecretKey()."&transactionRef=".$transaction_id;
        $url= $config->getGatewayURL().":".$config->getPort().$config->getPathD();
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'content' => $data
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        if($result != false) {
            $json = json_decode($result);
            $transac = array();
            foreach($json->transaction as $transaction) {
                $transac[] = array(
                    'transacId'      => $transaction->transactionId,
                    'transacDate'    => $transaction->transactionDate,
                    'transacMontant' => $transaction->amount,
                    'transacOK'      => $transaction->message
                );
            }
            return $transac;
        }
        else {
            Mage::log("comnpay module: échec lors de l'appel au Web Service ! Commande :".$transaction_id, Zend_Log::DEBUG);
        }
    }
}
?>