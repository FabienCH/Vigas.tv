<?php

//checking that streams have been found
$nb_streams_to_display=count($streams_to_display);
if($nb_streams_to_display>0)
{

    if (!isset($_GET['offset']))
    {
        ?>
        <div id="streams-by-game-display" class="<?= $div_streams_display_class ?>">
        <?php
    }

    foreach($streams_to_display as $stream) 
    {
    ?>
        <div id="<?= $stream->getChannelName()?>" class="<?= $div_stream_class ?>">
            <div style="background-image:url(<?= $stream->getPreviewUrl()?>);  background-size : contain;" >
                <img class="preview" alt="stream overlay" src="<?=getBaseURL()?>/../../Web/img/degrade-<?= $stream->getSource()?>.png" />
            </div>
            <p class="stream-infos"><?= $stream->getChannelDisplayName()?> playing <a href="<?=getBaseURL()?>streams-by-game/<?=urlencode($game)?>"><?= urldecode($game) ?></a></p>

            <div class="overlay stream-ov">
                <h5 class="stream-status"><?= $stream->getStatus()?></h5>
                <p class="viewers"><img alt="viewer icon" src="<?=getBaseURL()?>/../../Web/img/viewer-icon.png" /><?= $stream->getViewers()?></p>
                <img class="play-stream" alt="play stream icon" src="<?=getBaseURL()?>/img/play-logo.png" />
            </div>
        </div>
        <input type="hidden" id="stream-<?= $stream->getChannelName()?>" value="<?= $stream->getStreamUrl()?>">
        <input type="hidden" id="chat-<?= $stream->getChannelName()?>" value="<?= $stream->getChatUrl()?>">
    <?php
    }
    if (!isset($_GET['offset']))
    {?>
        </div>
    <?php
    }?>

    <input type="hidden" id="offset" value="<?=$offset + $limit?>">
    <input type="hidden" id="type" value="streams-by-game">
    <input type="hidden" id="game" value="<?=urlencode($_GET['game'])?>">
    <?php
    if($nb_streams_to_display==$limit && $offset + $limit<72)
    {
        ?>
        <div id="load-more-div">
            <button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>
        </div>
        <?php
    }
}

else
{
    $display_source=" on ";
    foreach($source_array as $key => $source)
    {
        if($source!="All")
        {
            if(!isset($source_array[$key+2]) && isset($source_array[$key+1]))
            {
                $display_source.= $source." or ";
            }
            elseif(!isset($source_array[$key+1]))
            {
                $display_source.= $source;
            }
            else
            {
                $display_source.= $source.", ";
            }
        }
    }
    ?>
    <input type="hidden" id="type" value="streams-by-game">
    <input type="hidden" id="game" value="<?=urlencode($_GET['game'])?>">
    <?php
    if(empty($source_array))
    {?>
        <p>No streaming platform selected. Please select at least one.</p>
    <?php
    }
    else
    {
    ?>
        <p>Couldn't find any live stream for : <strong><?= urldecode($game)?></strong><?=$display_source?>. Select more sources or go back to <a href="<?=getBaseURL()?>">main page</a></p>
    <?php
    }
}

if (isset($_GET['offset']))
{
    echo($streams_by_game_view);
}


