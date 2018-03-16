<?php

use Vigas\Application\Application;

if(Application::getUser() !== null)
{
	var_dump(Application::getUser());
    $user = Application::getUser();

     if((Application::getPlatformAccounts()['twitch_data'] != null || Application::getPlatformAccounts()['smashcast_data'] != null) && $user->getFirstLinkDone()==1)
    {		
        require_once __DIR__.'/../View/allStreamsView.php';
    }
    else
    {
        if($user->getFirstLinkDone()==1)
        {?>
<p>You did not link any account, please link at least one account under the <a href="<?= Application::getBaseURL()?>linked-account"/>Linked Accounts</a> section in your profile.</p>
        <?php
        }
		else
		{
			require_once __DIR__.'/../View/platformAccountView.php';
		}
    }
}

else
{
	require_once __DIR__.'/../../Application/View/loginView.php';
}

if (isset($_GET['source_json']))
{
    echo($following_view);
}