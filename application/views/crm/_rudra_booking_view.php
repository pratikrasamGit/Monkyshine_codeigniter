<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->

<?php $bookings = (isset($bookings[0]) && !empty($bookings[0])) ? $bookings[0] : []; ?>


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Booking Information</h4>
                <table class="table">
                    <tbody>
                        <!-- <tr>
                    <td></td>
                    <td><?php echo (isset($bookings['wash_id']) && $bookings['wash_id'] != "") ? $bookings['wash_id'] : ""; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo (isset($bookings['user_id']) && $bookings['user_id'] != "") ? $bookings['user_id'] : ""; ?></td>
                </tr> -->
                        <tr>
                            <td>Name</td>
                            <td><?php echo (isset($bookings['user_name']) && $bookings['user_name'] != "") ? $bookings['user_name'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><?php echo (isset($bookings['phone']) && $bookings['phone'] != "") ? $bookings['phone'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Booking ID</td>
                            <td><?php echo (isset($bookings['booking_id']) && $bookings['booking_id'] != "") ? $bookings['booking_id'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Zipcode</td>
                            <td><?php echo (isset($bookings['zipcode']) && $bookings['zipcode'] != "") ? $bookings['zipcode'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Latitude</td>
                            <td><?php echo (isset($bookings['latitude']) && $bookings['latitude'] != "") ? $bookings['latitude'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Longitude</td>
                            <td class="text-justify text-wrap"><?php echo (isset($bookings['longitude']) && $bookings['longitude'] != "") ? $bookings['longitude'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo (isset($bookings['address']) && $bookings['address'] != "") ? $bookings['address'] : ""; ?></td>
                        </tr>
                        <!-- <tr>
                            <td></td>
                            <td><?php echo (isset($bookings['wash_type_id']) && $bookings['wash_type_id'] != "") ? $bookings['wash_type_id'] : ""; ?></td>
                        </tr> -->
                        <tr>
                            <td>Wash Type</td>
                            <td><?php echo (isset($bookings['wash_type_name']) && $bookings['wash_type_name'] != "") ? $bookings['wash_type_name'] : ""; ?></td>
                        </tr>
                        <!-- <tr>
                            <td></td>
                            <td><?php echo (isset($bookings['extra_wash_type_ids']) && $bookings['extra_wash_type_ids'] != "") ? $bookings['extra_wash_type_ids'] : ""; ?></td>
                        </tr> -->
                        <!-- <tr>
                            <td></td>
                            <td><?php (isset($bookings['extra_wash_type_ids_array']) && $bookings['extra_wash_type_ids_array'] != "") ? $bookings['extra_wash_type_ids_array'] : ""; ?></td>
                        </tr> -->
                        <tr>
                            <td class="">Extra Washes</td>
                            <td>
                                <?php if (isset($bookings['extra_wash_type_ids_definition']) && is_array($bookings['extra_wash_type_ids_definition']) && !empty($bookings['extra_wash_type_ids_definition'])) { ?>
                                    <table class="table">
                                        <tbody>
                                            <?php foreach ($bookings['extra_wash_type_ids_definition'] as $key => $ewl) {
                                                if (isset($ewl['name']) && isset($ewl['amount'])) { ?>
                                                    <tr>
                                                        <td><?= $ewl['name']; ?></td>
                                                        <td><?= $ewl['amount']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                <?php } ?>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td></td>
                            <td><?php echo (isset($bookings['wash_date']) && $bookings['wash_date'] != "") ? $bookings['wash_date'] : ""; ?></td>
                        </tr> -->
                        <tr>
                            <td>Wash Date</td>
                            <td><?php echo (isset($bookings['wash_date_a']) && $bookings['wash_date_a'] != "") ? $bookings['wash_date_a'] : ""; ?></td>
                        </tr>
                        <!-- <tr>
                            <td>Wash Time</td>
                            <td><?php echo (isset($bookings['wash_time']) && $bookings['wash_time'] != "") ? $bookings['wash_time'] : ""; ?></td>
                        </tr> -->
                        <tr>
                            <td>Wash Time</td>
                            <td><?php echo (isset($bookings['wash_time_a']) && $bookings['wash_time_a'] != "") ? $bookings['wash_time_a'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Wash Notes</td>
                            <td><?php echo (isset($bookings['notes']) && $bookings['notes'] != "") ? $bookings['notes'] : "-"; ?></td>
                        </tr>
                        <!-- <tr>
                            <td></td>
                            <td><?php echo (isset($bookings['wash_status_id']) && $bookings['wash_status_id'] != "") ? $bookings['wash_status_id'] : ""; ?></td>
                        </tr> -->
                        <tr>
                            <td>Wash Status</td>
                            <td><?php echo (isset($bookings['wash_status_definition']) && $bookings['wash_status_definition'] != "") ? $bookings['wash_status_definition'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Wash Created At</td>
                            <td><?php echo (isset($bookings['created_at']) && $bookings['created_at'] != "") ? $bookings['created_at'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td>

                            </td>
                        </tr>

                        <tr>
                            <td>Quality Assurance Uploads</td>
                            <td>
                                <?php if (isset($bookings['qa_uploads']) && is_array($bookings['qa_uploads']) && !empty($bookings['qa_uploads'])) {
                                    foreach ($bookings['qa_uploads'] as $key => $qa) {
                                        if ($qa != "") { ?>
                                            <a href="<?= $qa; ?>" target="_blank"><img src="<?= $qa; ?>" class="rounded mx-auto d-block float-left" alt="Quality Assurance Images" style="width: 50px; height: 50px;"></a>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                            </td>
                        </tr>

                        <tr>
                            <td>Completed by</td>
                            <td><?php if($bookings['washerdata']){ ?>
                                Name : <?=$bookings['washerdata']->name.' '.$bookings['washerdata']->surname?><br>
                                ID : <?=$bookings['washerdata']->id?><br>
                                Date/time : <?=date('d-m-Y',strtotime($bookings['wash_date'])).' '.$bookings['wash_time_a']?>
                                <?php } ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <!-- <img class="card-img-top" src="holder.js/100px180/" alt=""> -->
            <div class="card-body">
                <h4 class="card-title">Payment Information</h4>
                <?php $payment = (isset($payments_history)  && !empty($payments_history)) ? $payments_history : []; ?>
                <table class="table ">
                    <tbody>
                    <tr>
                            <td>Wash Amount</td>
                            <td><?php echo (isset($bookings['wash_amount']) && $bookings['wash_amount'] != "") ? $bookings['wash_amount'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Extra Wash Amount</td>
                            <td><?php echo (isset($bookings['extra_wash_amount']) && $bookings['extra_wash_amount'] != "") ? $bookings['extra_wash_amount'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Extra Charge [zipcode based]</td>
                            <td><?php echo (isset($bookings['extra_charges']) && $bookings['extra_charges'] != "") ? $bookings['extra_charges'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Vat Percentage</td>
                            <td><?php echo (isset($bookings['vat_percentage']) && $bookings['vat_percentage'] != "") ? $bookings['vat_percentage'] . "%" : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Vat</td>
                            <td><?php echo (isset($bookings['vat']) && $bookings['vat'] != "") ? $bookings['vat'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Total Pay</td>
                            <td><?php echo (isset($bookings['total_pay']) && $bookings['total_pay'] != "") ? $bookings['total_pay'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Paid</td>
                            <td><?php echo (isset($payment['paid']) && $payment['paid'] != "") ? $payment['paid'] : "0.00"; ?></td>
                        </tr>
                        <tr> 
                            <td>Transaction ID</td>
                            <td><?php echo (isset($payment['transaction_id']) && $payment['transaction_id'] != "") ? $payment['transaction_id'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Paid Date</td>
                            <td><?php echo (isset($payment['created_at']) && $payment['created_at'] != "") ? date('d-m-Y', strtotime($payment['created_at'])) : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Paid Time</td>
                            <td><?php echo (isset($payment['created_at']) && $payment['created_at'] != "") ? date('h:i A', strtotime($payment['created_at'])) : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Payment Type</td>
                            <td><?php   if(isset($payment['pay_type'])){ 
                                            if($payment['pay_type'] == "1") 
                                            { 
                                                echo "Credit card / NETS"; 
                                            }elseif($payment['pay_type'] == "2")
                                            { 
                                                echo "Mobilepay"; 
                                            }
                                        }else{ echo "-"; } ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <div class="card">
            <!-- <img class="card-img-top" src="holder.js/100px180/" alt=""> -->
            <div class="card-body">
                <h4 class="card-title text-center">Car Information</h4>
                <table class="table">
                    <tbody>
                        <!-- <tr>
                            <td></td>
                            <td><?php echo (isset($bookings['car_id']) && $bookings['car_id'] != "") ? $bookings['car_id'] : "-"; ?></td>
                        </tr> -->
                        <tr>
                            <td>Car Number</td>
                            <td><?php echo (isset($bookings['car_number']) && $bookings['car_number'] != "") ? $bookings['car_number'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Car Name</td>
                            <td><?php echo (isset($bookings['car_name']) && $bookings['car_name'] != "") ? $bookings['car_name'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Car Brand</td>
                            <td><?php echo (isset($bookings['car_brand']) && $bookings['car_brand'] != "") ? $bookings['car_brand'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Car Model</td>
                            <td><?php echo (isset($bookings['car_model']) && $bookings['car_model'] != "") ? $bookings['car_model'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Car Colour</td>
                            <td><?php echo (isset($bookings['car_color']) && $bookings['car_color'] != "") ? $bookings['car_color'] : "-"; ?></td>
                        </tr>
                        <tr>
                            <td>Car Description</td>
                            <td><?php echo (isset($bookings['car_description']) && $bookings['car_description'] != "") ? $bookings['car_description'] : "-"; ?></td>
                        </tr>
                        <!-- <tr>
                            <td>Car Size</td>
                            <td><?php echo (isset($bookings['car_size']) && $bookings['car_size'] != "") ? CarSizeType($bookings['car_size']) : "-"; ?></td>
                        </tr> -->
                        <tr>
                            <td>Car Size</td>
                            <td><?php echo (isset($bookings['car_size_definition']) && $bookings['car_size_definition'] != "") ? $bookings['car_size_definition'] : ""; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Payment Information</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Wash Amount</td>
                            <td><?php echo (isset($bookings['wash_amount']) && $bookings['wash_amount'] != "") ? $bookings['wash_amount'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Extra Wash Amount</td>
                            <td><?php echo (isset($bookings['extra_wash_amount']) && $bookings['extra_wash_amount'] != "") ? $bookings['extra_wash_amount'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Extra Charge [zipcode based]</td>
                            <td><?php echo (isset($bookings['extra_charges']) && $bookings['extra_charges'] != "") ? $bookings['extra_charges'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Vat Percentage</td>
                            <td><?php echo (isset($bookings['vat_percentage']) && $bookings['vat_percentage'] != "") ? $bookings['vat_percentage'] . "%" : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Vat</td>
                            <td><?php echo (isset($bookings['vat']) && $bookings['vat'] != "") ? $bookings['vat'] : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Total Pay</td>
                            <td><?php echo (isset($bookings['total_pay']) && $bookings['total_pay'] != "") ? $bookings['total_pay'] : ""; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->

        <div class="card">
            <!-- <img class="card-img-top" src="holder.js/100px180/" alt=""> -->
            <div class="card-body">
                <h4 class="card-title text-center">Wash Comments</h4>
                <?php if (isset($bookings['comments']) && is_array($bookings['comments']) && !empty($bookings['comments'])) { ?>
                    <table class="table">
                        <tbody>
                            <thead>
                                <tr>
                                    <th>Commented by</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <?php foreach ($bookings['comments'] as $key => $wc) {
                                if (isset($wc['commented_by']) && isset($wc['comment'])) { ?>
                                    <tr>
                                        <td><?= $wc['commented_by']; ?></td>
                                        <td><?= $wc['comment']; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>