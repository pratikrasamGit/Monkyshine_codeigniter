
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_times_crtl extends CI_Controller
{                   
    public function __construct()
    {
        parent::__construct();                
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_times';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model','crd');
        $this->load->model('rudra_times_rudra_model','rudram');
        // Uncomment Following Codes for Session Check and change accordingly 
        /*
        if (!$this->session->userdata('rudra_admin_logged_in'))
        {			
            $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
            $return_url = uri_string();
            redirect(base_url("admin-login?req_uri=$return_url"), 'refresh');
        }
        else
        {
            $this->admin_id = $this->session->userdata('rudra_admin_id');
            $this->return_data = array('status' => false, 'msg' => 'Working', 'login' => true, 'data' => array());
        }
        */
    }
    /***********
	//Rudra_times_crtl ROUTES
        $crud_master = $crm . "Rudra_times_crtl/";
        $route['rudra_times'] = $crud_master . 'index';
        $route['rudra_times/index'] = $crud_master . 'index';
        $route['rudra_times/list'] = $crud_master . 'list';
        $route['rudra_times/post_actions/(:any)'] = $crud_master.'post_actions/$1';
	**************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Timeslots';                        
        $data['page_template'] = '_rudra_times';
        $data['page_header'] = ' Timeslots';
        $data['load_type'] = 'all';                
        $this->load->view('crm/template', $data);
    }
    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $times = array('10:00','11:00','12:00','13:00','14:00','15:00');


        for($i=date('Y-m-d') ; $i<= date('Y-m-d',strtotime('+7 days')) ; $i++){

            // echo $i.'<br>';
            $timeslots = $this->db->get_where($this->full_table,array('day'=>$i))->result(); 
            if( count($timeslots) < 1 ){
                for($j=1;$j<=6;$j++){


                        $updateArray = 
                        array(
                        'day' => $i,
                        'time' => $times[$j-1],
                        'total_slots' => 1,
                        'slots_taken' => 0
                        );
                        // print_r($updateArray);
                        $this->db->insert($this->full_table,$updateArray);

                }


            }

        }


        // exit;


        $orderArray = array(  'id', 'day', 'time', 'total_slots', 'slots_taken', 'status', 'is_deleted', );
		$limit = html_escape($this->input->post('length'));
		$start = html_escape($this->input->post('start'));
		$order = '';
		$dir   = $this->input->post('order')[0]['dir'];
		$order   = $this->input->post('order')[0]['column'];
		$orderColumn = $orderArray[$order];
		$general_search = $this->input->post('search')['value'];
		$filter_data['general_search'] = $general_search;
		$totalData = $this->rudram->count_table_data($filter_data,$this->full_table);
		$totalFiltered = $totalData; //Initailly Total and Filtered count will be same
			$rescheck = '';
		$rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data,$rescheck,$this->full_table);
		$rows_count = $this->rudram->count_table_data($filter_data,$this->full_table);
		$totalFiltered = $rows_count;
        $i=0;
        // echo "<pre>";print_r($rows);
		if(!empty($rows))
		{
            
			$res_json = array();
            $prevday = '';
			foreach ($rows as $row)
			{
                
				$actions_base_url = 'rudra_times/post_actions';
				$actions_query_string = "?id= $row->id.'&target_table='.$row->id";
				$form_data_url = 'rudra_times/post_actions';
				$action_url = 'rudra_times/post_actions';
				$sucess_badge =  "class=\'badge badge-success\'";
				$danger_badge =  "class=\'badge badge-danger\'";
				$info_badge =  "class=\'badge badge-info\'";
				$warning_badge =  "class=\'badge badge-warning\'";
	
				$actions_button ="<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a>";
				$row->actions = $actions_button;

                
                $currentday = $row->day;
				//JOINS LOGIC

                if($currentday != $prevday){
                    $i++;

                    $checkslots = $this->db->get_where($this->full_table,array('day'=>$row->day))->result(); 
                    $total_slots_taken = 0;
                    $total_slots = 0;
                    foreach($checkslots as $slot){
                        $total_slots = $total_slots + $slot->total_slots;
                        $total_slots_taken = $total_slots_taken + $slot->slots_taken;
                    }
                    $row->total_slots = $total_slots;
                    $row->total_slots_taken = $total_slots_taken.'/'.$total_slots;
                    $row->status = (isset($row->status) && $row->status != "") ? statusDropdown($id = $row->status) : "";
                    $row->is_deleted = (isset($row->is_deleted) && $row->is_deleted != "") ? isDeletedDropdown($id = $row->is_deleted) : "";
                
				    $data[] = $row;
                }
                $prevday = $row->day;
			}
		}
		else
		{
			$data = array();
		}
		$json_data = array
			(
			'draw'           => intval($this->input->post('draw')),
			// 'recordsTotal'    => intval($totalData),
			// 'recordsFiltered' => intval($totalFiltered),
            'recordsTotal'    => $i,
			'recordsFiltered' => $i,
			'data'           => $data
            );
		echo json_encode($json_data);
	
    }
    public function post_actions($param1)
    {
        // main index codes goes here
        $action = (isset($_GET['act']) ? $_GET['act'] : $param1);
		$id = (isset($_GET['id']) ? $_GET['id'] : 0);
		$this->return_data['status'] = true;
        $col_json = $this->db->get_where($this->bdp.'crud_master_tables',array('tbl_name'=>$this->full_table))->row(); 
        $data['col_json'] = $col_json;
        $json_cols = json_decode($data['col_json']->col_strc);

        $data['times'] = array('10:00','11:00','12:00','13:00','14:00','15:00');

 
        //Get Data Methods
        if($action == "get_data")
        {
            $data['id'] = $id;                           
            foreach($json_cols as $ck => $cv)
            {
                if($cv->form_type == 'ddl')
                {
                    $data[$cv->col_name] = $cv->ddl_options;
                }

                //Foreign Key Logics
                if($cv->f_key)
                {
                    $ref_table_name = $cv->ref_table;
                    $data[$cv->col_name] = $this->db->get($ref_table_name)->result();
                }
            }

            $data['form_data'] = $this->db->get_where($this->full_table,array('id'=>$id))->row(); 



            if($data['form_data']){

                $timeslots = $this->db->get_where($this->full_table,array('day'=>$data['form_data']->day))->result(); 

                // $bookings = $this->db->get_where('rudra_booking',array('wash_date'=>$data['form_data']->day))->result(); 

                $slots_data = [];
                foreach( $timeslots as $slot){

                    $bookings = $this->db->get_where('rudra_booking',array('wash_time'=>$slot->time,'wash_date'=>$data['form_data']->day,'status'=>'1','is_deleted'=>'0'))->result(); 

                    $slots_data[]=array(
                        'time' => $slot->time,
                        'slots_taken' => count($bookings),
                        'total_slots' => $slot->total_slots
                    );
                }
 

                $data['slots_data'] = $slots_data;
                $html = $this->load->view("crm/forms/_ajax_rudra_times_form_edit", $data, TRUE);
            }else{
                $html = $this->load->view("crm/forms/_ajax_rudra_times_form", $data, TRUE);
            }
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if($action == "update_data")
        {
            if(!empty($_POST))
            {
                $this->db->where('day',$this->input->post('day'));
                $this->db->delete("$this->full_table");

                // $id = $_POST['id'];
                // $updateArray = 
				// array(
				// //  'id' => $this->input->post('id',true),
				//  'day' => $this->input->post('day',true),
				//  'time' => $this->input->post('time',true),
				//  'total_slots' => $this->input->post('total_slots',true),
				//  'slots_taken' => $this->input->post('slots_taken',true),
				//  'status' => $this->input->post('status',true),
				//  'is_deleted' => $this->input->post('is_deleted',true),
				// );

                // $this->db->where('id',$id);
                // $this->db->update($this->full_table,$updateArray);

                $count = $this->input->post('count');

                for($i=1;$i<=$count;$i++){

                    if($this->input->post('time'.$i)){

                        $updateArray = 
                        array(
                        //  'id' => $this->input->post('id',true),
                        'day' => $this->input->post('day'),
                        'time' => $this->input->post('time'.$i),
                        'total_slots' => $this->input->post('total_slots'.$i),
                        'slots_taken' => $this->input->post('slots_taken'.$i),
                        // 'status' => $this->input->post('status'),
                        // 'is_deleted' => $this->input->post('is_deleted'),
                        );

                        $this->db->insert($this->full_table,$updateArray);
                    }

                }
                $id = $this->db->insert_id();

            }
            
        }
        //Insert Method
        if($action == "insert_data")
        {
            $id = 0;
            if(!empty($_POST))
            { 

                $count = $this->input->post('count');

                for($i=1;$i<=$count;$i++){

                    if($this->input->post('time'.$i)){

                        $updateArray = 
                        array(
                        //  'id' => $this->input->post('id',true),
                        'day' => $this->input->post('day'),
                        'time' => $this->input->post('time'.$i),
                        'total_slots' => $this->input->post('total_slots'.$i),
                        'slots_taken' => $this->input->post('slots_taken'.$i),
                        // 'status' => $this->input->post('status'),
                        // 'is_deleted' => $this->input->post('is_deleted'),
                        );

                        $this->db->insert($this->full_table,$updateArray);
                    }

                }
                //Insert Codes goes here 
                
                $id = $this->db->insert_id();
            }
            
            
            
        }

        // Export CSV Codes 
        if($action == "export_data")
        {
            $header = array();
            foreach($json_cols as $ck => $cv)
            {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_times').'_'.date('d-m-Y').".csv";
            $fp = fopen('php://output', 'w');                         
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            fputcsv($fp, $header);
            $result_set = $this->db->get($this->full_table)->result();
            foreach($result_set as $k)
            {  
                $row = array();
                foreach($json_cols as $ck => $cv)
                {
                    $cl = $cv->col_name;                                    
                    $row[] = $k->$cl;
                }                              
                fputcsv($fp,$row);
            }
        }
        echo json_encode($this->return_data);
		exit;
    }
                    
}