var stream_div;
var parent_div_content;
var all_streams_source_array = ["All", "Twitch", "Smashcast","Youtube"];
var source_array = ["All", "Twitch", "Smashcast"];


function scroll_to_stream()
{
	vertical_marge = $(window).innerHeight() - $('#stream').height();
	$('html, body').animate({
		scrollTop:$('#stream').offset().top-(vertical_marge/2)
	}, 'slow');
}

function close_stream()
{
	$('#'+stream_div.attr("id")).html(parent_div_content);		//restore content of the div-prev container
	$('#'+stream_div.attr("id")).removeClass("col-sm-12 stream-container");
	$('#'+stream_div.attr("id")).addClass("col-xl-3 col-lg-4 col-sm-6 div-prev");
	$('#'+stream_div.attr("id")).removeAttr('style');
	show_overlay();
	hide_overlay();
}

function expand_stream()
{
	$(".sidebar").addClass("expanded-sidebar");
	$(".header").addClass("expanded-header");
	$(".app").addClass("expanded-app");
	$(".d-lg-none").attr('style','display: block !important');
	
	$("#stream").removeClass("col-md-8");
	$("#chat").removeClass("col-md-4");
	$("#stream").addClass("col-md-9");
	$("#chat").addClass("col-md-3");
	stream_id = $("#stream").closest(".stream-container");
	stream_id.addClass("expanded-stream-container");
	stream_id.removeClass("stream-container");
		
	$(".expand-button").replaceWith("<img alt=\"reduce stream button\" class=\"reduce-button\" src=\"/Web/img/reduce-button.png\" />");
	
	scroll_to_stream();
}

function reduce_stream()
{
	
	$(".sidebar").removeClass("expanded-sidebar");
	$(".header").removeClass("expanded-header");
	$(".app").removeClass("expanded-app");
	$(".d-lg-none").removeAttr('style');
	
	$("#stream").removeClass("col-md-9");
	$("#chat").removeClass("col-md-3");
	$("#stream").addClass("col-md-8");
	$("#chat").addClass("col-md-4");
	stream_id = $("#stream").closest(".expanded-stream-container");
	stream_id.addClass("stream-container");
	stream_id.removeClass("expanded-stream-container");
		
	$(".reduce-button").replaceWith("<img alt=\"expand stream button\" class=\"expand-button\" src=\"/Web/img/expand-button.png\" />");
	
	scroll_to_stream();
}
	
function load_stream()
{	
	$(document).on("click", ".stream-reload", function() {
		window.location.href="/stream/"+$(this).parent().attr("id");
		return;
	});		
		
	//loading stream of the clicked div-prev and replace previously opened stream (if he exists) by the div-prev
	$(document).on("click", ".stream-ov", function() {
		
		stream_status = "reduced";
		
		$(this).fadeOut("fast");
		if (typeof stream_div != "undefined") {
			if($(".reduce-button").attr("src") == "/Web/img/reduce-button.png")
			{
				stream_status = "expanded";
			}			
			close_stream();
		}
		parent_div_content = $(this).parent().html();	//saving content of the div-prev container
		stream_div = $(this).parent();
		stream_div.removeClass("col-xl-3 col-lg-4 col-sm-6 div-prev");
		$(stream_div).removeAttr('style');
		stream_div.addClass("col-sm-12 stream-container");
		if($("#source-"+stream_div.attr('id')).attr('value') == 'Youtube')
		{
			stream_div.addClass("col-sm-12 youtube-stream");
			stream_div.html("<img alt=\"close stream button\" class=\"close-button\" src=\"/Web/img/close.png\" /><div id=\"stream-border\"><iframe class=\"col-md-8 col-sm-12\" id=\"stream\" src=\""+$("#stream-"+stream_div.attr('id')).attr('value')+"\" allowfullscreen frameborder=\"0\" scrolling=\"no\" ></iframe><img alt=\"expand stream button\" class=\"expand-button\" src=\"/Web/img/expand-button.png\" /><div class=\"col-md-4 col-sm-12\" id=\"chat\" ><img alt=\"youtube not found\" class=\"youtube-not-found\" src=\"/Web/img/youtube-not-found.png\" /><p>Unfortunately, chat from Youtube is not available</p><p class=\"youtube-link\">You can open it in a new window <a id=\"yt_chat_link\" alt=\"Youtube Chat\" href=\""+$("#chat-"+stream_div.attr('id')).attr('value')+"\" target=\"_blank\" onclick=\"window.open('"+$("#chat-"+stream_div.attr('id')).attr('value')+"', 'newwindow', 'width=350,height=600'); return false;\">here</a></p></div></div>");
		}
		else
		{
			stream_div.html("<img alt=\"close stream button\" class=\"close-button\" src=\"/Web/img/close.png\" /><div id=\"stream-border\"><iframe class=\"col-md-8 col-sm-12\" id=\"stream\" src=\""+$("#stream-"+stream_div.attr('id')).attr('value')+"\" allowfullscreen frameborder=\"0\" scrolling=\"no\" ></iframe><img alt=\"expand stream button\" class=\"expand-button\" src=\"/Web/img/expand-button.png\" /><iframe class=\"col-md-4 col-sm-12\" id=\"chat\" src=\""+$("#chat-"+stream_div.attr('id')).attr('value')+"\"frameborder=\"0\" scrolling=\"no\"></iframe></div>");
		}
				
		$(".close-button").mouseenter(function(){
			$("#stream-border").css({
				"box-shadow": "0px 0px 5px 3px #f4c402",			
			});
		});
		$(".close-button").mouseleave(function(){
			$("#stream-border").css({
				"box-shadow": "none",			
			});
		});
		
		$(document).on("click", ".close-button", function() {
			if($(".reduce-button").length)
			{
				reduce_stream();
			}
			close_stream();
		});
		
		if(stream_status == "expanded")
		{
			expand_stream();
		}
		
		$(document).on("click", ".expand-button", function() {
			expand_stream();
		});
		
		$(document).on("click", ".reduce-button", function() {
			reduce_stream();
		});
		
		scroll_to_stream();	
		
	});
}
		
