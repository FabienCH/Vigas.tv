<?php
use Vigas\Application\Application;


if ($this->params['games_offset'] == 0)
{
    ?>
    <div id="games-display" class="<?= $div_games_display_class ?>">
    <?php
}

if($this->params['games_limit']==6)
{?>
    <h4>Top 6 games</h4>
<?php
}
foreach($this->data['games_to_display'] as $game) 
{?>
    <div id="<?= $game->getId() ?>" class="<?= $div_game_class ?>">
        <a class="game-link" href="<?=Application::getBaseURL()?>streams-by-game/<?= urlencode($game->getGame()) ?>">
        <div>
            <img class="preview" alt="game image" src="<?= $game->getBox() ?>"/>
        </div>
        <div class="overlay game-ov">
            <p><?= urldecode($game->getGame()) ?></p>
            <p class="game-infos"><img alt="viewer icon" src="<?=Application::getBaseURL()?>Web/img/viewer-icon.png" /> <span><?= $game->getViewers() ?></span></p>
        </div>
        </a>
    </div>
    <?php
}
if($this->params['games_limit']==24)
{
    if ($this->params['games_offset'] == 0)
    {?>
        </div>
    <?php
    }?>
    <input type="hidden" id="offset" value="<?=$this->params['games_offset'] + $this->params['games_limit']?>">
    <input type="hidden" id="type" value="games">
    <?php

    if(count($this->data['games_to_display']) == $this->params['games_limit']  && $this->params['games_offset'] + $this->params['games_limit']<72)
    {?>
        <div id="load-more-div">
                <button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>
        </div>
    <?php
    }
}
if($this->params['games_limit']==6)
{?>
    <a href="<?=Application::getBaseURL()?>games"><button name="view-more" class="btn btn-sm btn-primary view-more-btn">View all games</button></a>
    </div>
<?php
}

if ($this->params['games_offset'] != 0)
{
    echo($games_view);
}
