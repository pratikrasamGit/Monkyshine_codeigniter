<?php

function statusDropdown($id = "")
{
    $arr = ['0' => "Inactive", '1' => "Active"];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function isDeletedDropdown($id = "")
{
    $arr = ['0' => "Active", '1' => "Deleted"];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function washStatus($id = "")
{
    $arr = ['1' => "Pending", '2' => "Accepted", '3' => "Completed"];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function payType($id = "")
{
    $arr = ['0' => 'NA', '1' => 'Credit Card', '2' => 'Mobile Pay'];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function CarSizeType($id = "")
{
    $arr = ['1' => 'Small', '2' => 'Medium', '3' => 'Large', '4' => 'Mikro'];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function userType($id = "")
{
    $arr = ['1' => 'User', '2' => 'Washer'];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

function userList($type = "", $id = "")
{
    $ci = &get_instance();

    $where = ($type != "") ? ['user_type' => $type] : [];
    if ($id != "")
        $where = array_merge($where, ['id' => $id]);

    $ci->db->where($where);
    $result = $ci->db->get('rudra_user');

    $users = [];
    if ($result->num_rows() > 0) {
        if ($id != "") {
            $u = $result->row();
            $users = ['id' => $u->id, 'name' => $u->name, 'surname' => $u->surname];
        } else {
            foreach ($result->result() as $key => $u) {
                if($u->name!=''){
                    $users[] = ['id' => $u->id, 'name' => $u->name, 'surname' => $u->surname];
                }
            }
        }
    }

    return $users;
}

function washTypesList($id = "")
{
    $ci = &get_instance();

    if ($id != "")
        $ci->db->where(['id' => $id]);

    $result = $ci->db->get('rudra_wash_types');

    $wash_types = [];
    if ($result->num_rows() > 0) {
        if ($id != "") {
            $wt = $result->row();
            $wash_types = ['id' => $wt->id, 'name' => $wt->wash_name, 'car_size' => $wt->car_size];
        } else {
            foreach ($result->result() as $key => $wt) {
                $wash_types[] = ['id' => $wt->id, 'name' => $wt->wash_name, 'car_size' => $wt->car_size];
            }
        }
    }

    return $wash_types;
}

function bookingList($id = "")
{
    $ci = &get_instance();

    if ($id != "")
        $ci->db->where(['id' => $id]);

    $result = $ci->db->get('rudra_booking');

    $booking = [];
    if ($result->num_rows() > 0) {
        if ($id != "") {
            $wt = $result->row();
            $booking = ['id' => $wt->id, 'booking_id' => $wt->booking_id];
        } else {
            foreach ($result->result() as $key => $wt) {
                $booking[] = ['id' => $wt->id, 'booking_id' => $wt->booking_id];
            }
        }
    }

    return $booking;
}

function carNumbersList($id = "")
{
    $ci = &get_instance();

    if ($id != "")
        $ci->db->where(['id' => $id]);

    $result = $ci->db->get('rudra_user_registered_car_numbers');

    $carlist = [];
    if ($result->num_rows() > 0) {
        if ($id != "") {
            $wt = $result->row();
            $carlist = ['id' => $wt->id, 'registration_number' => $wt->registration_number];
        } else {
            foreach ($result->result() as $key => $wt) {
                $carlist[] = ['id' => $wt->id, 'registration_number' => $wt->registration_number];
            }
        }
    }

    return $carlist;
}

function refundStatusDropdown($id = "")
{
    $arr = ['0' => "No", '1' => "Yes"];

    return ($id != "" && isset($arr[$id])) ? $arr[$id] : $arr;
}

/* End of file dropdown.php */
