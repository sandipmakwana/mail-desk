<?php
class User_model extends CI_Model {
	var $table = 'emp_details';
	var $column_order = array(null, 'emp_firstname','emp_email','emp_role','emp_status'); //set column field database for datatable orderable
	var $column_search = array('emp_firstname','emp_email','emp_role','emp_status'); //set column field database for datatable searchable
	var $order = array('emp_id' => 'asc'); // default order
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	private function _get_datatables_query()
			{

				$this->db->from($this->table);

				$i = 0;

				foreach ($this->column_search as $item) // loop column
				{
					if($_POST['search']['value']) // if datatable send POST for search
					{

						if($i===0) // first loop
						{
							$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
							$this->db->like($item, $_POST['search']['value']);
						}
						else
						{
							$this->db->or_like($item, $_POST['search']['value']);
						}

						if(count($this->column_search) - 1 == $i) //last loop
							$this->db->group_end(); //close bracket
					}
					$i++;
				}

				if(isset($_POST['order'])) // here order processing
				{
					$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
				}
				else if(isset($this->order))
				{
					$order = $this->order;
					$this->db->order_by(key($order), $order[key($order)]);
				}
			}

			function get_datatables()
			{
				$this->_get_datatables_query();
				if($_POST['length'] != -1)
				$this->db->limit($_POST['length'], $_POST['start']);
				$query = $this->db->get();
				return $query->result();
			}

			function count_filtered()
			{
				$this->_get_datatables_query();
				$query = $this->db->get();
				return $query->num_rows();
			}

			public function count_all()
			{
				$this->db->from($this->table);
				return $this->db->count_all_results();
		}
	 public function duplicate_data($val,$id){

        $query=$this->db->query("SELECT * FROM emp_details where emp_username = '".$val."' and emp_id != $id");
       // echo $query->num_rows();
       // exit;
       return $query->num_rows();
   	 }
     public function get_maxid(){

	        $query=$this->db->query("SELECT * FROM emp_details order by emp_id desc limit 1");
	        $results = $query->result_array();
	       // print_r($results);
	        if(isset($results))
	        {
	         foreach($results as $data)
	              $lastid = $data['emp_id'];
	        }
	        else
	        	$lastid = 1;
	         return $lastid;
    }


	public function record_count() {
        return $this->db->count_all("emp_details");
    }

    public function fetch_users($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("emp_details");

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }


/**************************  START INSERT QUERY ***************/
    public function insert_data($data){
        $this->db->insert('emp_details', $data);
         $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**************************  END INSERT QUERY ****************/


    /*************  START SELECT or VIEW ALL QUERY ***************/
    public function view_data(){

        $query=$this->db->query("SELECT ud.*
                                 FROM emp_details ud
                                 ORDER BY ud.emp_id ASC");
        return $query->result_array();
    }
    /***************  END SELECT or VIEW ALL QUERY ***************/


    /*************  START EDIT PARTICULER DATA QUERY *************/
    public function edit_data($id){
        $query=$this->db->query("SELECT ud.*
                                 FROM emp_details ud
                                 WHERE ud.emp_id = $id");
        return $query->result_array();
    }
    /*************  END EDIT PARTICULER DATA QUERY ***************/

      public function search_member($nm){
	    	$query = $this->db->get("emp_details");
	       	 $this->db->where("emp_firstname like '$nm%'");
	        return $query->num_rows();
   }

   public function get_employees($emp_id, $keyword = '', $is_ldap = 0){

		$this->db->where("emp_id != '$emp_id'");
	   // $this->db->where("emp_role = 'Employee'");
	    $this->db->where("emp_status = 'Active'");
	     $this->db->where("is_ldap = '".$is_ldap."'");

		if($keyword){
			 $this->db->where("(emp_username like '$keyword%' OR emp_firstname like '$keyword%' OR emp_lastname like '$keyword%')");
		}
		else{
			 $this->db->limit(5, 0);
		}
        $query = $this->db->get("emp_details");

       // echo $query->num_rows();
       // exit;
       return $query->result_array();
   	 }
   	 public function get_employees_name($emp_id){

	 		$this->db->where("emp_id = '$emp_id'");
	 	   // $this->db->where("emp_role = 'Employee'");
	 	    $this->db->where("emp_status = 'Active'");



	         $query = $this->db->get("emp_details");

	        // echo $query->num_rows();
	        // exit;
	        return $query->result_array();
   	 }

	public function is_employee_exist($emp_email, $is_ldap = 1){
		$this->db->where("emp_email = '$emp_email'");
 	    $this->db->where("is_ldap = '$is_ldap'");


         $query = $this->db->get("emp_details");

        // echo "QUERY".$this->db->last_query();die;

        // echo $query->num_rows();
        // exit;
        return $query->result_array();
	}
}




?>