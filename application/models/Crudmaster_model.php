<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudmaster_model extends CI_Model {

	// constructor
	function __construct()
	{
		parent::__construct();
		/*cache control*/
		$this->return_array = array();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->user_img_url = " (CASE WHEN A.social_image_change = 1 THEN CONCAT('".base_url()."uploads/user/',A.image) ELSE A.social_image END) as disp_image ";
		$this->user_img_thumb_url = " (CASE WHEN A.social_image_change = 1 THEN CONCAT('".base_url()."uploads/user/thumb/',A.image) ELSE A.social_image END) as image_thumb ";
		$this->gift_icon_url = " CONCAT('".base_url('uploads/gifts/')."',file) as gift_icon ";
	}

	
	
	public function get_all_tables($limit,$start,$order,$dir,$filter_data)
	{
		$table = $this->bdp.'crud_master_tables CRD';
		$mname = "rudra_buy_coin";
		$select = "SELECT *";
		$where = " WHERE 1 = 1 ";
		if($filter_data['general_search'] != '')
		{
			$where .= " AND CRD.tbl_name like '%".$filter_data['general_search']."%'";

		}
		
		$from = " FROM ".$table;

		 $query = $select.$from.$where." ORDER BY CRD.id DESC LIMIT ".$start." , ".$limit;
		$res = $this->db->query($query)->result();
		return $res;
	}
	//2101 0006 


	public function count_all_tables($filter_data)
	{
		$table = $this->bdp.'crud_master_tables CRD';
	
		$select = "SELECT COUNT(CRD.id) as paycnt ";
		$where = " WHERE 1 = 1 ";
		if($filter_data['general_search'] != '')
		{
			$where .= " AND CRD.tbl_name like '%".$filter_data['general_search']."%'";

		}
		
		$from = " FROM ".$table;
		$query = $select.$from.$where;
		$res = $this->db->query($query)->row();
		return $res->paycnt;
	}

    public function crud_all_data($limit,$start,$order,$dir,$filter_data,$tbl_data,$table_name)
	{
		//echo $tbl_data->col_strc.'kk';
		$rescheck = $this->db->get_where($this->bdp.'crud_master_tables',array('tbl_name' => $table_name))->row();
        $cols = json_decode($rescheck->col_strc);
		//print_r($filter_data);
		$table = $this->bdp.$table_name.' TBL';
		$select = "SELECT *";
		$where = " WHERE 1 = 1 ";
        if(!empty($cols))
        {
			$where .= " AND (  ";
			$sep = '';
            foreach($cols as $v => $cls )
            {
                if(!$cls->f_key)
                {
                    
                    $where .=  $sep."   TBL.".$cls->col_name." LIKE '%".$filter_data['general_search'] ."%' ";
					$sep = " OR ";
                }

                

            }
			$where .= " ) ";

        }
		
		$from = " FROM ".$table;
		$order_by = ($order == '' ? '' : ' ORDER BY '.$order." ".$dir);
		$query = $select.$from.$where.$order_by."  LIMIT ".$start." , ".$limit;
		$res = $this->db->query($query)->result();
		return $res;
	}
	//2101 0006 


	public function crud_count_all_data($filter_data,$table_name)
	{
		$table = $this->bdp.$table_name.' TBL';
		$rescheck = $this->db->get_where($this->bdp.'crud_master_tables',array('tbl_name' => $table_name))->row();
        $cols = json_decode($rescheck->col_strc);
	
		$select = "SELECT COUNT(*) as paycnt ";
		$where = " WHERE 1 = 1 ";

		if(!empty($cols))
        {
			$where .= " AND (  ";
			$sep = '';
            foreach($cols as $v => $cls )
            {
                if(!$cls->f_key)
                {
                    
                    $where .=  $sep."   TBL.".$cls->col_name." LIKE '%".$filter_data['general_search'] ."%' ";
					$sep = " OR ";
                }

                

            }
			$where .= " ) ";

        }
		
		$from = " FROM ".$table;
	     $query = $select.$from.$where;
		$res = $this->db->query($query)->row();
		return $res->paycnt;
	}


}
