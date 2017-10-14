<?php
namespace Vigas\Application\View;

class View
{  
    protected $params;
    protected $data;
    protected $main_title;
    protected $content_title;
    protected $navbar_account;
    protected $navbar;
    protected $content;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
		ob_start();
        require_once __DIR__.'/navbarAccountView.php';
		$this->navbar_account = ob_get_clean();
    }
    
    public function getStreamsContentView()
    {
        $div_streams_display_class = "row";
        $div_stream_class = "col-lg-3 col-md-4 col-xs-6 div-prev";
        require_once __DIR__.'/../../StreamingPlatforms/View/allStreamsView.php';
        
        if($this->params['streams_offset'] == 0)
        {
            $div_games_display_class = "col-xs-12 div-navbar";
            $div_game_class = "col-sm-6 col-xs-2 div-prev-navbar";
			ob_start();
            require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php';
            $this->navbar = ob_get_clean(); 
        }    
    }
    
    public function getStreamsView()
    {         
        $this->main_title = "Vigas - Live streams from Twitch and Smashcast";
        $this->content_title = "Live streams";
        $this->getStreamsContentView($this->main_title, $this->content_title);
    }
   
    public function getGamesView()
    {
        $this->main_title = "Vigas - All games from Twitch and Smashcast";
		$this->content_title = "All games";
        $div_games_display_class = "row";
        $div_game_class="col-lg-2 col-md-3 col-xs-4 div-prev";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php';
        $this->content = ob_get_clean();  

        if($this->params['games_offset'] == 0)
        {
            $div_streams_display_class = "col-xs-12 div-navbar";
            $div_stream_class = "col-sm-12 col-xs-4 div-prev-navbar";
			ob_start();
            require_once __DIR__.'/../../StreamingPlatforms/View/allStreamsView.php';
            $this->navbar = ob_get_clean();
        }
    }
    
     public function getStreamsByGameView()
    {         
        $this->main_title = "Vigas - ".urldecode($this->params['games'])." live streams from Twitch and Smashcast";
        $this->content_title = urldecode($this->params['games'])." live streams";
        $this->getStreamsContentView($this->main_title, $this->content_title);
    }
    
    public function getFollowingView()
    {         
        $this->main_title = "Vigas - Following live streams from Twitch and Smashcast";
        $this->content_title = "Live streams";
        $div_streams_display_class="row";
        $div_stream_class="col-lg-3 col-md-4 col-xs-6 div-prev";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/followingView.php';
        $this->content = ob_get_clean();
        
        if($this->params['streams_offset'] == 0)
        {
            $div_games_display_class = "col-xs-12 div-navbar";
            $div_game_class = "col-sm-6 col-xs-2 div-prev-navbar";
			ob_start();
            require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php'; 
            $this->navbar = ob_get_clean();
        }
    }
    
    public function getSearchView()
    {
        if($this->params['query'] != '')
        {
            $this->main_title = "Vigas - Results for ".$this->params['query'];
            $this->content_title = "Results for ".$this->params['query'];
        }
        else
        {
            $this->main_title = "Vigas - No Result";
            $this->content_title = "No Result";
        }
        ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/searchView.php';
        $this->content = ob_get_clean();
        
        $div_games_display_class = "col-xs-12 div-navbar";
        $div_game_class = "col-sm-6 col-xs-2 div-prev-navbar";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php'; 
        $this->navbar = ob_get_clean();
    }
    
    public function getLinkedAccountView()
    {
        $this->main_title = "Vigas - Login or Create Account";
        $this->content_title = "Login or create account";
		ob_start();
        require_once __DIR__.'/linkedAccountView.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function getSaveTokenView()
    {
        $this->main_title = "Vigas - Login or Create Account";
        $this->content_title = "Login or create account";
        $this->getDefaultNavbarView();
    }
    
    public function getProfileView()
    {
        $this->main_title = "Vigas - Settings";
        $this->content_title = "Settings";
		ob_start();
        require_once __DIR__.'/profileView.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function getForgotPasswordView()
    {
        $this->main_title = "Vigas - Forgot Password";
        $this->content_title = "Forgot Password";
		ob_start();
        require_once __DIR__.'/forgotPasswordView.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function getResetPasswordView()
    {
        $this->main_title = "Vigas - Reset Password";
        $this->content_title = "Reset Password";
		ob_start();
        require_once __DIR__.'/resetPasswordView.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function getAboutView()
    {
        $this->main_title = "Vigas - About";
        $this->content_title = "About Vigas";
		ob_start();
        require_once __DIR__.'/aboutView.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function get404View()
    {
        $this->main_title="Vigas - Page not found";
        $this->content_title="Page not found";
		ob_start();
        require_once __DIR__.'/404View.php';
        $this->content = ob_get_clean();
        $this->getDefaultNavbarView();
    }
    
    public function getDefaultNavbarView()
    {
		ob_start();
        require_once __DIR__.'/defaultNavbarView.php';
        $this->navbar = ob_get_clean();
    }
    
    public function getTemplate()
    {
        require_once __DIR__.'/template.php';   
    }

}
