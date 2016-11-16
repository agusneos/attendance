<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_status','record');
        $this->auth->restrict();
        $this->auth->menu(14);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_status'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_status_name    = addslashes($_POST['m_status_name']);
        
        echo $this->record->create($m_status_name);
    }     
    
    function update($m_status_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_status_name    = addslashes($_POST['m_status_name']);
        
        echo $this->record->update($m_status_id, $m_status_name);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_status_id = addslashes($_POST['m_status_id']);
        
        echo $this->record->delete($m_status_id);
    }
    
}

/* End of file status.php */
/* Location: ./application/controllers/master/status.php */