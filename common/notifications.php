<div class="row">
<?php 
//echo '<pre>';print_r($this->site_data['notifications']);echo "</pre>";
if(!empty($this->site_data['notifications']))
{
	foreach($this->site_data['notifications'] as $notk => $notv)
	{
		if(!empty($notk))
		{
			foreach($notv as $notp)
			{
				if($notk == "payment_notifications")
				{
?>
	<div class="alert alert-danger alert-dismissible fade show col-sm-12" role="alert">
            <strong>Payments!</strong>&nbsp;<?= $notp['additional_notes'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
	
	<?php 
				}//Payment Notification Ends here
				if($notk == "kyc" && !empty($notp))
				{
					if(!empty($notp))
					{
						
					?>
					<div class="alert alert-<?= $notp['type'] ?> alert-dismissible fade show col-sm-12" role="alert">
            <strong>KYC!</strong>&nbsp;<?= $notp['message'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
					<?php
					}
				}// KYC Main IF ends here
			} // Main Notification Foreach Ends Here
		} // notification  If Ends Here
	}
}
	?>
</div>