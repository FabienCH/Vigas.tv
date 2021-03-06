<?php
namespace Vigas\Application\View;

/**
* Class View.
* Imports view files and sets view
*/
class View
{  
	/**
    * @var array Parameters used by the view
    */
    protected $params;
	
	/**
    * @var array Data retrived from the model
    */
    protected $data;
	
	/**
    * @var string HTML page title 
    */
    protected $main_title;
	
	/**
    * @var string The view's content title
    */
    protected $content_title;
	
	/**
    * @var string Top navbar account information
    */
    protected $navbar_account;
	
	/**
    * @var string Left side navbar
    */
    protected $navbar;
	
	/**
    * @var string The view's content
    */
    protected $content;

	/**
    * Sets parameters for the view and gets the navbar account view
    * @param array $params Parameters used by the view
    * @param array $data Data retrived from the model
    */
    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
		ob_start();
        require_once __DIR__.'/navbarAccountView.php';
		$this->navbar_account = ob_get_clean();
    }
    
	/**
    * Gets the all live streams or streams by game view
    * @param string $streams_view The view file to get (allStreams or streamsByGame)
    */
    public function getStreamsContentView($streams_view)
    {
        $div_streams_display_class = "row";
        $div_stream_class = "col-xl-3 col-lg-4 col-sm-6 div-prev";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/'.$streams_view.'View.php';
		$this->content = ob_get_clean();
        
        if($this->params['streams_offset'] == 0)
        {
			$this->getGamesNavbarView();
        }    
    }
    
	/**
    * Gets the all live streams view
    */
    public function getStreamsView()
    {         
        $this->main_title = "Vigas - Live streams from Twitch, Youtube and Smashcast";
        $this->content_title = "Live streams";
        $this->getStreamsContentView('allStreams');
    }
	
	/**
    * Gets the streams content only
    */
	public function getStreamsContent()
    {
		$div_streams_display_class = "row";
        $div_stream_class = "col-xl-3 col-lg-4 col-sm-6 div-prev";
        require_once __DIR__.'/../../StreamingPlatforms/View/streamsContent.php';
    }
   
	/**
    * Gets the all games view
    */
    public function getGamesView()
    {
        $this->main_title = "Vigas - All games from Twitch, Youtube and Smashcast";
		$this->content_title = "All games";
        $div_games_display_class = "row";
        $div_game_class="col-lg-2 col-md-3 col-sm-4 col-6 div-prev";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php';
        $this->content = ob_get_clean();  
        if($this->params['games_offset'] == 0)
        {
            $this->getStreamsNavbarView();
        }
    }
	
	/**
    * Gets the games content only
    */
	public function getGamesContent()
    {
		$div_games_display_class = "row";
        $div_game_class = "col-lg-2 col-md-3 col-sm-4 col-6 div-prev";
        require_once __DIR__.'/../../StreamingPlatforms/View/gamesContent.php';
    }
    
	/**
    * Gets the live streams by game view
    */
    public function getStreamsByGameView()
    {         
        $this->main_title = "Vigas - ".urldecode($this->params['games'])." live streams from Twitch, Youtube and Smashcast";
        $this->content_title = urldecode($this->params['games'])." live streams";
        $this->getStreamsContentView('streamsByGame');
    }
    
	/**
    * Gets the following live streams view
    */
    public function getFollowingView()
	{         
        $this->main_title = "Vigas - Following live streams from Twitch, Youtube and Smashcast";
        $this->content_title = "Following live streams";
        $div_streams_display_class="row";
        $div_stream_class="col-xl-3 col-lg-4 col-sm-6 div-prev";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/followingView.php';
        $this->content = ob_get_clean();
        
        if($this->params['streams_offset'] == 0)
        {
			$this->getGamesNavbarView();
        }
    }
    
	/**
    * Gets the search view
    */
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
		$this->getGamesNavbarView();
    }
    
	/**
    * Gets the login view
    */
    public function getLoginView()
    {
		
        $this->main_title = "Vigas - Login";
		require_once __DIR__.'/login.php';
    }
	
	/**
    * Gets the sign up view
    */
    public function getSignupView()
    {
		
        $this->main_title = "Vigas - Sign Up";
		require_once __DIR__.'/signup.php';
    }
	
	/**
    * Gets the save token view
    */
    public function getSaveTokenView()
    {
        $this->main_title = "Vigas - Login or Create Account";
        $this->content_title = "Login or create account";
        $this->getGamesNavbarView();
    }
    
	/**
    * Gets the user profile view
    */
    public function getProfileView()
    {
        $this->main_title = "Vigas - Settings";
        $this->content_title = "Settings";
		ob_start();
        require_once __DIR__.'/profileView.php';
        $this->content = ob_get_clean();
        $this->getGamesNavbarView();
    }
	
	/**
    * Gets the linked accounts view
    */
    public function getLinkedAccountsView()
    {
        $this->main_title = "Vigas - Settings";
        $this->content_title = "Settings";
		ob_start();
        require_once __DIR__.'/../../StreamingPlatforms/View/linkedAccountsView.php';
        $this->content = ob_get_clean();
        $this->getGamesNavbarView();
    }
    
	/**
    * Gets the forgot password view
    */
    public function getForgotPasswordView()
    {
        $this->main_title = "Vigas - Forgot Password";
        require_once __DIR__.'/forgotPassword.php';
    }
    
	/**
    * Gets the reset password view
    */
    public function getResetPasswordView()
    {
        $this->main_title = "Vigas - Reset Password";
        require_once __DIR__.'/resetPassword.php';
    }
    
	/**
    * Gets the about view
    */
    public function getAboutView()
    {
        $this->main_title = "Vigas - About";
        $this->content_title = "About Vigas";
		ob_start();
        require_once __DIR__.'/aboutView.php';
        $this->content = ob_get_clean();
        $this->getGamesNavbarView();
    }
    
	/**
    * Gets the 404 view
    */
    public function get404View()
    {
        $this->main_title="Vigas - Page not found";
        $this->content_title="Page not found";
		ob_start();
        require_once __DIR__.'/404View.php';
        $this->content = ob_get_clean();
        $this->getGamesNavbarView();
    }
    
	/**
    * Gets the game navbar view
    */
    public function getGamesNavbarView()
    {
		$div_games_display_class = "col-sm-12 div-navbar";
        $div_game_class = "col-sm-6 div-prev-navbar game-navbar";
		ob_start();
		require_once __DIR__.'/mobileNavbarView.php';
		require_once __DIR__.'/../../StreamingPlatforms/View/allGamesView.php'; 
        $this->navbar = ob_get_clean();
    }
	
	/**
    * Gets the game navbar view
    */
    public function getStreamsNavbarView()
    {
		$div_streams_display_class = "col-sm-12 div-navbar";
        $div_stream_class = "col-sm-12 div-prev-navbar stream-navbar";
		ob_start();
		require_once __DIR__.'/mobileNavbarView.php';
		require_once __DIR__.'/../../StreamingPlatforms/View/allStreamsView.php'; 
        $this->navbar = ob_get_clean();
    }
    
	/**
    * Gets the template
    */
    public function getTemplate()
    {
        require_once __DIR__.'/template.php';   
    }

}
