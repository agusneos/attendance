<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_employee extends CI_Model
{    
    static $table1 = 'm_employee';
    static $table2 = 'm_dept';
    static $table3 = 'm_rank';
    static $table4 = 'm_status';
    static $table5 = 'm_shuttle';
     
    public function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index(){
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'm_employee_name';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } 
                }
            }
	}
        
        $this->db->join(self::$table2, 'm_employee_dept=m_dept_id', 'left')
                 ->join(self::$table3, 'm_employee_rank=m_rank_id', 'left')
                 ->join(self::$table4, 'm_employee_status=m_status_id', 'left')
                 ->join(self::$table5, 'm_employee_shuttle=m_shuttle_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $total  = $this->db->count_all_results(self::$table1);
        
        $this->db->join(self::$table2, 'm_employee_dept=m_dept_id', 'left')
                 ->join(self::$table3, 'm_employee_rank=m_rank_id', 'left')
                 ->join(self::$table4, 'm_employee_status=m_status_id', 'left')
                 ->join(self::$table5, 'm_employee_shuttle=m_shuttle_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }   
        
    function create($m_employee_id, $m_employee_name, $m_employee_birth, $m_employee_addr,
                    $m_employee_dept, $m_employee_hired, $m_employee_termit, $m_employee_rank,
                    $m_employee_status, $m_employee_shuttle) {
        $query = $this->db->insert(self::$table1,array(
            'm_employee_id'         => $m_employee_id,
            'm_employee_name'       => $m_employee_name,
            'm_employee_birth'      => $m_employee_birth,
            'm_employee_addr'       => $m_employee_addr,
            'm_employee_dept'       => $m_employee_dept,
            'm_employee_hired'      => $m_employee_hired,
            'm_employee_termit'     => $m_employee_termit,
            'm_employee_rank'       => $m_employee_rank,
            'm_employee_status'     => $m_employee_status,
            'm_employee_shuttle'    => $m_employee_shuttle
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }        
    }
    
    function update($m_employee_id, $m_employee_name, $m_employee_birth, $m_employee_addr,
                    $m_employee_dept, $m_employee_hired, $m_employee_termit, $m_employee_rank,
                    $m_employee_status, $m_employee_shuttle){
        $this->db->where('m_employee_id', $m_employee_id);
        $query = $this->db->update(self::$table1,array(
            'm_employee_name'       => $m_employee_name,
            'm_employee_birth'      => $m_employee_birth,
            'm_employee_addr'       => $m_employee_addr,
            'm_employee_dept'       => $m_employee_dept,
            'm_employee_hired'      => $m_employee_hired,
            'm_employee_termit'     => $m_employee_termit,
            'm_employee_rank'       => $m_employee_rank,
            'm_employee_status'     => $m_employee_status,
            'm_employee_shuttle'    => $m_employee_shuttle
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function delete($m_employee_id){
        $query = $this->db->delete(self::$table1, array('m_employee_id' => $m_employee_id));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getDept(){
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getRank(){
        $query  = $this->db->get(self::$table3);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getStatus(){
        $query  = $this->db->get(self::$table4);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getShuttle(){
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
}

/* End of file m_employee.php */
/* Location: ./application/models/master/m_employee.php */