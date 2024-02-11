<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<div class="form-group col-sm-6">
		<label>Slot Date</label>
		<input type="date" name="slot_date" class="form-control" value="<?= (!empty($form_data) ? $form_data->slot_date : ''); ?>" placeholder="Slot Date" />
	</div>
	<div class="form-group col-sm-6">
		<label>Start Time</label>
		<input type="text" name="start_time" class="form-control" value="<?= (!empty($form_data) ? $form_data->start_time : ''); ?>" placeholder="Start Time" />
	</div>
	<div class="form-group col-sm-6">
		<label>End Time</label>
		<input type="text" name="end_time" class="form-control" value="<?= (!empty($form_data) ? $form_data->end_time : ''); ?>" placeholder="End Time" />
	</div>
	<div class="form-group col-sm-6">
		<label>No Of Service</label>
		<input type="number" name="no_of_service" class="form-control" value="<?= (!empty($form_data) ? $form_data->no_of_service : ''); ?>" placeholder="No Of Service" />
	</div>


	<?php $options = isDeletedDropdown($id = ""); ?>
	<!-- <div class="form-group col-sm-6">
		<label>Is Deleted</label>
		<select id='is_deleted' name="is_deleted" class="form-control">
			<?php foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->is_deleted == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div> -->
</div>
<!--Uncomment if Scroll Required div -->