function show_overlay()
{
	$(".div-prev, .div-prev-navbar").mouseenter(function() {
		$(".overlay").stop();
		$(".overlay").hide();
		var overlay = $(this).find(".overlay");
		
		overlay.css({
			position: "absolute",
			top: $(this).find(".preview").position().top,
			left: $(this).find(".preview").position().left,	
			width: $(this).width(),
			height: $(this).height(),
		});
			
		if($(this).attr("class")=="col-sm-6 col-sm-2 div-prev-navbar" || $(this).attr("class")=="col-sm-12 col-sm-4 div-prev-navbar")
		{
			overlay.css({
				position: "absolute",
				top: 0,
				left: 0,
			});
		}

		$(overlay).fadeIn("fast");
		$(overlay).find(".play-stream").height($(this).height()*0.3);
		
	});
}

function hide_overlay()
{
	$(".div-prev, .div-prev-navbar").mouseleave(function() {
		$(this).find(".overlay").stop();
		$(this).find(".overlay").fadeOut("fast");
	});
}


function load_more()
{	
	load_more_div = $("#load-more-div").html();
	if(load_more_div != undefined)
	{
		var load_more_txt = "streams";
		var offset=$("#offset").val();
		var type=($("#type").val());
			var source_json = JSON.stringify(source_array);
			
		if(type=="games")
		{
			load_more_txt = "games"; 
			var add_to_offset = 24;
			var max_offset = 72;
			var url = 'https://vigas.tv/index.php?action=games&offset='+offset+'&requested_by=ajax';
		}
		else if(type=="streams-by-game")
		{
			var add_to_offset = 36;
			var max_offset = 144;
			var url = 'https://vigas.tv/index.php?action=streams-by-game&game='+($("#game").val())+'&offset='+offset+'&source_json='+source_json+'&requested_by=ajax';
		}
		else
		{
			var add_to_offset = 36;
			var max_offset = 144;
			var url = 'https://vigas.tv/index.php?offset='+offset+'&source_json='+source_json+'&requested_by=ajax';
		}	

		$(window).off("scroll");

		if(parseInt($("#offset").val()) < max_offset)
		{
			$('#load-more-div').css({ display: "none" });
			$('#'+type+'-display').after('<div id="loading-gif" class="col-sm-12"><img style="display: block; margin: auto;" src="/Web/img/loading.gif" /></div>');
			var xhr = new XMLHttpRequest();
			xhr.open('GET', url);
			xhr.addEventListener('readystatechange', function() {
				if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
					document.getElementById(type+'-display').innerHTML += xhr.responseText;
					$("#offset").val(parseInt($("#offset").val()) + add_to_offset);
					show_overlay();
					hide_overlay();
					$(window).scroll(function() {
						if(Math.ceil($(window).scrollTop()) + $(window).innerHeight() >= $(document).height() && $('#'+type+'-display p.alert-warning').length != 1) {
							load_more();
						}
					});
					$('#load-more').click(function() {
						load_more();
					});
					if(parseInt($("#offset").val()) == max_offset)
					{
						document.getElementById("load-more-div").innerHTML='';
						$("#load-more-div").css({ height: "0px" });
						
					}
					else
					{
						document.getElementById("load-more-div").innerHTML='<button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more '+load_more_txt+'</button>';
						$("#load-more-div").removeAttr('style');	
					}
					$('#loading-gif').remove();
					alert('tot');
					console.log($('#content .preview').length + $('#stream').length);
					console.log($('#content .preview').length);
					console.log(parseInt($("#offset").val()));
					console.log(('#'+type+'-display p.alert-warning').length);
					if($('#content .preview').length + $('#stream').length < parseInt($("#offset").val()) || $('#'+type+'-display p.alert-warning').length == 1)
					{
						
						$('#load-more-div').remove();
					}
				}
			});
			xhr.send(null);	
		}
	}
}

