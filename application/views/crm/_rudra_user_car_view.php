<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Car Information</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Car size</td>
                            <td><?=CarSizeType($id = $form_data->car_size) ?></td>
                        </tr>
                        <tr>
                            <td>User</td>
                            <td><?php echo (isset($user_data->name) && $user_data->name != "") ? $user_data->name." ".$user_data->surname : ""; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Registration Number</td>
                            <td><?php echo (isset($form_data->registration_number) && $form_data->registration_number != "") ? $form_data->registration_number : ""; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Name</td>
                            <td><?php echo (isset($form_data->name) && $form_data->name != "") ? $form_data->name : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Brand</td>
                            <td><?php echo (isset($form_data->brand) && $form_data->brand != "") ? $form_data->brand : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td><?php echo (isset($form_data->model) && $form_data->model != "") ? $form_data->model : ""; ?></td>
                        </tr> 
                        <tr>
                            <td>Color</td>
                            <td><?php echo (isset($form_data->color) && $form_data->color != "") ? $form_data->color : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><?php echo (isset($form_data->description) && $form_data->description != "") ? $form_data->description : ""; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?php if($form_data->status=='1'){ echo "Active"; }else{ echo "Inactive"; } ?></td>
                        </tr>
                        <!-- <tr>
                            <td>Is Deleted</td>
                            <td><?php if($form_data->is_deleted=='1'){ echo "Deleted"; }else{ echo "Active"; } ?></td>
                        </tr> -->
                       

                    </tbody>
                </table>
            </div>
        </div>

      
    </div>
</div>