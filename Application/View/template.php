<!doctype html>
<?php 
use Vigas\Application\Application;
?>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?= $this->main_title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
		if (isset($_GET['action']) && $_GET['action'] == 'about' && !isset($e))
		{
			echo "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
		}
		?>
		<link href="https://plus.google.com/b/117076079832095712778" rel="publisher" />
		<link rel="icon" type="image/x-icon" href="<?=Application::getBaseURL()?>favicon.ico" />
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="<?=Application::getBaseURL()?>../Web/css/vendor.css">
        <link rel="stylesheet" href="<?=Application::getBaseURL()?>../Web/css/app.css">
		<link href="<?=Application::getBaseURL()?>../Web/css/style.css" rel="stylesheet">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-76196030-2"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-76196030-2');
		</script>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse d-lg-none d-xl-none">
                        <button class="collapse-btn" id="sidebar-collapse-btn">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div class="col-lg-3 header-block header-block-search">
                        <form role="search" action="<?=Application::getBaseURL()?>search/" method="post">
                            <div class="input-container">
                                <input type="search" name="query" class="search-field" placeholder="Search">
								<button type="submit" class="fa fa-search search-btn"></button>
                                <div class="underline"></div>
                            </div>
                        </form>
                    </div>
					
					<?php
						$home_active = "";
						$home_underline = "";
						$following_active = "";
						$following_underline = "";
						$games_active = "";
						$games_underline = "";
						if(!isset($_GET['action']))
						{
							$home_active = "class=active";
							$home_underline = "<span class=\"nav-underline\"></span>";
						}
						if(isset($_GET['action']) && $_GET['action'] == 'following')
						{
							$following_active = "class=active";
							$following_underline = "<span class=\"nav-underline\"></span>";
						}
						if(isset($_GET['action']) && $_GET['action'] == 'games')
						{
							$games_active = "class=active";
							$games_underline = "<span class=\"nav-underline\"></span>";
						}
						
					?>
					<div class="col-lg-6 header-block header-block-nav top-menu">
						<ul class="nav-link">
							<li><a <?=$home_active;?> href="<?=Application::getBaseURL()?>">All Live Streams</a>
							<?=$home_underline;?></li>
							<li><a class="<?=$following_active; ?>" href="<?=Application::getBaseURL()?>following">Following Streams</a>
							<?= $following_underline;?></li>
							<li><a class="<?=$games_active; ?>" href="<?=Application::getBaseURL()?>games">Games</a>
							<?= $games_underline;?></li>
						</ul>
					</div>
                    <div class="col-lg-3 header-block header-block-nav">
                        <ul class="nav-profile">
							<?=$this->navbar_account?>
                        </ul>
                    </div>
                </header>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                            <div class="brand">
                                <div class="logo">
                                    <a href="<?=Application::getBaseURL()?>"><img alt="vigas logo" src="<?=Application::getBaseURL()?>../Web/img/logo.png" /></a>
								</div>
							</div>
                        <nav class="menu">
                            <ul class="sidebar-menu metismenu" id="sidebar-menu">
								<?= $this->navbar ?>
								<li id="navbar-social-network" class="col-sm-12">
									<div class="fb-like" style="display:block;" data-href="https://www.facebook.com/Vigas.TV" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
									<div class="gplus-like"><div class="g-plusone" data-href="https://plus.google.com/+VigasTv"></div></div>
								</li>								
                            </ul>
                        </nav>
                    </div>
                </aside>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>
                <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
                <div class="mobile-menu-handle"></div>
                <article class="content dashboard-page">
					<?php
					if (isset($_GET['action']) && ($_GET['action'] == 'about' || $_GET['action'] == '404') && !isset($e))
					{
						$class="reduced-container";
					}
					else
					{
						$class="";
					}?>
					<section class="container-fluid <?= $class ?>">
						<!-- Page Heading -->
						<div class="row">
							<header class="col-lg-12">
								<h1 class="page-header">
									<?= $this->content_title ?>
								</h1>
							</header>
						</div>
						<!-- /.row -->
						<?php
						if(isset($_GET['action']) && ($_GET['action'] == 'profile' || $_GET['action'] == 'linked-accounts'))
						{?>
							<ul class="nav nav-tabs">
								<li class="nav-item"><a class="nav-link <?php if($_GET['action']=='profile') {echo "active";}?>" href="<?=Application::getBaseURL()?>profile">Profile</a></li>
								<li class="nav-item"><a class="nav-link <?php if($_GET['action']=='linked-accounts') {echo "active";}?>" href="<?=Application::getBaseURL()?>linked-accounts">Linked accounts</a></li>
							</ul>
						<?php
						}
						
						if (!isset($_GET['action']) || $_GET['action'] == 'streams-by-game' || $_GET['action'] == 'following' && (Application::getUser() !== null && Application::getUser()->getPlatformAccounts()!== null && Application::getUser()->getFirstLinkDone()==1) && !isset($e))
						{
							?>
							<form role="form">
									<div class="form-group source-choice">
										<label>
											<input class="checkbox" type="checkbox" checked id="All" onclick="reload(this.id);" value="All">
											<span>All</span>
										</label>
										<label>
											<input class="checkbox" type="checkbox" checked id="Twitch" onclick="reload(this.id);" value="Twitch">
											<span>Twitch</span>
										</label>
										<?php if(!isset($_GET['action']))
										{ ?>
										<label>
											<input class="checkbox" type="checkbox" checked id="Youtube" onclick="reload(this.id);" value="Youtube">
											<span>Youtube</span>
										</label>
										<?php } ?>
										<label>
											<input class="checkbox" type="checkbox" checked id="Smashcast" onclick="reload(this.id);" value="Smashcast">
											<span>Smashcast</span>
										</label>									
									</div>
								</form>
						<?php } ?>
						<div id="content">	
							<?= $this->content ?>
						</div>
					</section>  
                </article>
                <footer class="footer">
					<p class="footer-block"><a href="<?=Application::getBaseURL()?>">Vigas.tv</a> v2.0 | 2016 - 2018 | <a href="<?=Application::getBaseURL()?>about">About</a> | Template based on  <a target="_blank" href="https:/code.io-admin-html/">Modular Admin</a> | <a target="_blank" href="https://www.facebook.com/Vigas.TV/"><img alt="facebook logo" src="<?=Application::getBaseURL()?>../Web/img/facebook.png"/></a> <a target="_blank" href="https://plus.google.com/+VigasTv/about"><img alt="google plus logo" src="<?=Application::getBaseURL()?>../Web/img/googleplus.png"/></a></p>
                </footer>
               </div>
				
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>
        <script>
            (function(i, s, o, g, r, a, m)
            {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function()
                {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-80463319-4', 'auto');
            ga('send', 'pageview');
        </script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="<?=Application::getBaseURL()?>../Web/js/vendor.js"></script>
        <script src="<?=Application::getBaseURL()?>../Web/js/app.js"></script>
        <script src="<?=Application::getBaseURL()?>../Web/js/script.js"></script>
    </body>
</html>