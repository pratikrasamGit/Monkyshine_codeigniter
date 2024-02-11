<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" />
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />

<!-- [ Main Content ] start -->
<div class="row">
    <!-- [Total-user section] start -->
    <div class="col-sm-12">
        <div class="card text-left">
            <div class="card-body">
                <div class="col-sm-3 float-right">
                    <div class="input-daterange input-group " id="datepicker_range">
                        <input type="text" class="form-control text-left" placeholder="Start date" id="start_date_stat" name="start">
                        <input type="text" class="form-control text-right" placeholder="End date" id="end_date_stat" name="end">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear-fix clearfix"></div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= $page_header ?></h5>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                        <thead>