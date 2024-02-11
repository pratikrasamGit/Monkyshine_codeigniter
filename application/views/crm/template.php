<?php
if ($this->session->userdata('rudra_admin_utype') == 1) {
    $this->load->view('crm/common/header');
    $this->load->view('crm/common/top_header');
    $this->load->view('crm/common/left_menu');
    $template_common = 'common';
}  ?>


<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <?php
                //$this->load->view('crm/' . $template_common . '/notifications');
                ?>

                <!-- [ breadcrumb ] start -->
                <!--div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><i class="feather icon-home"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div-->
                <!-- [ breadcrumb ] end -->
                <div class="main-body">
                    <div class="page-wrapper">
                        <?php $this->load->view('crm/' . $page_template) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- [ Main Content ] end -->
<style>
    .hidden {
        display: none !important;
    }

    .datepicker>.datepicker-days {
        display: block !important;
    }
</style>
<?php $this->load->view('crm/' . $template_common . '/footer_scripts'); ?>