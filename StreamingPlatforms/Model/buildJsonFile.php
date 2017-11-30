<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\Application\Controller\Autoloader;
require_once __DIR__.'/../../Application/Controller/Autoloader.php';
Autoloader::register();

use Vigas\StreamingPlatforms\Model\Twitch;
use Vigas\StreamingPlatforms\Model\Smashcast;
use Vigas\StreamingPlatforms\Controller\SPController;
use Vigas\StreamingPlatforms\Model\MediasManager;

$streams_manager = new MediasManager;
$games_manager = new MediasManager;

$twitch = new Twitch;
$twitch->getStreamsFromPlatform($twitch->getApiUrl('get_streams', ['limit_val' => 100, 'offset_val' => 0]), array('Client-ID: '.$twitch->getApiKeys()['client_id']));
$twitch->getStreamsFromPlatform($twitch->getApiUrl('get_streams', ['limit_val' => 100, 'offset_val' => 100]), array('Client-ID: '.$twitch->getApiKeys()['client_id']));
$streams_manager->setMediasArray($twitch->getStreams());

$smashcast = new Smashcast;
$smashcast->getStreamsFromPlatform($smashcast->getApiUrl('get_streams', ['limit_val' => 100, 'offset_val' => 0]));
$streams_manager->setMediasArray($smashcast->getStreams());

$streams_manager->buildJsonFile(__DIR__.'/data/streams.json');

$twitch = new Twitch;
$twitch->getGamesFromPlatform($twitch->getApiUrl('get_games', ['limit_val' => 100]), array('Client-ID: '.$twitch->getApiKeys()['client_id']));
$games_manager->addGames($twitch->getGames());

$smashcast = new Smashcast;
$smashcast->getGamesFromPlatform($smashcast->getApiUrl('get_games', ['limit_val' => 100]));
$games_manager->addGames($smashcast->getGames());

$games_manager->buildJsonFile(__DIR__.'/data/games.json');

?>
