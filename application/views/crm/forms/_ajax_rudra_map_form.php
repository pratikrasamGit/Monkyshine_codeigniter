
<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
<div class="form-group col-sm-12">
	<label>Upload Image</label>
 	<input type="file"  name="image" class="form-control" value="<?= (!empty($form_data) ? $form_data->image : ''); ?>"   placeholder="Image"  style="width:47%" />
	<?php if($form_data->image != "") { ?>
		<a href="<?= base_url().$form_data->image; ?>" target="_blank"><img src="<?= base_url().$form_data->image; ?>" class="rounded mx-auto d-block float-left" alt="Map vie image" style="width: 100px; height: 100px;"></a>
	<?php } ?>
</div>

<?php $options = statusDropdown($id = ""); ?>
<div class="form-group col-sm-6">

	<label>Status</label>

	<select  id='status'  name="status" class="form-control" >
<?php 
 foreach($options as $dk => $dv) 
{
	 $selectop = '';
	 if(!empty($form_data) && $form_data->status == $dk)
	{
		$selectop = 'selected="selected"';  
	} ?> 
	<option <?= $selectop ?> value="<?= $dk ?>"  ><?= $dv?></option>
<?php  } ?>
	</select>
</div>

<?php $options = isDeletedDropdown($id = ""); ?>
<!-- <div class="form-group col-sm-6">

	<label>Is Deleted</label>

	<select  id='is_deleted'  name="is_deleted" class="form-control" >
<?php 
 foreach($options as $dk => $dv) 
{
	 $selectop = '';
	 if(!empty($form_data) && $form_data->is_deleted == $dk)
	{
		$selectop = 'selected="selected"';  
	} ?> 
	<option <?= $selectop ?> value="<?= $dk ?>"  ><?= $dv?></option>
<?php  } ?>
	</select>
</div> -->
</div>
<!--Uncomment if Scroll Required div -->
