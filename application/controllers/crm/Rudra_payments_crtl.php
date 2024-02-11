
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_payments_crtl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_payments';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model', 'crd');
        $this->load->model('rudra_payments_rudra_model', 'rudram');
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
	//Rudra_payments_crtl ROUTES
        $crud_master = $crm . "Rudra_payments_crtl/";
        $route['rudra_payments'] = $crud_master . 'index';
        $route['rudra_payments/index'] = $crud_master . 'index';
        $route['rudra_payments/list'] = $crud_master . 'list';
        $route['rudra_payments/post_actions/(:any)'] = $crud_master.'post_actions/$1';
     **************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Payments';
        $data['page_template'] = '_rudra_payments';
        $data['page_header'] = ' Payments';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }
    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $orderArray = array('id', 'fk_user_id', 'fk_booking_id', 'date_time', 'pay_type', 'paid', 'transaction_id', 'request', 'fk_bank_account_id', 'status', 'is_deleted',);
        $limit = html_escape($this->input->post('length'));
        $start = html_escape($this->input->post('start'));
        $order = '';
        $dir   = $this->input->post('order')[0]['dir'];
        $order   = $this->input->post('order')[0]['column'];
        $orderColumn = $orderArray[$order];
        $general_search = $this->input->post('search')['value'];
        $filter_data['general_search'] = $general_search;
        $totalData = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $totalData; //Initailly Total and Filtered count will be same
        $rescheck = '';
        $rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data, $rescheck, $this->full_table);
        $rows_count = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $rows_count;
        if (!empty($rows)) {
            $res_json = array();
            foreach ($rows as $row) {
                $actions_base_url = 'rudra_payments/post_actions';
                $actions_query_string = "?id= $row->id.'&target_table='.$row->id";
                $form_data_url = 'rudra_payments/post_actions';
                $action_url = 'rudra_payments/post_actions';
                $sucess_badge =  "class=\'badge badge-success\'";
                $danger_badge =  "class=\'badge badge-danger\'";
                $info_badge =  "class=\'badge badge-info\'";
                $warning_badge =  "class=\'badge badge-warning\'";

                $actions_button = "<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a>";
                $row->actions = $actions_button;

                //JOINS LOGIC

                $fk_user_id = $this->db->get_where('rudra_user', array('id' => $row->fk_user_id))->row();
                $row->fk_user_id = (!empty($fk_user_id) ? ($fk_user_id->name.' '.$fk_user_id->surname) : '--');
                $fk_booking_id = $this->db->get_where('rudra_booking', array('id' => $row->fk_booking_id))->row();
                $row->fk_booking_id = (!empty($fk_booking_id) ? ('#'.$fk_booking_id->booking_id) : '--');
                $fk_bank_account_id = $this->db->get_where('rudra_washer_bank_info', array('id' => $row->fk_bank_account_id))->row();
                $row->fk_bank_account_id = (!empty($fk_bank_account_id) ? $fk_bank_account_id->id : '--');

                $row->status = (isset($row->status) && $row->status != "") ? statusDropdown($id = $row->status) : "";
                $row->is_deleted = (isset($row->is_deleted) && $row->is_deleted != "") ? isDeletedDropdown($id = $row->is_deleted) : "";
                $row->pay_type = (isset($row->pay_type) && $row->pay_type != "") ? payType($id = $row->pay_type) : "";
                $data[] = $row;
            }
        } else {
            $data = array();
        }
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
            $html = $this->load->view("crm/forms/_ajax_rudra_payments_form", $data, TRUE);
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if ($action == "update_data") {
            if (!empty($_POST)) {
                $id = $_POST['id'];
                $updateArray =
                    array(
                        'id' => $this->input->post('id', true),
                        'fk_user_id' => $this->input->post('fk_user_id', true),
                        'fk_booking_id' => $this->input->post('fk_booking_id', true),
                        'date_time' => $this->input->post('date_time', true),
                        'pay_type' => $this->input->post('pay_type', true),
                        'paid' => $this->input->post('paid', true),
                        'transaction_id' => $this->input->post('transaction_id', true),
                        'request' => $this->input->post('request', true),
                        'fk_bank_account_id' => $this->input->post('fk_bank_account_id', true),
                        'status' => $this->input->post('status', true),
                        'is_deleted' => '0',
                    );

                $this->db->where('id', $id);
                $this->db->update($this->full_table, $updateArray);
            }
        }
        //Insert Method
        if ($action == "insert_data") {
            $id = 0;
            if (!empty($_POST)) {
                //Insert Codes goes here 
                $updateArray =
                    array(
                        'id' => $this->input->post('id', true),
                        'fk_user_id' => $this->input->post('fk_user_id', true),
                        'fk_booking_id' => $this->input->post('fk_booking_id', true),
                        'date_time' => $this->input->post('date_time', true),
                        'pay_type' => $this->input->post('pay_type', true),
                        'paid' => $this->input->post('paid', true),
                        'transaction_id' => $this->input->post('transaction_id', true),
                        'request' => $this->input->post('request', true),
                        'fk_bank_account_id' => $this->input->post('fk_bank_account_id', true),
                        'status' => $this->input->post('status', true),
                        'is_deleted' => '0',
                    );

                $this->db->insert($this->full_table, $updateArray);
                $id = $this->db->insert_id();
            }
        }

        // Export CSV Codes 
        if ($action == "export_data") {
            $header = array();
            foreach ($json_cols as $ck => $cv) {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_payments') . '_' . date('d-m-Y') . ".csv";
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


    public function payment_accept(){

        echo json_encode($_REQUEST, true);
    }

    public function payment_cancel(){
        
        echo json_encode($_REQUEST, true);
    }

    public function payment_callback(){

        echo json_encode($_REQUEST, true);
    }


    public function pay_nets(){
        $data['page_title'] = 'Checkout page';
        $this->load->view('crm/common_front/header',$data);
        $this->load->view("proceed-checkout");
        $this->load->view('crm/common_front/footer');

    }



    public function create_payment(){


        $payload = file_get_contents('payload.json');
        assert(json_decode($payload) && json_last_error() == JSON_ERROR_NONE);

        $ch = curl_init('https://api.dibspayment.eu/v1/payments');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
                'Content-Type: application/json',
                'Accept: application/json',
                // 'Authorization: test-secret-key-a40713b2b4394379bb039699cb77a41d',
                'Authorization: live-secret-key-4df1a783981c4c12b09155f640ae3e12'
            ));                                                
        $result = curl_exec($ch);
        echo($result);

        // $this->load->view('crm/common_front/header',$data);
        // $this->load->view("proceed-checkout");
        // $this->load->view('crm/common_front/footer');



    }


    public function checkout(){
        $data['page_title'] = 'Checkout page';

        $this->load->view('crm/common_front/header',$data);
        $this->load->view("checkout");
        $this->load->view('crm/common_front/footer');

    }


    public function payment_completed(){

        $data['page_title'] = 'Payment completed';

        $this->load->view('crm/common_front/header',$data);
        $this->load->view("completed");
        $this->load->view('crm/common_front/footer');

    }

    public function payment_failed(){

        $data['page_title'] = 'Payment failed';

        $this->load->view('crm/common_front/header',$data);
        $this->load->view("404");
        $this->load->view('crm/common_front/footer');

    }

    public function payment_success(){


        $transaction_id = $this->input->post('paymentId', true);


        $updateArray = array(
                        'status' => '1',
                        );

        $this->db->where('transaction_id', $transaction_id);
        $this->db->update("$this->full_table", $updateArray);


    }


    public function mobilepay_callback(){
        echo "Go back to Monkyshine app to check payment status.";
    }

}
