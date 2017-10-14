<?php

namespace Vigas\StreamingPlatforms\Model;

/********** error reporting *************/
error_reporting(E_ALL);
ini_set('display_errors', 'on');

ini_set('xdebug.default_enable', 'on');
ini_set('xdebug.show_local_vars', 1);
ini_set('xdebug.var_display_max_depth', 7);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
/**************************************/


use Vigas\Application\Controller\Autoloader;
require_once __DIR__.'/../../Application/Controller/Autoloader.php';
Autoloader::register();

use Vigas\StreamingPlatforms\Model\StreamsManager;
use Vigas\StreamingPlatforms\Model\GamesManager;
use Vigas\StreamingPlatforms\Controller\MediaController as MediaController;

$media_controller = new MediaController();
$media_controller->setPlatformsKeys();
$media_controller->getPlatformsKeys();
 
$streams_manager = new StreamsManager;
$streams_manager->getTwitchStreams('https://api.twitch.tv/kraken/streams?limit=100&offset=0', null, array('Client-ID: '.$media_controller->getPlatformsKeys()['twitch']['client_id']));
$streams_manager->getTwitchStreams('https://api.twitch.tv/kraken/streams?limit=100&offset=100', null, array('Client-ID: '.$media_controller->getPlatformsKeys()['twitch']['client_id']));
$streams_manager->getSmashcastStreams('https://api.smashcast.tv/media/live/list?limit=120&start=0');
$streams_manager->buildJsonFile(__DIR__.'/data/streams.json');

$games_manager = new GamesManager;
$games_manager->getTwitchGames('https://api.twitch.tv/kraken/games/top?limit=100', null, array('Client-ID: '.$media_controller->getPlatformsKeys()['twitch']['client_id']));
$games_manager->getSmashcastGames('https://api.smashcast.tv/games?limit=100&liveonly=true');
$games_manager->buildJsonFile(__DIR__.'/data/games.json');

?>
