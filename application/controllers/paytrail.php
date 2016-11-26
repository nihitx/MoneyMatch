<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paytrail extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/payinginvoice
	 *	- or -  
	 * 		http://example.com/index.php/payinginvoice/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/payinginvoice/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 * license for xml converter https://github.com/dmitrirussu/php-sepa-xml-generator/blob/master/LICENSE
	 */
	 
        function __construct() {
            parent::__construct();
        }
        
        public function getPaytrailTokenWithAjax($newamount){
               $resultArray = $this->getPaytrailToken($newamount);
               if( $resultArray['result'] == 'success'){
                   
                   $this->output->set_content_type('application/json');
                   return $this->output->set_output(json_encode($resultArray));
               }else{
                   return false;
               }
        }
        
        public function getPaytrailTokenWithAjax2($newamount , $borrow_orig_id){
            
               $resultArray = $this->getPaytrailToken($newamount);
               if( $resultArray['result'] == 'success'){
                   $this->load->model('user_model');
                    $result = $this->user_model->getBorrowRequestTable($borrow_orig_id);
                    $results = json_decode(json_encode($result[0]), true);
                    $results['status'] = 3;
        
                    $update = $this->user_model->updateBorrowRequestTable($results);
                    if(!$update){
                        $this->output->set_status_header('401', 'could not update');
                    }
                   $this->output->set_content_type('application/json');
                   return $this->output->set_output(json_encode($resultArray));
               }else{
                   return false;
               }
        }
	
        public function getPaytrailToken($newAmount)
        { 
            require_once(APPPATH.'third_party/Paytrail/Paytrail_Module_Rest.php');
 
            
            
                $urlset = new Paytrail_Module_Rest_Urlset(
                    base_url(). "index.php/paytrail/PaytrailSuccess", // onnistuneen maksun paluuosoite
                    base_url(). "index.php/paytrail/PaytrailFailure", // epäonnistuneet maksun paluuosoite
                    base_url(). "index.php/paytrail/PaytrailNotify",  // osoite, johon lähetetään maksuvarmistus Paytrailin palvelimelta
                    "" // pending-osoite ei käytössä
                );
             
            

            // Payment creation
                $orderNumber = 245;                     // Use distinguished order number
                $price = $newAmount;                         // Total  (incl. VAT)
                $payment = new Paytrail_Module_Rest_Payment_S1($orderNumber, $urlset, $price);

                // Changing payment default settings
                // Changing payment method selection page language into English here
                // The default language is Finnish. See other options from PHP class
                $payment->setLocale("en_US");

            
            // Lähetetään maksu Paytrailin palveluun ja käsitellään mahdolliset virheet
            $module = new Paytrail_Module_Rest(13466, "6pKF4jkv97zmqBJ3ZL8gUw5DfT2NMQ");
            try {
                $result = $module->processPayment($payment);
                
                $resultArray['result'] = 'success';
                $resultArray['paytrail_token'] = $result->getToken();
            }
            catch (Paytrail_Exception $e) {
                // käsitellään virhe
                // Virheen kuvaus luettavissa $e->getMessage()
                $resultArray['result'] = 'failed';
                $resultArray['resultText'] = $e->getMessage();
            }

            // Käytetään Paytrailin palauttamaa url-osoitetta maksun suorittamiselle
            // halutulla tavalla. Tässä käyttäjä ohjataan välittömästi saatuun osoitteeseen.
            // header("Location: {$result->getUrl()}");
            return $resultArray;
        }
        
    /* Paytrail success return address *********************************************/
        
     public function PaytrailSuccess() {
        require_once(APPPATH.'third_party/Paytrail/Paytrail_Module_Rest.php');

        $module = new Paytrail_Module_Rest(13466, "6pKF4jkv97zmqBJ3ZL8gUw5DfT2NMQ"); 
        if ($module->confirmPayment($_GET["ORDER_NUMBER"], $_GET["TIMESTAMP"], $_GET["PAID"], $_GET["METHOD"], $_GET["RETURN_AUTHCODE"])) {
    // Payment receipt is valid
    // If needed, the used payment method can be found from the variable $_GET["METHOD"]
    // and order number for the payment from the variable $_GET["ORDER_NUMBER"]
        
//             $orderNumber = json_decode($_GET["ORDER_NUMBER"], true);
//            $this->adduserCash();
            if($this->session->userdata('type_person') == 2){
                redirect('welcome/marketplace');
            }else{
                redirect('welcome/borrower_request');
            }
            
               
        }
        else {
            
        }
    }
    
    /*Paytrail failure return address **********************************************/
     public function PaytrailFailure() 
    {
        $resultArray = array(
            'result' => 'failed',
            'resultText' => 'Paying the invoice using Paytrail failed.'
        );
        
       
    }
    
    /* Paytrail notify callback address. This address is called from Paytrail's server once the payment,
     * has been booked by Paytrail. */
     public function PaytrailNotify($displayModalInfo = null) 
    {   
        require_once(APPPATH.'third_party/Paytrail/Paytrail_Module_Rest.php');

        $module = new Paytrail_Module_Rest(13466, "6pKF4jkv97zmqBJ3ZL8gUw5DfT2NMQ"); 
        if ($module->confirmPayment($_GET["ORDER_NUMBER"], $_GET["TIMESTAMP"], $_GET["PAID"], $_GET["METHOD"], $_GET["RETURN_AUTHCODE"])) {
            // Maksukuittaus on validi
            // Maksussa käytetty maksutapa löytyy tarvittaessa muuttujasta $_GET["METHOD"]
            // ja kuitattavan maksun tilausnumero muuttujasta $_GET["ORDER_NUMBER"]
            /* Let's return OK status code to the caller. */
            return http_response_code(200);
        }
        else {
            // Maksukuittaus ei ollut validi, mahdollinen huijausyritys
            /* Let's return error status code to the caller. */
            return http_response_code(400);
        }
    }
    
}