<?php

use Vigas\Application\Application;
use Vigas\Application\View\Forms;

if(Application::getUser() !== null)
{?>
	<div class="col-md-4 col-sm-6">
        <?php 
        if(isset($this->data['change_pwd_error']))
            {echo $this->data['change_pwd_error'];}
            Forms::getChangePwdForm($_SERVER["REDIRECT_URL"], 'post', Application::getUser());
        ?>
	</div>
<?php
}

else
{
	 header('Location: https://vigas.tv'.Application::getBaseURL().'login');
}
