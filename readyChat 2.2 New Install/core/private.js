/*
	ReadyChat 2.2.0
	Private Chat JavaScript
*/

// Configure audio alerts
var sfx_newMessage = document.createElement('audio');
var sfx_outgoing = document.createElement('audio');

sfx_newMessage.setAttribute('src', 'library/audio/sfx_newMessage.mp3');
sfx_outgoing.setAttribute('src', 'library/audio/sfx_outgoing.mp3');

// Close all dialogs
function rcCloseDialogs()
{
	$('#dimmer').fadeOut('fast', function() {});
	$('#message_box').fadeOut('fast', function() {});
	$('#box_entry').fadeOut('fast', function() {});
	$('#top_error').fadeOut('fast', function() {});
	$('#contentbox').fadeOut('fast', function() {});
	$('#rpw').val('');
	
	$("#content").focus();
	inputReady = false;
}

$(document).ready(function(){
	$("input#content").select().focus();
	
	/*
		Sound Effects
	*/
	
	$("#sfx").click(function(event)
	{
		if (audioAlerts)
		{
			audioAlerts = 0;
			
			this.src = 'template/'+site_template+'/icons/sfx_off.png';
			$("#sfx").attr('title', 'Click to enable sound effects');
		}
		else
		{
			audioAlerts = 1;
			
			this.src = 'template/'+site_template+'/icons/sfx_on.png';
			$("#sfx").attr('title', 'Click to disable sound effects');
		}
		
		event.preventDefault();
	});
	
	$('form').submit(function()
	{
		var form = $(this);
		var name =  form.find("input[name='name']").val();
		var content =  form.find("input[name='content']").val();
		var token_c = form.find("input[name='token']").val();
	
		if (content != '')
		{
			$("#content").blur();
			
			var currentTime = new Date().getTime() / 1000;
			if (Math.floor(lastMsg) + spamTimer < Math.floor(currentTime) || lastMsg == 0)
			{
				$('<li />').text(content).prepend($('<small />').text(""+name)).appendTo('ul#messages');
				$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
				
				if (audioAlerts)
				{
					sfx_outgoing.play();
				}
			}
			else
			{
				alert("too fast");
			}
		}
		
		form.find("input[name='content']").val('').focus();
		$.ajax({
			type: 'POST',
			url: 'core/rc.privateSend.php',
			data: {content: content, token: token_c, cid: cid},
			success:function(response)
			{	
				poll_for_new_messages();
				
				if (response == 1)
				{
					if (content == '')
					{
						return false;
					}
					
					lastMsg = new Date().getTime() / 1000;
				}
			}
		});
		
		$("#content").focus();
		return false;
	});
	
	poll_for_new_messages = function()
	{	
		$.ajax({
			url: "core/rc.privateMain.php?cid=" + cid + "&lastpoll=" + time + "&initialLoad=" + initialLoad,
			cache: false,
			success: function(html)
			{
				if ($.trim(html) != "N/N")
				{
					$("#messages").append(html);
				
					if (autoscroll)
					{
						$("#messages").animate({scrollTop:$("#messages")[0].scrollHeight}, 1000); 
					}
				
					if (audioAlerts)
					{
						sfx_newMessage.play();
					}
					
					time = new Date().getTime() / 1000;
				}
			},
		});	
	}
	
	poll_for_new_messages();
	var poll = setInterval(poll_for_new_messages, 3000);
	initialLoad = 0;
});