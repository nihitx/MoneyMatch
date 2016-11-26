<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
public function __construct(){
  parent::__construct();
  $this->load->library('javascript');
  $this->load->library('form_validation');
  $this->load->library('email');
  $this->load->library('session');
  
}
	
    public function index($openmodel = null)
    {
        $this->load->helper('form');
        $this->load->helper('url');
        $dataArray = array();
        if(!$openmodel){
            $dataArray['openmodel'] = 0;
        }else{
            $dataArray['openmodel'] = 1;
        }
        $this->load->view('header');
        $this->load->view('main_landing_view', $dataArray);
    }
    
    public function login(){
        $this->load->view('header');
        $this->load->view('login_view');
    }
    
    public function login_user(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[32]');

        if ($this->form_validation->run() == FALSE){
            $this->load->view('header');
            $this->load->view('login_view');
        }else{
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $this->load->model('user_model');
                $result =  $this->user_model->login_user($email, $password);
                $userinfo = json_decode(json_encode($result[0]), true);
                if($result){
                    $this->session->set_userdata(array(
                        'user_id' => $userinfo['ORIG_ID'],
                        'type_person'=>$userinfo['type_person'],
                            'loggedin' => true
                    ));
                    
                    if($userinfo['type_person'] == 1){
                        $this->borrower_request();
                    }else{
                        $this->marketplace();
                    }
                }else{
                    return $this->output->set_status_header('401', 'You are not registered');
                }
        }
    }
        
        
    public function registerUser(){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[new_users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('ssn', 'Ssn', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirm', 'required|matches[password]');


        if ($this->form_validation->run() == FALSE){
            $dataArray = array();
            $dataArray['openmodel'] = 0;
            $this->load->view('header');
            $this->load->view('main_landing_view',$dataArray);
        
        }else{

            $data =  array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'password' => $this->input->post('password'),
            'ssn' => $this->input->post('ssn'),
            'currency' =>$this->input->post('currencybutton'),
             'type_person' => $this->input->post('type_person'),
            'ORIG_ID' => null
            );
            

            $this->load->model('user_model');
            $foo =  $this->user_model->storeRegisterInfo($data); 
            if(!$foo){
                return $this->output->set_status_header('401', 'Could not save user!!!');
            }else{
                $this->session->set_userdata(array(
                            'user_id' => $foo,
                            'type_person'=>$data['type_person'],
                            'loggedin' => true
                        ));
                if($data['type_person'] == 1){
                    $user_save = 1; 
                    $this->index($user_save);
                }else{
                    redirect('welcome/marketplace', 'refresh');
                }
            }
        }
    }
    
    
    public function marketplace(){
        if (!$this->session->userdata('user_id') && !$this->session->userdata('type_person') == 2 ){
         return $this->index();
        }
        $this->load->view('header');
        $this->load->view('marketplace_view');
        
        
    }
    
    
    public function uploadProfilePicture(){
//        if (!$this->session->userdata('user_id')){
//         return $this->index();
//        }
//        $userid = $this->session->userdata('user_id');
//        $data = json_decode(file_get_contents('php://input'), true);
        $fileCount = count($_FILES);
        return var_dump($fileCount);
        
    }
    
    public function borrower_request(){
        if (!$this->session->userdata('user_id')){
         return $this->index();
        }
        $this->load->view('header');
        $this->load->view('borrower_view');
    }
    
    public function saveuser_data( $salary, $job , $house, $iban ){
        if (!$this->session->userdata('user_id')){
         return $this->index();
        }
        $this->load->helper('url');
        $userid = $this->session->userdata('user_id');
        $data = array(
          'Owner' => $userid,
           'job' => $job,
            'salary' => $salary,
            'housing' => $house,
            'validation' => 0,
            'iban' => $iban,
            'points' => 0
        );
        $this->load->model('user_model');
        $foo =  $this->user_model->storeuserdata($data); 
        if(!$foo){
            return $this->output->set_status_header('401', 'Could not save user data!!!');
        
        }else{
            redirect('welcome/borrower_request', 'refresh');
        }
    }
    
    public function saveborrowrequest($amount, $interest, $loantime){
        if (!$this->session->userdata('user_id')){
         return $this->index();
        }
        $userid = $this->session->userdata('user_id');
        
        $dataArray = array(
            'Owner' => $userid,
            'Amount' => $amount,
            'Interest' => $interest,
            'Loantime' => $loantime,
            'status' =>0,
            'ORIG_ID' => null
        );
        
        $this->load->model('user_model');
        $foo =  $this->user_model->storeborrowrequest($dataArray); 
        if($foo){
          return  redirect('welcome/borrower_request', 'refresh');
        }else{
            $this->output->set_status_header('401', 'Request not understood as an Ajax request!');
        }
        
    }
    
    public function getBorrowerRequest(){
            $user = $this->session->userdata('user_id');
            $this->load->model('user_model');
            $result = $this->user_model->getUserBorrowRequest($user);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($result));
    }
    
    public function getBorrowerInformation(){
            $user = $this->session->userdata('user_id');
            $this->load->model('user_model');
            $result = $this->user_model->getBorrowerInformationfromdb($user);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($result));
    }
    
    public function getAllBorrowerForMatch(){
            $this->load->model('user_model');
            $result = $this->user_model->getBorrowerToMatch();
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($result));
    }
    
    public function investTheAmount($NewAmount , $Iban , $br_id , $borrower_owner){
        /* first updat borrow request table */
        $user = $this->session->userdata('user_id');
        $this->load->model('user_model');
        $result = $this->user_model->getBorrowRequestTable($br_id);
        $results = json_decode(json_encode($result[0]), true);
        
        /* update bororw request table with new information */
        $results['amount_got'] = $results['Amount'];
        $results['status'] = 1;
        
        $update = $this->user_model->updateBorrowRequestTable($results);
        if(!$update){
            $this->output->set_status_header('401', 'could not update');
        }
        /* now lets create the payment plan */
        $iSecsInDay = 86400;
        $iTotalDays = 30;
        $user_signup = time() + ($iSecsInDay * $iTotalDays);
        $duedate = date('Y-m-d', $user_signup);
        $paymentPlan = array(
            'borrower_request_id' => $br_id,
            'investor_id' => $user,
            'Amount' => $NewAmount,
            'duedate' => $duedate,
             'status' => 0, // not paid back
            'ORIG_ID' => null,
            'amount_paid_back' => null,
            'borrower_id' => $borrower_owner
        );
        
         $paymentplanORIG_ID =  $this->user_model->storePaymentPlan($paymentPlan); 
         if(!$paymentplanORIG_ID){
            $this->output->set_status_header('401', 'could not update');
        }
        $Iban = str_replace(' ', '', $Iban);
        $invoiceArray = array(
            'investor_id' => $user,
            'Amount' => $NewAmount,
            'duedate' => $duedate,
            'iban' => $Iban,
            'ORIG_ID' => null
        );
        
        $invoice_orig_id=  $this->user_model->storeInvoice($invoiceArray); 
         if(!$invoice_orig_id){
            $this->output->set_status_header('401', 'could not update');
        }
        
        redirect('welcome/marketplace', 'refresh');
    }
    
    
    public function getallinvestedto(){
            $user = $this->session->userdata('user_id');
            $this->load->model('user_model');
            $result = $this->user_model->getallinvestedtoborrower($user);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($result));
    }
    
    public function logout(){
        $this->session->set_userdata('logged_in', FALSE);
        $this->session->sess_destroy();
        $this->index();
    }
    
    
}
