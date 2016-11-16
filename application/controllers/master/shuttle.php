<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shuttle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_shuttle','record');
        $this->auth->restrict();
        $this->auth->menu(15);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_shuttle'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_shuttle_name    = addslashes($_POST['m_shuttle_name']);
        
        echo $this->record->create($m_shuttle_name);
    }     
    
    function update($m_shuttle_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_shuttle_name    = addslashes($_POST['m_shuttle_name']);
        
        echo $this->record->update($m_shuttle_id, $m_shuttle_name);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_shuttle_id = addslashes($_POST['m_shuttle_id']);
        
        echo $this->record->delete($m_shuttle_id);
    }
    
}

/* End of file shuttle.php */
/* Location: ./application/controllers/master/shuttle.php */