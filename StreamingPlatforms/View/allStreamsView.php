<?php
use Vigas\Application\Application;

ob_start();

$nb_streams_to_display=count($this->data['streams_to_display']);

if($nb_streams_to_display>0)
{
	$separator = " playing ";
    if($this->params['streams_offset'] == 0 && !isset($_GET['source_json']))
    {?>
            <div id="streams-display" class="<?= $div_streams_display_class ?>">
			
    <?php
	}
    if($this->params['streams_limit']==3)
    {?>
            <h4>Top 3 live streams</h4>
    <?php
		$separator = " - ";
    }
    foreach($this->data['streams_to_display'] as $stream) 
    {	
        $game=$stream->getGame();
        ?>
        <div id="<?= $stream->getChannelName()?>" class="<?= $div_stream_class ?>">
            <div style="background-image:url(<?= $stream->getPreviewUrl()?>); background-size : contain;" >
                <img class="preview" alt="stream overlay" src="<?= Application::getBaseURL()?>Web/img/degrade-<?= $stream->getSource()?>.png" />
            </div>
            <p class="stream-infos"><?= $stream->getChannelDisplayName().$separator ?><a href="<?=Application::getBaseURL()?>streams-by-game/<?= urlencode($game) ?>"><?= urldecode($game) ?></a></p>

            <div class="overlay stream-ov">
                <?php
                if (!isset($_GET['action']))
                {?>
                <h5 class="stream-status"><?= $stream->getStatus()?></h5>
                <?php } ?>
                <p class="viewers"><img alt="viewer icon" src="<?=Application::getBaseURL()?>Web/img/viewer-icon.png" /><?= $stream->getViewers()?></p>
                <img class="play-stream" alt="play stream icon" src="<?=Application::getBaseURL()?>Web/img/play-logo.png" />
            </div>
        </div>
        <input type="hidden" id="stream-<?= $stream->getChannelName()?>" value="<?= $stream->getStreamUrl()?>">
        <input type="hidden" id="chat-<?= $stream->getChannelName()?>" value="<?= $stream->getChatUrl()?>">
    <?php
    }

    if($this->params['streams_limit']==36)
    {
        if($this->params['streams_offset'] == 0 && !isset($_GET['source_json']))
        {?>
            </div>
        <?php
        }
        ?>
        <input type="hidden" id="offset" value="<?=$this->params['streams_offset'] + $this->params['streams_limit']?>">
        <input type="hidden" id="type" value="<?= isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'streams' ?>">
        <?php
         if(isset($_GET['game']))
        {?>
            <input type="hidden" id="game" value="<?=urlencode($_GET['game'])?>">
        <?php
        }
    
        if($nb_streams_to_display==$this->params['streams_limit'] && $this->params['streams_offset'] + $this->params['streams_limit']<144)
        {?>
            <div id="load-more-div">
                <button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>
            </div>	
        <?php
        }

        if(isset($this->params['id-stream']))
        {?>
            <input type="hidden" id="id-stream" value="<?=$this->params['id-stream']?>">
        <?php
        }
    }
    if(isset($_GET['action']) && $_GET['action'] == 'games')
    {?>
        <a href="<?=Application::getBaseURL()?>"><button name="view-more" class="btn btn-sm btn-primary view-more-btn">View all streams</button></a>
        </div>
    <?php
    }
}
else
{?>
    <input type="hidden" id="type" value="<?= isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'streams' ?>">
    <?php
    if(isset($_GET['game']))
    {?>
        <input type="hidden" id="game" value="<?=urlencode($_GET['game'])?>">
    <?php
    }
    if(empty($this->params['source_array']))
    {?>
        <p>No streaming platform selected. Please select at least one.</p>
    <?php
    }
    else
    {
        $display_source=" on ";
        foreach($this->params['source_array'] as $key => $source)
        {
            if($source!="All")
            {
                if(!isset($this->params['source_array'][$key+2]) && isset($this->params['source_array'][$key+1]))
                {
                    $display_source.= $source." or ";
                }
                elseif(!isset($this->params['source_array'][$key+1]))
                {
                    $display_source.= $source;
                }
                else
                {
                    $display_source.= $source.", ";
                }
            }
        }?>
        <p>Couldn't find any live stream <?=$display_source?></p>
    <?php
    }
}
if(isset($_GET['source_json']))
{
    echo ob_get_clean();
}
else
{
	$this->content = ob_get_clean();
}
