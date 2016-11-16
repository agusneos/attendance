<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rank extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_rank','record');
        $this->auth->restrict();
        $this->auth->menu(13);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_rank'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_rank_name    = addslashes($_POST['m_rank_name']);
        
        echo $this->record->create($m_rank_name);
    }     
    
    function update($m_rank_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_rank_name    = addslashes($_POST['m_rank_name']);
        
        echo $this->record->update($m_rank_id, $m_rank_name);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_rank_id = addslashes($_POST['m_rank_id']);
        
        echo $this->record->delete($m_rank_id);
    }
    
}

/* End of file rank.php */
/* Location: ./application/controllers/master/rank.php */