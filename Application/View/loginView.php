<?php
use Vigas\Application\Application;
use Vigas\Application\View\Forms;
?>

<div class="col-md-6">
	<div class="login-form">
		<h3>Login</h3>
		<?php
		if(isset($this->data['login_error']))
			{echo $this->data['login_error'];}
		isset($this->params['log-username']) ? $username = $this->params['log-username'] : $username = '';
			Forms::getLoginForm(Application::getBaseURL().'login', 'post', $username);
		?>
	</div>
</div>

<div id="create-account-form" class="col-md-6">
	<div class="login-form">
		<h3>Create Account</h3>
		<?php
		if(isset($this->data['create_account_error']))
			{echo $this->data['create_account_error'];}
		isset($this->params) ? $params = $this->params : $params = '';
			Forms::getCreateAccountForm(Application::getBaseURL().'login', 'post', $params);
		?>
	</div>
</div>