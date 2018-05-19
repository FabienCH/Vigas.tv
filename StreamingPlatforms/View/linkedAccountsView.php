<?php
use Vigas\Application\Application;

if(isset($_SERVER["REDIRECT_URL"]))
{
    if(Application::getUser() !== null)
	{
        $user = Application::getUser();
         
		if($_SERVER["REDIRECT_URL"] == Application::getBaseURL()."linked-accounts" || ($_SERVER["REDIRECT_URL"] == Application::getBaseURL()."following" && Application::getUser()->getFirstLinkDone() == 0))
		{?>
		<div class="col-md-12, link-account-form">
			<div class="row">
			<?php
			if(!isset($user->getPlatformAccounts()['TwitchAccount']))
			{?>
				<div class="col-md-6">
				<h5>Link your Twitch account</h5>
				<a href="https://api.twitch.tv/kraken/oauth2/authorize?response_type=code&client_id=s22t9783kw51czw3yqdt3kvf6onx40w&redirect_uri=https://vigas.tv<?=Application::getBaseURL()?>save-token&scope=user_read channel_read&state=oauth2"><img src="https://ttv-api.s3.amazonaws.com/assets/connect_dark.png"/></a>
				</div>
				<?php
			}
			else
			{?>
				<div class="col-md-6">
				<h5><img class="linked-account-icon" src="<?=Application::getBaseURL()?>Web/img/twitch-icon.png"/>Twitch account</h5>
				<p><?= ucfirst($user->getPlatformAccounts()['TwitchAccount']->getUsername());?></p>
				<p><img class="linked-profil-pic" src="<?= $user->getPlatformAccounts()['TwitchAccount']->getProfilPictureUrl();?>"/></p>
				</div>
				<?php
			}
			if(!isset($user->getPlatformAccounts()['SmashcastAccount']))
			{?>	
				<div class="col-md-6">
				<h5>Link your Smashcast account</h5>
				<a href="https://api.smashcast.tv/oauth/login?app_token=pgemZT76WNPjOs9KikyHo1CxA0hRl4YSFbvfaGJd"><img src="<?=Application::getBaseURL()?>Web/img/connect-smashcast.png"/></a>
				</div>
				<?php
			}
			else
			{?>
				<div class="col-md-6">
				<h5><img class="linked-account-icon" src="<?=Application::getBaseURL()?>Web/img/smashcast-icon.png"/>Smashcast account</h5>
				<p><?= ucfirst($user->getPlatformAccounts()['SmashcastAccount']->getUsername());?></p>
				<p><img class="linked-profil-pic" src="<?= $user->getPlatformAccounts()['SmashcastAccount']->getProfilPictureUrl();?>"/></p>
				</div>
				<?php
			}
			?>
			</div>
		</div>
		<?php
            if($_SERVER["REDIRECT_URL"] == Application::getBaseURL()."following")
            {?>
            <div class="col-md-12, first-link-done">
                Once you have link your accounts, click the 'Done' button. You can add more acount later in you profile, under Linked Accounts
                <?php
                if(isset($first_link_error))
                    {echo '<br/>'.$first_link_error;}
                ?>
                <form  class="first-link-done-form" action="<?=Application::getBaseURL()?>first-link-done" method="post">
                    <button  name="first-link-done" type="submit" class="btn btn-default">Done</button>
                </form>
            </div>
            <?php
            }
		}
       
	}
	else
	{
		header('Location: https://vigas.tv'.Application::getBaseURL().'login');
	}
    
}
