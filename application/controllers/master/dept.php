<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dept extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_dept','record');
        $this->auth->restrict();
        $this->auth->menu(12);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_dept'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_dept_name    = addslashes($_POST['m_dept_name']);
        
        echo $this->record->create($m_dept_name);
    }     
    
    function update($m_dept_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_dept_name    = addslashes($_POST['m_dept_name']);
        
        echo $this->record->update($m_dept_id, $m_dept_name);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_dept_id = addslashes($_POST['m_dept_id']);
        
        echo $this->record->delete($m_dept_id);
    }
    
}

/* End of file dept.php */
/* Location: ./application/controllers/master/dept.php */