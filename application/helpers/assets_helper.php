<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function assets_url() {
    return base_url();
}
 function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
public function dropdown($field1,$field2,$table,$condition,$name,$class,$class_option)
	{
		$str = "<select name='$name' id='$name' class='$class' required='required'>";
		
		$ans = $this->select("$field1,$field2",$table,$condition);
		
		//pre($ans);
		
		if($ans == "no")
		{
			$str.="<option value='' class='$class_option'>No Records</option>";
		}
		else
		{
			//pre($ans);
			$str.="<option value='' class='$class_option'>Select</option>";
			foreach($ans as $key=>$val)
			{
				
				$data1 = $val->$field2;
				$data2 = $val->$field1;
				$str.="<option value='$data2' class='$class_option'>$data1</option>";
			}
		}
		
		$str.="</select>";
		
		echo $str;
	}
	
	public function select($field,$table,$condition)
    {
		$sql = "select $field from $table where $condition";
		//echo $sql;
		$ans = $this->CI->db->query($sql);
		
		if($ans->num_rows==0)
		{
			return "no";
		}
		else
		{
			$fans = $ans->result();
			//pre($fans);
			return $fans;
		}
    }
/* End of file assets_helper.php */
/* Location: ./application/helpers/assets_helper.php */

?>