var stream_div;
var parent_div_content;
var source_array = ["All", "Twitch", "Smashcast"];

function scroll_to_stream()
{
	vertical_marge = $(window).innerHeight() - $('#stream').height();
	console.log($(window).innerHeight());
	console.log($('#stream').height());
	console.log(vertical_marge);
	$('html, body').animate({
		scrollTop:$('#stream').offset().top-(vertical_marge/2)
	}, 'slow');
}

function close_stream()
{
	$('#'+stream_div.attr("id")).html(parent_div_content);		//restore content of the div-prev container
	$('#'+stream_div.attr("id")).removeClass("col-sm-12 stream-container");
	$('#'+stream_div.attr("id")).addClass("col-lg-3 col-md-4 col-sm-6 div-prev");
	$('#'+stream_div.attr("id")).removeAttr('style');
	show_overlay();
	hide_overlay();
}

function expand_stream()
{
	$("#stream").removeClass("col-sm-8");
	$("#chat").removeClass("col-sm-4");
	
	$(".sidebar").addClass("expanded-sidebar");
	$(".header").addClass("expanded-header");
	$(".app").addClass("expanded-app");
	$(".d-lg-none").attr('style','display: block !important');
	
	$(".footer").animate({
		left:0
	}, 'slow', function() {
		$('#stream').width('calc(100% - 300px)');
		$("#stream-border").height($("#stream").width()/16*9);
		$('#chat').width('300px');
		scroll_to_stream();
	});
	
	$(".expand-button").replaceWith("<img alt=\"reduce stream button\" class=\"reduce-button\" src=\"/Web/img/reduce-button.png\" />");
}

function reduce_stream()
{
	$("#stream").removeAttr('style');
	$("#chat").removeAttr('style');
	$("#stream").addClass("col-sm-8");
	$("#chat").addClass("col-sm-4");
	
	$(".sidebar").removeClass("expanded-sidebar");
	$(".header").removeClass("expanded-header");
	$(".app").removeClass("expanded-app");
	$(".d-lg-none").removeAttr('style');
	
	$(".footer").animate({
		left:"240px"
	}, 'slow', function() {
		$('#stream-border').height($('#stream').width()/16*9);
		if($("#stream").length)
		{
			scroll_to_stream();
		}	
	});
	
	$(".reduce-button").replaceWith("<img alt=\"expand stream button\" class=\"expand-button\" src=\"/Web/img/expand-button.png\" />");
}
	
function load_stream()
{	
	//loading stream of the clicked div-prev and replace previously opened stream (if he exists) by the div-prev
	$(document).on("click", ".stream-ov", function() {
		if($(this).parent().attr("class")=="col-sm-12 col-sm-4 div-prev-navbar")
		{
			window.location.href="/stream/"+$(this).parent().attr("id");
			return;
		}
		
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
		stream_div.removeClass("col-lg-3 col-md-4 col-sm-6 div-prev");
		$(stream_div).removeAttr('style');
		stream_div.addClass("col-sm-12 stream-container");
		stream_div.html("<img alt=\"close stream button\" class=\"close-button\" src=\"/Web/img/close.png\" /><div id=\"stream-border\"><iframe class=\"col-sm-8\" id=\"stream\" src=\""+$("#stream-"+stream_div.attr('id')).attr('value')+"\" allowfullscreen frameborder=\"0\" scrolling=\"no\" ></iframe><img alt=\"expand stream button\" class=\"expand-button\" src=\"/Web/img/expand-button.png\" /><iframe class=\"col-sm-4\" id=\"chat\" src=\""+$("#chat-"+stream_div.attr('id')).attr('value')+"\"frameborder=\"0\" scrolling=\"no\"></iframe></div>");
		
		$("#stream-border").height($("#stream").width()/16*9);
		
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
				
		$(window).resize(function(){
			$("#stream-border").height($("#stream").width()/16*9);
		});
		
		$(document).on("click", ".close-button", function() {
			if($(".reduce-button").length)
			{
				reduce_stream();
			}
			close_stream();
			console.log("close expand");
		});
		
		if(stream_status == "expanded")
		{
			expand_stream();
			console.log("expanded expand");
		}
		
		$(document).on("click", ".expand-button", function() {
			expand_stream();
			console.log("click expand");
		});
		
		$(document).on("click", ".reduce-button", function() {
			reduce_stream();
			console.log("click reduce");
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
		var offset=$("#offset").val();
		var type=($("#type").val());
			var source_json = JSON.stringify(source_array);
			
		if(type=="games")
		{
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
						if(Math.ceil($(window).scrollTop()) + $(window).innerHeight() >= $(document).height()) {
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
						document.getElementById("load-more-div").innerHTML='<button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>';
						$("#load-more-div").removeAttr('style');	
					}
					$('#loading-gif').remove();
					if($('#content .preview').length < parseInt($("#offset").val()))
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
		if($('#Twitch').is(':checked') && $('#Smashcast').is(':checked'))
		{
			$("#All").prop('checked', true);
		}
	}
	$(".source-choice input[type=checkbox]:checked").each(
		function() { 
		   source_array[i]=$(this).attr("id");
		   i++;
		} 
	);

	var source_json = JSON.stringify(source_array);
	var type=($("#type").val());
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
				$('#'+type+'-display').after('<div id="load-more-div"><button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button></div>');
			}
			else
			{
				document.getElementById("load-more-div").innerHTML='<button id="load-more" class="btn btn-sm btn-primary load-more-btn">Load more streams</button>';
			}	
			show_overlay();
			hide_overlay();
			$(window).scroll(function() {
				if(Math.ceil($(window).scrollTop()) + $(window).innerHeight() >= $(document).height()) {
					load_more();
				}
			});
			$('#load-more').click(function() {
				load_more();
			});
			$('#loading-gif').remove();
			$('#'+type+'-display').css('display','');
			document.getElementById('source-choice-loading').innerHTML = "";
			if(source_json == '[]')
			{
				document.getElementById("load-more-div").innerHTML='';
				$("#load-more-div").css({ height: "0px" });
				
			}
			if($('#content .preview').length < parseInt($("#offset").val()))
			{
				$('#load-more-div').remove();
			}
	
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
