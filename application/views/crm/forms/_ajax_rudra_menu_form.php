<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<div class="form-group col-sm-6">
		<label>Fc Id</label>
		<input type="number" name="fc_id" class="form-control" value="<?= (!empty($form_data) ? $form_data->fc_id : ''); ?>" placeholder="Fc Id" />
	</div>
	<div class="form-group col-sm-6">
		<label>Mn Name</label>
		<input type="text" name="mn_name" class="form-control" value="<?= (!empty($form_data) ? $form_data->mn_name : ''); ?>" placeholder="Mn Name" />
	</div>
	<div class="form-group col-sm-6">
		<label>Mn Controller</label>
		<input type="text" name="mn_controller" class="form-control" value="<?= (!empty($form_data) ? $form_data->mn_controller : ''); ?>" placeholder="Mn Controller" />
	</div>
	<div class="form-group col-sm-6">
		<label>Mn Method</label>
		<input type="text" name="mn_method" class="form-control" value="<?= (!empty($form_data) ? $form_data->mn_method : ''); ?>" placeholder="Mn Method" />
	</div>
	<div class="form-group col-sm-6">
		<label>Mn Params</label>
		<input type="text" name="mn_params" class="form-control" value="<?= (!empty($form_data) ? $form_data->mn_params : ''); ?>" placeholder="Mn Params" />
	</div>

	<?php $options = $mn_status; ?>
	<div class="form-group col-sm-6">

		<label>Mn Status</label>

		<select id='mn_status' name="mn_status" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->mn_status == $dv) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Mn Icon</label>
		<input type="text" name="mn_icon" class="form-control" value="<?= (!empty($form_data) ? $form_data->mn_icon : ''); ?>" placeholder="Mn Icon" />
	</div>
</div>
<!--Uncomment if Scroll Required div -->