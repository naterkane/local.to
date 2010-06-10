<?php 
if ($this->config->item('registration_open')){

	$this->load->view("admin/request_invite");

} else {
?>
<div class="sign-up-form grid_6">
	<div class="description grid_3">
	<h3>Request an Invite!</h3>
	<p><strong>We're currently in private alpha.</strong></p>
	<p>If you'd like to participate in our public beta, please submit your email address and we'll send you an invitation as soon as accounts are available.</p>
	</div>
	<div class="grid_3">
		<form action="http://nom.createsend.com/t/r/s/jldhhd/" method="post" accept-charset="utf-8">
			<fieldset class="login">
				<p>
					<label for="jldhhd-jldhhd">Email:</label>
					<input type="text" name="cm-jldhhd-jldhhd" id="jldhhd-jldhhd" /><br />
				</p>
				<p>
					<input class="button" type="submit" value="Request Invitation" id="signMeUp" />
				</p>
			</fieldset>
		</form>
	</div>
</div>
<?php
}