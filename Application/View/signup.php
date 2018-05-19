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
        <link rel="stylesheet" href="<?=Application::getBaseURL()?>/../Web/modular/css/vendor.css">
        <link rel="stylesheet" id="theme-style" href="<?=Application::getBaseURL()?>/../Web/modular/css/app.css">
		<link href="<?=Application::getBaseURL()?>/../Web/modular/css/style.css" rel="stylesheet">
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
                        <p class="text-center">SIGNUP TO GET INSTANT ACCESS</p>
						<?php
						if(isset($this->data['signup_error']))
							{echo $this->data['signup_error'];}
						?>
                        <form id="signup-form" action="<?=Application::getBaseURL()?>signup" method="POST" novalidate="">
                            <div class="form-group">
								<label for="username">Username</label>
								<input type="text" class="form-control underlined" name="username" id="username" placeholder="Enter username" required=""> </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control underlined" name="email" id="email" placeholder="Enter email address" required=""> </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control underlined" name="password" id="password" placeholder="Enter password" required=""> </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control underlined" name="retype_password" id="retype_password" placeholder="Re-type password" required=""> </div>
                                </div>
                            </div>                     
                            <div class="form-group">
                                <button type="submit" name="signup" class="btn btn-block btn-primary">Sign Up</button>
                            </div>
                            <div class="form-group">
                                <p class="text-muted text-center">Already have an account?
                                    <a href="<?=Application::getBaseURL()?>login">Login!</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center">
                    <a href="<?=Application::getBaseURL()?>" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to main page</a>
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
        <script src="../Web/modular/js/vendor.js"></script>
        <script src="../Web/modular/js/app.js"></script>
        <script src="../Web/modular/js/script.js"></script>
    </body>
</html>