<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
    
    /**
	 * Constructor for discovery
	 * 
	 */
	function __construct(){
		parent::__construct();		
	}
        
        
        
        public function storeRegisterInfo($data) 
        {
            /* $this->load->database(); */
            
            $data['INSERT_DT'] = date("Y-m-d H:i:s");
            $this->db->set($data);
                
            $this->db->insert('new_users');
            /* Checking possible insert error */
            if(!$this->db->affected_rows() > 0)
            {    
                return false;
            }
                
            $id = $this->db->insert_id();
                
            /* if ORIG_ID parameter has no value, we have to update the just inserted
             * invoice row to have value in it's ORIG_ID column.
             */ 
            if ($data['ORIG_ID'] == null) {          
                $updatedata = array(
                    'ORIG_ID' => $id
                );
                $this->db->where('ID', $id);
                    
                $this->db->update('new_users', $updatedata);
                /* Checking possible update error */
                if(!$this->db->affected_rows() > 0)
                {    
                    return false;
                }
                $data['ORIG_ID'] = $id;
            }          
            return $data['ORIG_ID'];	
	}
        
        public function storeuserdata($data){
            $data['INSERT_DT'] = date("Y-m-d H:i:s");
            $this->db->set($data);
                
            $this->db->insert('user_data');
            /* Checking possible insert error */
            if(!$this->db->affected_rows() > 0)
            {    
                return false;
            }else{
                return true;
            }
                
        }
        
        public function login_user($email , $password){
            $query = 'select a.ORIG_ID, a.type_person 
                     from new_users a
                     where a.email = ? and a.password = ? and UPDATE_DT is null';
                $query = $this->db->query($query, array($email, $password));
                if($query->num_rows() > 0){
                $result = $query->result();
                return $result;
                }      		
                return false; 
        }
        
        public function storeborrowrequest($data){
            /* $this->load->database(); */
            
            $data['INSERT_DT'] = date("Y-m-d H:i:s");
            $this->db->set($data);
                
            $this->db->insert('borrow_request');
            /* Checking possible insert error */
            if(!$this->db->affected_rows() > 0)
            {    
                return false;
            }
                
            $id = $this->db->insert_id();
                
            /* if ORIG_ID parameter has no value, we have to update the just inserted
             * invoice row to have value in it's ORIG_ID column.
             */ 
            if ($data['ORIG_ID'] == null) {          
                $updatedata = array(
                    'ORIG_ID' => $id
                );
                $this->db->where('id', $id);
                    
                $this->db->update('borrow_request', $updatedata);
                /* Checking possible update error */
                if(!$this->db->affected_rows() > 0)
                {    
                    return false;
                }
                $data['ORIG_ID'] = $id;
            }          
            return $data['ORIG_ID'];	
        }
        
    public function getUserBorrowRequest($userid){
        $query ='select a.*, c.iban, c.duedate
                FROM borrow_request a 
                left outer join payment_plan b
                on a.Owner = b.borrower_id and b.UPDATE_DT is null
                left outer join invoice c 
                on b.investor_id = c.investor_id and c.UPDATE_DT is null
                WHERE a.Owner = '.$userid.' and a.UPDATE_DT is null and a.status < 3';
        
        $query = $this->db->query($query);
                $result = $query->result();
		return $result;	
    }
    
    public function getBorrowerInformationfromdb($userid){
        $query ='select a.*, b.*
                FROM new_users a
                inner join user_data b
                on a.ORIG_ID = b.Owner 
                WHERE a.ORIG_ID= '.$userid.' and a.UPDATE_DT is null';
        
        $query = $this->db->query($query);
                $result = $query->result();
		return $result;	
    }
    
    public function getBorrowerToMatch(){
        $query = 'select a.name ,a.currency,a.ORIG_ID as Borrower_id,  b.job, b.salary, b.housing, b.validation, b.points, c.Amount , c.Interest, c.Loantime,c.ORIG_ID
                    from new_users a
                    left outer join user_data b
                    on a.ORIG_ID = b.Owner
                    left outer join borrow_request c
                    on a.ORIG_ID = c.Owner and c.UPDATE_DT is null
                    where a.type_person = 1  and c.Status = 0';
        $query = $this->db->query($query);
                $result = $query->result();
		return $result;	
    }
    
    public function getBorrowRequestTable($orig_id){
        $query ='select a.*
                FROM borrow_request a 
                WHERE a.ORIG_ID = '.$orig_id.' and UPDATE_DT is null';
        
        $query = $this->db->query($query);
                $result = $query->result();
		return $result;	
    }
    
    public function updateBorrowRequestTable($data) {
	 	/* $this->load->database(); */	
             	
                /* 1. update original row */
                $whrearray = array(
                        'id' => $data['id'], 
                        'UPDATE_DT' => null
                );    
                $updatedata = array(
			'UPDATE_DT' => date("Y-m-d H:i:s"),
		);   
                $this->db->where($whrearray); 
                $this->db->update('borrow_request', $updatedata);    
                /* Checking possible update error */
                if(!$this->db->affected_rows() > 0)
                {    
                    return false;
                }
                
                $data['id'] = null;
                /* 2. insert a new row to become a current row */
                $ID = $this->storeborrowrequest($data);
                
                /* Checking possible insert error */
                if(!$ID) {
                    return false;
                }
                else {
                    return $ID;
                }
        }
        
        public function storePaymentPlan($data){
            /* $this->load->database(); */
            
            $data['INSERT_DT'] = date("Y-m-d H:i:s");
            $this->db->set($data);
                
            $this->db->insert('payment_plan');
            /* Checking possible insert error */
            if(!$this->db->affected_rows() > 0)
            {    
                return false;
            }
                
            $id = $this->db->insert_id();
                
            /* if ORIG_ID parameter has no value, we have to update the just inserted
             * invoice row to have value in it's ORIG_ID column.
             */ 
            if ($data['ORIG_ID'] == null) {          
                $updatedata = array(
                    'ORIG_ID' => $id
                );
                $this->db->where('id', $id);
                    
                $this->db->update('payment_plan', $updatedata);
                /* Checking possible update error */
                if(!$this->db->affected_rows() > 0)
                {    
                    return false;
                }
                $data['ORIG_ID'] = $id;
            }          
            return $data['ORIG_ID'];	
        }
        
        public function storeInvoice($data){
            /* $this->load->database(); */
            
            $data['INSERT_DT'] = date("Y-m-d H:i:s");
            $this->db->set($data);
                
            $this->db->insert('invoice');
            /* Checking possible insert error */
            if(!$this->db->affected_rows() > 0)
            {    
                return false;
            }
                
            $id = $this->db->insert_id();
                
            /* if ORIG_ID parameter has no value, we have to update the just inserted
             * invoice row to have value in it's ORIG_ID column.
             */ 
            if ($data['ORIG_ID'] == null) {          
                $updatedata = array(
                    'ORIG_ID' => $id
                );
                $this->db->where('id', $id);
                    
                $this->db->update('invoice', $updatedata);
                /* Checking possible update error */
                if(!$this->db->affected_rows() > 0)
                {    
                    return false;
                }
                $data['ORIG_ID'] = $id;
            }          
            return $data['ORIG_ID'];
        }
            
        
    public function getallinvestedtoborrower($userid){
        $query = 'select a.amount, a.duedate , b.name , b.email, c.iban
                    from payment_plan a 
                    left outer join new_users b
                    on a.borrower_id = b.ORIG_ID  and b.UPDATE_DT is null
                    left outer join user_data c
                    on b.ORIG_ID = c.Owner and c.UPDATE_DT is null
                    where a.investor_id = '.$userid.' and a.UPDATE_DT is null';
        $query = $this->db->query($query);
                $result = $query->result();
		return $result;	
    }
    
}
