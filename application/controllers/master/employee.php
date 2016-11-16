<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_employee','record');
        $this->auth->restrict();
        $this->auth->menu(11);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_employee'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $m_employee_id      = addslashes($_POST['m_employee_id']);
        $m_employee_name    = addslashes($_POST['m_employee_name']);
        $m_employee_birth   = addslashes($_POST['m_employee_birth']);
        $m_employee_addr    = addslashes($_POST['m_employee_addr']);
        $m_employee_dept    = addslashes($_POST['m_employee_dept']);
        $m_employee_hired   = addslashes($_POST['m_employee_hired']);
        $m_employee_termit  = addslashes($_POST['m_employee_termit']);
        $m_employee_rank    = addslashes($_POST['m_employee_rank']);
        $m_employee_status  = addslashes($_POST['m_employee_status']);
        $m_employee_shuttle = addslashes($_POST['m_employee_shuttle']);
        
        echo $this->record->create($m_employee_id, $m_employee_name, $m_employee_birth, $m_employee_addr,
                                   $m_employee_dept, $m_employee_hired, $m_employee_termit, $m_employee_rank,
                                   $m_employee_status, $m_employee_shuttle);
    }     
    
    function update($m_employee_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_employee_name    = addslashes($_POST['m_employee_name']);
        $m_employee_birth   = addslashes($_POST['m_employee_birth']);
        $m_employee_addr    = addslashes($_POST['m_employee_addr']);
        $m_employee_dept    = addslashes($_POST['m_employee_dept']);
        $m_employee_hired   = addslashes($_POST['m_employee_hired']);
        $m_employee_termit  = addslashes($_POST['m_employee_termit']);
        $m_employee_rank    = addslashes($_POST['m_employee_rank']);
        $m_employee_status  = addslashes($_POST['m_employee_status']);
        $m_employee_shuttle = addslashes($_POST['m_employee_shuttle']);
        
        echo $this->record->update($m_employee_id, $m_employee_name, $m_employee_birth, $m_employee_addr,
                                   $m_employee_dept, $m_employee_hired, $m_employee_termit, $m_employee_rank,
                                   $m_employee_status, $m_employee_shuttle);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_employee_id = addslashes($_POST['m_employee_id']);
        
        echo $this->record->delete($m_employee_id);
    }
    
    function getDept() {
        echo $this->record->getDept();
    }
    
    function getRank() {
        echo $this->record->getRank();
    }
    
    function getStatus() {
        echo $this->record->getStatus();
    }
    
    function getShuttle() {
        echo $this->record->getShuttle();
    }
}

/* End of file employee.php */
/* Location: ./application/controllers/master/employee.php */