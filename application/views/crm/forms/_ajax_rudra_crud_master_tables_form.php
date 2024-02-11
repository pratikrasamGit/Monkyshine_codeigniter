
<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
<div class="form-group col-sm-6">
	<label>Tbl Name</label>
 	<input type="text"  name="tbl_name" class="form-control" value="<?= (!empty($form_data) ? $form_data->tbl_name : ''); ?>"   placeholder="Tbl Name"   />
</div>
<div class="form-group col-sm-6">
	<label>Col Strc</label>
 	<input type="text"  name="col_strc" class="form-control" value="<?= (!empty($form_data) ? $form_data->col_strc : ''); ?>"   placeholder="Col Strc"   />
</div>
<div class="form-group col-sm-6">
	<label>List Template</label>
 	<input type="text"  name="list_template" class="form-control" value="<?= (!empty($form_data) ? $form_data->list_template : ''); ?>"   placeholder="List Template"   />
</div>
<div class="form-group col-sm-6">
	<label>Status</label>
 	<input type="number"  name="status" class="form-control" value="<?= (!empty($form_data) ? $form_data->status : ''); ?>"   placeholder="Status"   />
</div>
</div>
<!--Uncomment if Scroll Required div -->
