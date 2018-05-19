<?php

use Vigas\Application\Application;

if(Application::getUser() !== null)
{
    $user = Application::getUser();

    if($user->getPlatformAccounts() != null && $user->getFirstLinkDone()==1)
    {		
        require_once __DIR__.'/../View/allStreamsView.php';
    }
    else
    {
        if($user->getFirstLinkDone()==1)
        {?>
<p>You did not link any account, please link at least one account under the <a href="<?= Application::getBaseURL()?>linked-accounts"/>Linked Accounts</a> section in your profile.</p>
        <?php
        }
		else
		{
			require_once __DIR__.'/../View/linkedAccountsView.php';
		}
    }
}

else
{
	header('Location: https://vigas.tv'.Application::getBaseURL().'login');
}

if (isset($_GET['source_json']))
{
    echo($following_view);
}