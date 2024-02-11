<!-- [ Main Content ] start -->
<div class="row" style="display:none;">
    <!-- [Total-user section] start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body" style="padding:10px;">
                <div class="col-sm-9" style="clear:right;float:left">
                    <button id="btn0" type="button" class="btn btn-info load_ajax_data" data-value="0">All</button>
                    <button id="btn1" type="button" class="btn btn-warning load_ajax_data" data-value="1">Today</button>
                    <button id="btn2" type="button" class="btn btn-warning load_ajax_data" data-value="2">Yesterday</button>
                    <button id="btn3" type="button" class="btn btn-warning load_ajax_data" data-value="3">This Week</button>
                    <button id="btn4" type="button" class="btn btn-warning load_ajax_data" data-value="4">Last Week</button>
                    <button id="btn5" type="button" class="btn btn-warning load_ajax_data" data-value="5">This Month</button>
                    <button id="btn6" type="button" class="btn btn-warning load_ajax_data" data-value="6">Last Month</button>
                </div>
                <div class="col-sm-3" style="float:right">
                    <div class="input-daterange input-group " id="datepicker_range">
                        <input type="text" class="form-control text-left" placeholder="Start date" id="start_date_stat" name="start">
                        <input type="text" class="form-control text-right" placeholder="End date" id="end_date_stat" name="end">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- [ Main Content ] end -->
<div class="clear-fix clearfix"></div>
<section id="divUsersStats">
    <div class="row">
        <!-- [Total-user section] start -->
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Active Users</span>
                        <h3 class="f-w-300"><?= user_count($params = "active"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">In-Active Users</span>
                        <h3 class="f-w-300"><?= user_count($params = "inactive"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Bookings Pending</span>
                        <h3 class="f-w-300"><?= bookings_count($params = "pending"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Bookings Approved</span>
                        <h3 class="f-w-300"><?= bookings_count($params = "approved"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Bookings Completed</span>
                        <h3 class="f-w-300"><?= bookings_count($params = "completed"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Bookings Refund Pending</span>
                        <h3 class="f-w-300"><?= bookings_count($params = "refund_pending"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Todays bookings</span>
                        <a href="<?=base_url()?>rudra_booking" ><h3 class="f-w-300" <?php if(bookings_count($params = "todaysbooking") > 0){ echo 'style="color:red"'; } ?>><?= bookings_count($params = "todaysbooking"); ?></h3></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card table-card">
                <div class="row-table">
                    <div class="col-auto theme-bg text-white p-t-50 p-b-50">
                        <i class="feather icon-package f-30"></i>
                    </div>
                    <div class="col text-center">
                        <span class="text-uppercase d-block m-b-10">Unread Messages</span>
                        <a href="<?=base_url()?>rudra_chat/messages" ><h3 class="f-w-300" <?php if(unread_chats() > 0){ echo 'style="color:red"'; } ?>><?= unread_chats(); ?></h3></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .datepicker>.datepicker-days {
        display: block !important;
    }
</style>