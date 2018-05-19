<?php
use Vigas\Application\Application;
use Vigas\Application\View\Forms;

if(Application::getUser() !== null)
{?>
	<li class="profile dropdown">
		<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
			<?=ucfirst(Application::getUser()->getUsername())?> 
		</a>
		<div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
			<a class="dropdown-item" href="<?= Application::getBaseURL()?>profile">
				<i class="fa fa-user icon"></i> Profile </a>
			<a class="dropdown-item" href="<?=Application::getBaseURL()?>linked-accounts">
				<i class="fa fa-gear icon"></i> Linked Accounts </a>
			<a class="dropdown-item" href="<?=Application::getBaseURL()?>logout">
				<i class="fa fa-power-off icon"></i> Logout </a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="<?=Application::getBaseURL()?>about">
				<i class="fa fa-info-circle icon"></i> About </a>
		</div>
	</li>
<?php
}
else
{
?>
	<li>
	<a href="<?=Application::getBaseURL()?>login">Login</a>
	</li>
	
	<li>
	<a href="<?=Application::getBaseURL()?>signup">Sign Up</a>
	</li>
<?php
}
