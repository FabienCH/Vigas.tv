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
        <link href="https://plus.google.com/b/117076079832095712778" rel="publisher" />
		<link rel="icon" type="image/x-icon" href="<?=Application::getBaseURL()?>favicon.ico" />
        <!-- Place favicon.ico in the root directory -->
<<<<<<< HEAD
        <link rel="stylesheet" href="<?=Application::getBaseURL()?>/../Web/css/vendor.css">
        <link rel="stylesheet" id="theme-style" href="<?=Application::getBaseURL()?>/../Web/css/app.css">
		<link href="<?=Application::getBaseURL()?>/../Web/css/style.css" rel="stylesheet">
=======
        <link rel="stylesheet" href="<?=Application::getBaseURL()?>/../Web/modular/css/vendor.css">
        <link rel="stylesheet" id="theme-style" href="<?=Application::getBaseURL()?>/../Web/modular/css/app.css">
		<link href="<?=Application::getBaseURL()?>/../Web/modular/css/style.css" rel="stylesheet">
>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307
    </head>
    <body>
        <div class="auth">
            <div class="auth-container">
                <div class="card">
                    <header class="auth-header">
                        <h1 class="auth-title">
                            <div class="logo">
                                <span class="l l1"></span>
                                <span class="l l2"></span>
                                <span class="l l3"></span>
                                <span class="l l4"></span>
                                <span class="l l5"></span>
                            </div>Vigas</h1>
                    </header>
                    <div class="auth-content">
                        <p class="text-center">PASSWORD RECOVER</p>
<<<<<<< HEAD
						<?php
						if(isset($this->data['forgot_password_error']) && $this->data['forgot_password_error'] == 'success')
						{ ?>
							<div class="alert alert-success">An email has been send to <?= Application::getHTTPRequest()->getPostData()['email']?><br/>The link will expire after 30 minutes</div>
							<div class="form-group">
                                <a href="<?=Application::getBaseURL()?>login" class="link-as-btn btn btn-block btn-primary">Return to Login</a>
                            </div>
						<?php
						}
						else
						{ ?>
                        <p class="text-muted text-center">
                            <small>Enter your email address to recover your password.</small>
                        </p>
						<?php
						if(isset($this->data['forgot_password_error']))
							{ echo $this->data['forgot_password_error']; }
						?>
                        <form id="reset-form" action="<?=Application::getBaseURL().'forgot-password'?>" method="POST" novalidate="">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control underlined" name="email" id="email" placeholder="Your email address" required> </div>
                            <div class="form-group">
                                <button type="submit" name="reset-password" class="btn btn-block btn-primary">Reset</button>
                            </div>
                            <div class="form-group clearfix">
                                <a class="pull-left" href="<?=Application::getBaseURL()?>login">Return to Login</a>
                                <a class="pull-right" href="<?=Application::getBaseURL()?>signup">Sign Up !</a>
                            </div>
                        </form>
						<?php }	?>
=======
                        <p class="text-muted text-center">
                            <small>Enter your email address to recover your password.</small>
                        </p>
                        <form id="reset-form" action="/index.html" method="POST" novalidate="">
                            <div class="form-group">
                                <label for="email1">Email</label>
                                <input type="email" class="form-control underlined" name="email1" id="email1" placeholder="Your email address" required> </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Reset</button>
                            </div>
                            <div class="form-group clearfix">
                                <a class="pull-left" href="<?=Application::getBaseURL()?>login">return to Login</a>
                                <a class="pull-right" href="<?=Application::getBaseURL()?>signup">Sign Up!</a>
                            </div>
                        </form>
>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307
                    </div>
                </div>
                <div class="text-center">
                    <a href="<?=Application::getBaseURL()?>" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to main page </a>
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
<<<<<<< HEAD
        <script src="../Web/js/vendor.js"></script>
        <script src="../Web/js/app.js"></script>
        <script src="../Web/js/script.js"></script>
=======
        <script src="../Web/modular/js/vendor.js"></script>
        <script src="../Web/modular/js/app.js"></script>
        <script src="../Web/modular/js/script.js"></script>
>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307
    </body>
</html>