//reload the content according to the selected source(s)
function reload(id)
{
	source_array = [];
	var i=0;
	var type=($("#type").val());
	
	if(id=="All")
	{
		if($('#All').is(':checked'))
		{
			$(".source-choice input[type=checkbox]").prop('checked', true);
		}
	}
	else
	{
		if(!$('#'+id).is(':checked'))
		{
			$("#All").prop('checked', false);
			$('#'+id).prop('checked', false);
		}
		if(type == 'streams')
		{
			if($('#Twitch').is(':checked') && $('#Smashcast').is(':checked') && $('#Youtube').is(':checked'))
			{
				$("#All").prop('checked', true);
			}
		}
		else
		{
			if($('#Twitch').is(':checked') && $('#Smashcast').is(':checked'))
			{
				$("#All").prop('checked', true);
			}
		}		
	}
	$(".source-choice input[type=checkbox]:checked").each(
		function() { 
		   source_array[i]=$(this).attr("id");
		   i++;
		} 
	);

	var source_json = JSON.stringify(source_array);
	
    if(type=="following")
	{
            var url = 'https://vigas.tv/index.php?action=following&offset=0&source_json='+source_json+'&requested_by=ajax';
	}
	else if(type=="streams-by-game")
	{
            var url = 'https://vigas.tv/index.php?action=streams-by-game&game='+($("#game").val())+'&offset=0&source_json='+source_json+'&requested_by=ajax';
	}
	else
	{
            var url = 'https://vigas.tv/index.php?offset=0&source_json='+source_json+'&requested_by=ajax';
	}	

	$('#load-more-div').css({ display: "none" });
	$('#'+type+'-display').css({ display: "none" });
	$('#'+type+'-display').after('<div id="loading-gif" class="col-sm-12"><img style="display: block; margin: auto;" src="/Web/img/loading.gif" /></div>');
	$(window).off("scroll");
	
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('readystatechange', function() {
		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			$('#'+type+'-display').css({ display: "block" });
			document.getElementById(type+'-display').innerHTML = xhr.responseText;
			$("#offset").val(36);
			if($("#load-more-div").html() == undefined)
			{
				if($('#'+type+'-display p.alert-warning').length != 1 && $('.stream-infos').length >= 36)
				{
					$('#'+type+'-display').after('<div id="load-more-div"><button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button></div>');
				}		
			}
			else
			{
				if($('.stream-infos').length >= 36)
				{
					document.getElementById("load-more-div").innerHTML='<button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>';
				}
			}	
			show_overlay();
			hide_overlay();
			$(window).scroll(function() {
				if(Math.ceil($(window).scrollTop()) + $(window).innerHeight() >= $(document).height() && $('#'+type+'-display p.alert-warning').length != 1) {
					load_more();
				}
			});
			$('#load-more').click(function() {
				load_more();
			});
			$('#loading-gif').remove();
			$('#'+type+'-display').css('display','');
			//document.getElementById('source-choice-loading').innerHTML = "";
			if(source_json == '[]')
			{
				document.getElementById("load-more-div").innerHTML='';
				$("#load-more-div").css({ height: "0px" });
				
			}
			
			if($('#content .preview').length < parseInt($("#offset").val()) || $('#'+type+'-display p.alert-warning').length == 1)
			{
				$('#load-more-div').remove();
			}
			
			reduce_stream();
		}
		
	});
	xhr.send(null);
}

function select_feedback()
{
	if($('#message-type').val()=='Bug Report')
	{
            $('#email').parent().append("<p id=\"url-info\" class=\"alert alert-info\">Please provide as much details as you can on the bug (which page you were, what you were doing when the bug appeared, any error message you could have...)</p><div id=\"url-field\" class=\"form-group\"><label for=\"url\">Webpage's URL where the bug appeared</label><input class=\"form-control\" id=\"url\" name=\"url\" value=\"\" type=\"text\"></div>");
	}
	else if($('#message-type').val()=='Feedback')
	{
            $('#url-field').remove();
            $('#url-info').remove();
	}
}

$(document).ready(function() {
	load_stream();		

	if(typeof $('#id-stream').val() != "undefined")
	{
		if(typeof $('#'+$('#id-stream').val()).attr('id') != "undefined")
		{
			$('#'+$('#id-stream').val()).find(".stream-ov").trigger("click");
			$('#id-stream').remove();
		}
		else
		{
			alert('stream '+$('#id-stream').val()+' does not exist or streamer is offline.');
		}
	}

	$(window).scroll(function() {
		if(Math.ceil($(window).scrollTop()) + $(window).innerHeight() >= $(document).height()) {
			load_more();
		}
	});
	
	$('#load-more').click(function() {
		load_more();
	});
	
	$('#message-type').change(function() {
		select_feedback();
	});
	
	$("#close-update").click(function() {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', '/manage-update-info/close-update');
		xhr.send(null);
		
	});
	
	$("#dont-show-anymore").click(function() {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', '/manage-update-info/dont-show-anymore');
		xhr.send(null);
		
	});
		
	show_overlay();
	hide_overlay();
		
});
