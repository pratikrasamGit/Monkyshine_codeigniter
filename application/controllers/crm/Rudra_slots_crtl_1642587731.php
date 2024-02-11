
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_slots_crtl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_slots';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model', 'crd');
        $this->load->model('rudra_slots_rudra_model', 'rudram');
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
	//Rudra_slots_crtl ROUTES
        $crud_master = $crm . "Rudra_slots_crtl/";
        $route['rudra_slots'] = $crud_master . 'index';
        $route['rudra_slots/index'] = $crud_master . 'index';
        $route['rudra_slots/list'] = $crud_master . 'list';
        $route['rudra_slots/post_actions/(:any)'] = $crud_master.'post_actions/$1';
     **************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Slots';
        $data['page_template'] = '_rudra_slots';
        $data['page_header'] = ' Slots';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }
    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $orderArray = array('id', 'slot_date', 'start_time', 'end_time', 'no_of_service', 'is_deleted',);
        $limit = html_escape($this->input->post('length'));
        $start = html_escape($this->input->post('start'));
        $order = '';
        $dir   = $this->input->post('order')[0]['dir'];
        $order   = $this->input->post('order')[0]['column'];
        $orderColumn = $orderArray[$order];
        $general_search = $this->input->post('search')['value'];
        $filter_data['general_search'] = $general_search;
        // $totalData = $this->rudram->count_table_data($filter_data,$this->full_table);
        // $totalFiltered = $totalData; //Initailly Total and Filtered count will be same
        $rescheck = '';
        $totalData = 7;
        $rows = array();
        $date = date('Y-m-d');
        $rows = array();
        for ($i = 1; $i <= 7; $i++) {

            $actions_base_url = 'rudra_slots/post_actions';
            $actions_query_string = "?id= $date.'&target_table='.$date";
            $form_data_url = 'rudra_slots/post_actions';
            $action_url = 'rudra_slots/post_actions';
            $sucess_badge =  "class=\'badge badge-success\'";
            $danger_badge =  "class=\'badge badge-danger\'";
            $info_badge =  "class=\'badge badge-info\'";
            $warning_badge =  "class=\'badge badge-warning\'";

            $actions_button = "<a id='edt$date' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$date','$action_url/update_data','md','Update Details')\" >Edit</a>";
            $no_of_services = $this->rudram->get_no_of_services($date);
            array_push($rows, array("slot_date" => date('d-m-Y', strtotime($date)), "no_of_service" => $no_of_services, "actions" => $actions_button));

            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }

        // $rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data,$rescheck,$this->full_table);
        // $rows_count = $this->rudram->count_table_data($filter_data,$this->full_table);
        $totalFiltered = 7;
        // if(!empty($rows))
        // {
        // 	$res_json = array();
        foreach ($rows as $row) {
            // 		$actions_base_url = 'rudra_slots/post_actions';
            // 		$actions_query_string = "?id= $row->id.'&target_table='.$row->id";
            // 		$form_data_url = 'rudra_slots/post_actions';
            // 		$action_url = 'rudra_slots/post_actions';
            // 		$sucess_badge =  "class=\'badge badge-success\'";
            // 		$danger_badge =  "class=\'badge badge-danger\'";
            // 		$info_badge =  "class=\'badge badge-info\'";
            // 		$warning_badge =  "class=\'badge badge-warning\'";

            // 		$actions_button ="<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a>";
            // 		$row->actions = $actions_button;

            // 		//JOINS LOGIC

            $data[] = $row;
        }
        // }
        // else
        // {
        // 	$data = array();
        // }
        $json_data = array(
            'draw'           => intval($this->input->post('draw')),
            'recordsTotal'    => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
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
        $col_json = $this->db->get_where($this->bdp . 'crud_master_tables', array('tbl_name' => $this->full_table))->row();
        $data['col_json'] = $col_json;
        $json_cols = json_decode($data['col_json']->col_strc);

        $data['slot_date'] = (isset($_GET['id']) ? $_GET['id'] : 0);
        //Get Data Methods
        if ($action == "get_data") {
            $data['id'] = $id;
            foreach ($json_cols as $ck => $cv) {
                if ($cv->form_type == 'ddl') {
                    $data[$cv->col_name] = $cv->ddl_options;
                }

                //Foreign Key Logics
                if ($cv->f_key) {
                    $ref_table_name = $cv->ref_table;
                    $data[$cv->col_name] = $this->db->get($ref_table_name)->result();
                }
            }

            $data['form_data'] = $this->db->get_where($this->full_table, array('id' => $id))->row();
            $html = $this->load->view("crm/forms/_ajax_rudra_slots_form", $data, TRUE);
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if ($action == "update_data") {
            // echo "<pre>";
            // print_r($_POST);exit;
            if (!empty($_POST)) {
                for ($i = 1; $i <= 6; $i++) {
                    $slot_date = $_POST['slot_date'];
                    $start_time = $_POST['start_time' . $i];
                    $end_time = $_POST['end_time' . $i];

                    $updateArray =
                        array(
                            'slot_date' => $this->input->post('slot_date', true),
                            'start_time' => $this->input->post('start_time' . $i),
                            'end_time' => $this->input->post('end_time' . $i),
                            'no_of_service' => $this->input->post('no_of_service' . $i),
                        );

                    $chk_data = $this->db->get_where("$this->full_table", array('slot_date' => $slot_date, 'start_time' => $start_time, 'end_time' => $end_time))->row();
                    if (!empty($chk_data)) {

                        $this->db->where('slot_date', $slot_date);
                        $this->db->where('start_time', $start_time);
                        $this->db->where('end_time', $end_time);
                        $this->db->update($this->full_table, $updateArray);
                    } else {
                        if ($_POST['no_of_service' . $i] > 0) {
                            $this->db->insert($this->full_table, $updateArray);
                            $id = $this->db->insert_id();
                        }
                    }
                }
            }
        }


        // Export CSV Codes 
        if ($action == "export_data") {
            $header = array();
            foreach ($json_cols as $ck => $cv) {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_slots') . '_' . date('d-m-Y') . ".csv";
            $fp = fopen('php://output', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            fputcsv($fp, $header);
            $result_set = $this->db->get($this->full_table)->result();
            foreach ($result_set as $k) {
                $row = array();
                foreach ($json_cols as $ck => $cv) {
                    $cl = $cv->col_name;
                    $row[] = $k->$cl;
                }
                fputcsv($fp, $row);
            }
        }
        echo json_encode($this->return_data);
        exit;
    }
}
