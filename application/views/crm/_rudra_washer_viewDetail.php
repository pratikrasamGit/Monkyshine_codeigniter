<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">User Information</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>User Type</td>
                            <td><?php  if($form_data->user_type=='1'){ echo "User"; }else{ echo "Washer"; } ?></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?php echo (isset($form_data->name) && $form_data->name != "") ? $form_data->name : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Surname</td>
                            <td><?php echo (isset($form_data->surname) && $form_data->surname != "") ? $form_data->surname : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?php echo (isset($form_data->email) && $form_data->email != "") ? $form_data->email : ""; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Contact Email</td>
                            <td><?php echo (isset($form_data->contact_email) && $form_data->contact_email != "") ? $form_data->contact_email : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo (isset($form_data->address) && $form_data->address != "") ? $form_data->address : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Zipcode</td>
                            <td><?php echo (isset($form_data->zipcode) && $form_data->zipcode != "") ? $form_data->zipcode : ""; ?></td>
                        </tr> 
                        <tr>
                            <td>City</td>
                            <td><?php echo (isset($form_data->city) && $form_data->city != "") ? $form_data->city : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?php if($form_data->status=='1'){ echo "Active"; }else{ echo "Inactive"; } ?></td>
                        </tr>
                        <tr>
                            <td>Is Deleted</td>
                            <td><?php if($form_data->is_deleted=='1'){ echo "Deleted"; }else{ echo "Active"; } ?></td>
                        </tr>
                       

                    </tbody>
                </table>
            </div>
        </div>


        <div class="card" style="max-height: 500px;">
            <img class="card-img-top" src="holder.js/100px180/" alt="">
            <div class="card-body" style="overflow-y: scroll;">
                <h4 class="card-title text-center">Bookings</h4>
                <table class="table">
                    <tbody>
                        <?php foreach($booking_data as $value){ ?>
                            <tr>
                                <td><a href="<?=base_url()?>rudra_booking/view/<?=$value->id?>" target="_blank"><?=$value->booking_id?></a></td>
                                <td><?=date('F d, Y', strtotime($value->wash_date))." ".date('h:i A', strtotime($value->wash_time))?></td>
                                <td><?=washStatus($id = $value->wash_status)?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
    <div class="col-md-4">

        <div class="card" style="overflow-x: auto;">
            <img class="card-img-top" src="holder.js/100px180/" alt="">
            <div class="card-body" >
                <h4 class="card-title text-center">Bank Information</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Registration Number</td>
                            <td><?php echo (isset($bank_data->registration_number) && $bank_data->registration_number != "") ? $bank_data->registration_number : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td><?php echo (isset($bank_data->account_number) && $bank_data->account_number != "") ? $bank_data->account_number : ""; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card" style="max-height: 668px;overflow-y:scroll">
            <img class="card-img-top" src="holder.js/100px180/" alt="">
            <div class="card-body">
                <h4 class="card-title text-center">Chats</h4>
                <table class="table" >
                    <tbody>
                        <?php foreach($chat_data as $chat){ ?>
                            <tr >
                                <td><?=date('d M Y H:i',strtotime($chat->created_at))?></td>
                                <td ><?=$chat->message?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>