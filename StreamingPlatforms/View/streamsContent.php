<?php

use Vigas\Application\Application;

if($this->params['streams_limit']==3)
{?>
	<h4 class="sidebar-title">Top 3 live streams</h4>
	<?php
	$separator = "<br />";
}
else
{
	$separator = " playing ";
}

//if there is streams to display
if(count($this->data['streams_to_display']) > 0)
{	
	foreach($this->data['streams_to_display'] as $stream) 
	{	
		$source = $stream->getSource();
		$game = $stream->getGame();
		?>
		<div id="<?= $stream->getId()?>" class="<?= $div_stream_class ?>">
			<div style="background-image:url(<?= $stream->getPreviewUrl()?>); background-size : contain;" >
				<img class="preview" alt="stream overlay" src="<?= Application::getBaseURL()?>Web/img/degrade-<?= $stream->getSource()?>.png" />
			</div>
<<<<<<< HEAD
			<p class="ellipsis stream-infos"><?= $stream->getChannelDisplayName()?><?php if($source != 'Youtube') {echo $separator;} ?><a href="<?=Application::getBaseURL()?>streams-by-game/<?= urlencode($game) ?>"><?= urldecode($game) ?></a></p>
=======
			<p class="ellipsis stream-infos"><?= $stream->getChannelDisplayName().$separator ?><a href="<?=Application::getBaseURL()?>streams-by-game/<?= urlencode($game) ?>"><?= urldecode($game) ?></a></p>
>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307

			<div class="overlay stream-ov">
				<?php
				if (!isset($_GET['action']) || $_GET['action'] == 'streams-by-game' || $_GET['action'] == 'following')
				{?>
				<h5 class="ellipsis stream-status"><?= $stream->getStatus()?></h5>
				<?php } ?>
<<<<<<< HEAD
				<p class="viewers"><img alt="viewer icon" src="<?=Application::getBaseURL()?>Web/img/viewer-icon.png" /><?= $stream->getFormatedViewers()?></p>
=======
				<p class="ellipsis viewers"><img alt="viewer icon" src="<?=Application::getBaseURL()?>Web/img/viewer-icon.png" /><?= $stream->getViewers()?></p>
>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307
				<img class="play-stream" alt="play stream icon" src="<?=Application::getBaseURL()?>Web/img/play-logo.png" />
			</div>
		</div>
		<input type="hidden" id="stream-<?= $stream->getId()?>" value="<?= $stream->getStreamUrl()?>">
		<input type="hidden" id="chat-<?= $stream->getId()?>" value="<?= $stream->getChatUrl()?>">
		<input type="hidden" id="source-<?= $stream->getId()?>" value="<?= $source?>">
	<?php
	}
}

//if there is no streams to display
else
{
    if(empty($this->params['source_array']))
    {?>
        <p class="alert alert-warning">No streaming platform selected. Please select at least one.</p>
    <?php
    }
    else
    {
		if(!isset($_GET['game']))
		{
			$game = "";
		}
		else
		{
			$game = ' '.htmlspecialchars(urldecode($_GET['game']));
		}
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
        <p class="alert alert-warning">Couldn't find any<?=$game?> live stream <?=$display_source?></p>
    <?php
    }
}
