/*
	ReadyChat 2.2.0
	Main JavaScript File
*/

// Variables
var oldPrivs = 0;
var newPrivs = 0;
var c_timestamp = Math.floor(new Date().getTime() / 1000);
time = Math.floor(new Date().getTime() / 1000);
last_time = Math.floor(new Date().getTime() / 1000);

// Configure audio alerts
var sfx_newMessage = document.createElement('audio');
var sfx_outgoing = document.createElement('audio');
var sfx_newPrivate = document.createElement('audio');

sfx_newMessage.setAttribute('src', 'library/audio/sfx_newMessage.mp3');
sfx_outgoing.setAttribute('src', 'library/audio/sfx_outgoing.mp3');
sfx_newPrivate.setAttribute('src', 'library/audio/sfx_newPrivate.mp3');

// Function to open pages in a new window
function url(title, url, width, height) 
{
	var left = (screen.width/2)-(width/2);
	var top = (screen.height/2)-(height/1);
	
	newwindow=window.open(url, title, 'height='+height+', width='+width+', scrollbars=yes, top='+top+', left='+left);
	newwindow.focus();
	
	return false;
}

// Function to use smilies
function smiley(myField, myValue)
{
	if (document.selection) 
	{
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	} 
	else if (myField.selectionStart || myField.selectionStart == '0') 
	{
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
	} 
	else 
	{
		myField.value += myValue;
	}
	
	// Auto-close smiley box
	$('#dimmer').fadeOut('fast', function() {});
	$('#smiley_box').fadeOut('fast', function() {});
	event.preventDefault();
}

// Function to display random messages to guests
function showRoom()
{
	var rms = ["You can define random messages in core/chat.js!", 
			   "Need help? Check out our <a href='documents/help.html' target='_blank'>Help Document</a>!"
			  ];
	
	$('#rm').hide();
	$('#rm').html(rms[Math.floor(Math.random() * rms.length)]);
	$('#rm').fadeIn('fast', function() {});
};

showRoom();

// Function to refresh private messages
function newpvts()
{ 
	$('#pvt_head').load("core/rc.listings.php?list=pms"); 
	
	// Count new messages
	oldPrivs = newPrivs;
	newPrivs = $('#new_count').text();
	
	if (newPrivs > oldPrivs)
	{
		sfx_newPrivate.play();
		document.title = siteTitle + " * New PM *";
	}
	
	if (newPrivs == 0)
	{
		document.title = siteTitle;
	}
};

// Function to refresh active users
function activeUsers()
{ 
	$('#user_list').load("core/rc.listings.php?list=active"); 
};

// Function to load the room topic
function roomTopic()
{ 
	$('#topic_text').load("core/rc.listings.php?list=topic&rid="+room); 
}

// Function to switch the room background
function roomBackground()
{ 
	$.ajax({
		url: "core/rc.listings.php?list=background&rid="+room,
		cache: false,
		success: function(html)
		{
			if ($.trim(html) != "nobg")
			{
				$('#messages').css('background-image', 'url(' + html + ')');
			}
			else
			{
				$('#messages').css('background-image', 'none');
			}
		},
	});
}

// Close all dialogs
function rcCloseDialogs()
{
	$('#dimmer').fadeOut('fast', function() {});
	$('#message_box').fadeOut('fast', function() {});
	$('#box_entry').fadeOut('fast', function() {});
	$('#top_error').fadeOut('fast', function() {});
	$('#smiley_box').fadeOut('fast', function() {});
	$('#contentbox').fadeOut('fast', function() {});
	$('#rpw').val('');
	
	$("#content").focus();
	inputReady = false;
}

$(document).ready(function()
{
	$("input#content").select().focus();
	
	/*******/
	
	/*
		Generate Smiley List
	*/
	

	var smilies = '';
	var cols = 1;
	var i = 0;

	$.each( emotify.emoticons(), function(k,v)
	{
		i++;
		smilies += emotify( k );
	});
	
	$('#smiley_area').html(smilies);
	
	$('#user_list').load("core/rc.listings.php?list=active"); 
	
	/*
		Auto Scroll
	*/
	
	$("#autoscroll").click(function(event)
	{
		if (autoscroll)
		{
			autoscroll = 0;
			
			this.src = 'template/'+site_template+'/icons/scroll_off.png';
			$("#autoscroll").attr('title', 'Enable Auto Scroll');
		}
		else
		{
			autoscroll = 1;
			
			this.src = 'template/'+site_template+'/icons/scroll.png';
			$("#autoscroll").attr('title', 'Disable Auto Scroll');
		}
		
		event.preventDefault();
	});
	
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
	
	/*
		Close Dialogs
	*/
	
	$("#close, #close_smilies, #close_profile").click(function (event) 
	{
		rcCloseDialogs();
		event.preventDefault();
	});
	
	$(document).keyup(function(key) 
	{
		if (key.keyCode == 27) 
		{
			rcCloseDialogs();
		}
	});
	
	/*
		Smilies
	*/
	
	$("#smilies").click(function (event) 
	{
		$('#dimmer').fadeIn('fast', function() {});
		$('#smiley_box').fadeIn('fast', function() {});
		
		$('#content').blur();
	});
	
	/*
		Embedded Profiles
	*/
	
	$(document).on('click', '.user', function (event)
	{	
		embedded = $(this).attr("embedded");
		
		if (embedded == 1 && !kicked)
		{
			event.preventDefault();
			$('.progress-indicator').fadeIn('fast', function() {});
			
			view_profile = $(this).attr("profile_id");
			profile_title = $(this).attr("user_name");
			
			$.ajax({
				url: "core/rc.loadProfile.php?profile_id=" + view_profile,
				cache: false,
				success: function(html)
				{
					if (html != 2)
					{
						$("#contentbox_html").html(html);
						$('#content_title').html(profile_title+"'s Profile");
						$('#close_profile').text("Close Profile");
						
						$('.progress-indicator').fadeOut('fast', function() {});
						
						if (view_profile != my_id)
						{
							$('#private_chat').html("<a id=\"private\" href=\"#private\" onclick=\"url('Private Chat', 'private_chat.php?cid="+view_profile+"&who="+profile_title+"', '600', '460')\">Private Chat</a>");
						}
						else
						{
							$('#private_chat').html("");
						}
						
						$('#dimmer').fadeIn('fast', function() {});
						$('#contentbox').fadeIn('fast', function() {});
						$('#content').blur();
					}
					else
					{
						$('.progress-indicator').fadeOut('fast', function() {});
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#message_title').html(lang.error_profile);
						$('#message_area').html(lang.error_profile_txt);
						$('#content').blur();					
					}
				},
			});
		}
	});
	
	/*
		Flash Games
	*/
	
	$("#games").click(function (event) 
	{
		$('#dimmer').fadeIn('fast', function() {});
		
		$.ajax({
			url: "arcade.php",
			cache: false,
			success: function(html)
			{
				$("#message_area").html(html);
				$('#message_box').fadeIn('fast', function() {});
				$('#message_title').html(lang.flash_games);
				$('#content').blur();
			},
		});
	});
	
	/*
		Credits
	*/
	
	$("#credits").click(function (event) 
	{
		$('#dimmer').fadeIn('fast', function() {});
		
		$("#message_area").html("Software: readyChat<br />Release Version: 2.2.0<br />Developer: DesignSkate<br /><br /><a href='http://designskate.com/readychat' target='_blank' style='text-decoration:underline'>Developer Website</a>");
		$('#message_box').fadeIn('fast', function() {});
		$('#message_title').html("readyChat Information");
		$('#content').blur();
	});
	
	/*
		Display Active Users
	*/
	
	$('#user_list').load("core/rc.listings.php?list=active");

	/*
		Private Messages
	*/
	
	if (pms)
	{
		$('#pvt_head').load("core/rc.listings.php?list=pms");
		
		$("#pvt_head").click(function (event) 
		{
			$('.progress-indicator').fadeIn('fast', function() {});
			
			$.ajax({
				url: "core/rc.listings.php?list=newpms",
				cache: false,
				success: function(html)
				{
					$("#message_area").html(html);
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html("New Private Messages");
					$('.progress-indicator').fadeOut('fast', function() {});
				},
			});
			
			$('#content').blur();
		});		
	}
	
	/*
		Room Topic
	*/
	
	$('#topic_text').load("core/rc.listings.php?list=topic&rid="+room);
	
	/*
		Room Background
	*/
	
	roomBackground();
	
	/*
		Switch Rooms
	*/
	
	$(document).on('click', '.room', function ()
	{	
		var gotoroom = $(this).attr("room_link");
		var roomtitle = $(this).attr("room_title");
		
		if (gotoroom == room)
		{
			$('#dimmer').fadeIn('fast', function() {});
			$('#message_box').fadeIn('fast', function() {});
			$('#message_title').html(lang.error_room);
			$('#message_area').html(lang.error_room_txt);
			$('#content').blur();
			
			return false;
		}
		else if (kicked == 1)
		{
			$('#dimmer').fadeIn('fast', function() {});
			$('#message_box').fadeIn('fast', function() {});
			$('#message_title').html(lang.error_room);
			$('#message_area').html(lang.error_gen);
			$('#content').blur();
			
			return false;
		}
		else
		{
			roomKey = $('#rpw').val();
			
			if (inputReady)
			{
				if (roomKey == "")
				{
					$('#top_error').html("Enter a password!");
					$('#top_error').fadeIn('fast', function() {});				
				}
				else
				{
					$('#top_error').html("<img src=\"template/"+site_template+"/images/loading_small.gif\">");
					$('#top_error').fadeIn('fast', function() {});
				}
			}
			else
			{
				$('.progress-indicator').fadeIn('fast', function() {});
			}
			
			$.ajax({
				type: 'POST',
				url: 'core/rc.swapRoom.php',
				data: "nid="+gotoroom+"&key="+roomKey,
				success:function(response)
				{
					if (response == 3)
					{
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#message_title').html(lang.alert);
						$('#message_area').html(lang.error_room_3);
						$('#content').blur();
						
						$('.progress-indicator').fadeOut('fast', function() {});
					}
					else if (response == 2)
					{
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#message_title').html(lang.alert);
						$('#message_area').html(lang.error_room_2);
						$('#content').blur();
						
						$('.progress-indicator').fadeOut('fast', function() {});
					}
					else if (response == 4 || response == 5)
					{
						inputReady = true;
						
						if (response == 5)
						{
							$('#top_error').fadeIn('fast', function() {});
							$('#top_error').html("Invalid Password");
						}
						
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#message_title').html(lang.password);
						$('#message_area').html(lang.password_txt);
						$("#rtj").attr('room_link', gotoroom);
						$("#rtj").attr('room_title', roomtitle);
						$('#box_entry').fadeIn('fast', function() {});
						$('#close').html("Cancel");
						$("#rpw").focus();
						$('#content').blur();
						
						$('.progress-indicator').fadeOut('fast', function() {});
					}
					else
					{
						room = gotoroom;
						inputReady = false;
						$("#messages").append("<div class='cm'>" + lang.first_join + " <strong>" + roomtitle + "</strong><div class='underline'></div>");
						$("#messages").animate({scrollTop:$("#messages")[0].scrollHeight}, 1000); 
						
						activeUsers();
						roomTopic();
						roomBackground();
						poll_for_new_messages();
						rcCloseDialogs();
						showRooms();
						
						$('.progress-indicator').fadeOut('fast', function() {});
					}
				}
			});		
		}
	});
	
	$('form').submit(function()
	{
		if (kicked == 1) 
		{
			$('#dimmer').fadeIn('fast', function() {});
			$('#message_box').fadeIn('fast', function() {});
			$('#message_title').html(lang.alert);
			$('#message_area').html(lang.notconnected);
			$('#content').blur();	
			return false;
		}
		else
		{
			var form = $(this);
			var name =  form.find("input[name='name']").val();
			var content =  form.find("input[name='content']").val();
			var token =  form.find("input[name='admin_token']").val();
			var token_c = form.find("input[name='token']").val();
			
			if (content == "/prune" || content.indexOf("/kick") >= 0 || content.indexOf("/ban") >= 0 || content.indexOf("/warn") >= 0 || content.indexOf("/announce") >= 0)
			{
				$.ajax({
					type: 'POST',
					url: 'core/rc.cmd.php',
					data: "cmd="+content+"&token="+token,
					success:function(response)
					{						
						if (response == 20)
						{
							$('#message_title').html(lang.action_1);
							$('#message_area').html(lang.room_pruned);	
							$('#content').blur();		
							form.find("input[name='content']").val('');								
							document.getElementById("messages").innerHTML = "";
							$('<small class="join" />').text(lang.pruned_public).appendTo('ul#messages');
							$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
						}
						if (response == 21)
						{
							$('#message_title').html(lang.action_1);
							$('#message_area').html(lang.kicked_atxt);		
							form.find("input[name='content']").val('');		
							$('#content').blur();			
							
							activeUsers();
						}
						if (response == 22)
						{
							$('#message_title').html(lang.action_1);
							$('#message_area').html(lang.banned_atxt);		
							form.find("input[name='content']").val('');		
							$('#content').blur();					
							activeUsers();
						}
						if (response == 23)
						{
							$('#message_title').html(lang.action_1);
							$('#message_area').html(lang.warned_atxt);		
							form.find("input[name='content']").val('');		
							$('#content').blur();						
						}
						if (response == 24)
						{
							$('#message_title').html(lang.action_1);
							$('#message_area').html(lang.announce_sent);		
							form.find("input[name='content']").val('');	
							$('#content').blur();	
						}
						else if (response == 2)
						{
							$('#message_title').html(lang.action_2);
							$('#message_area').html(lang.action_3);				
						}
						else if (response == 4)
						{
							$('#message_title').html(lang.action_4);
							$('#message_area').html(lang.action_5);	
						}
						
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#content').blur();
					}
				});
				
				return false;
			}
			else
			{			
				form.find("input[name='content']").val('').focus();
				$.ajax({
					type: 'POST',
					url: 'core/rc.sendChat.php',
					data: {content: content, room: room, token: token_c, timestamp: c_timestamp},
					success:function(response)
					{
						if (response == 1)
						{
							data = response.split ( "(nxt)" );
							
							if ($.trim(data[0]) == '')
							{
								return false;
							}
							
							if (autopoll)
							{
								poll_for_new_messages();
							}
							
							lastMsg = Math.floor(new Date().getTime() / 1000);
						}
						else if (response == 2)
						{
							$('#dimmer').fadeIn('fast', function() {});
							$('#message_box').fadeIn('fast', function() {});
							$('#message_title').html(lang.alert);
							$('#message_area').html(lang.error_blank);
							$('#content').blur();
						}
						else if (response == 3)
						{
							$('#dimmer').fadeIn('fast', function() {});
							$('#message_box').fadeIn('fast', function() {});
							$('#message_title').html(lang.alert);
							$('#message_area').html(lang.spam);
							$('#content').blur(); 
						}
					}
				});
				
				if ($.trim(content) != '')
				{
					currentTime = Math.floor(new Date().getTime() / 1000);
					if (Math.floor(lastMsg) + spamTimer < Math.floor(currentTime) || lastMsg == 0)
					{
						$("#content").blur();
						$('<li />').html(emotify(content)).prepend($('<small style="color:'+colourHEX+'" />').text(name)).appendTo('ul#messages');
						
						if (autoscroll)
						{
							$('#messages').scrollTop( $('#messages').get(0).scrollHeight );
						}
						
						if (audioAlerts)
						{
							sfx_outgoing.play();
							console.log(audioAlerts);
						}
					}
					else
					{
						$('#dimmer').fadeIn('fast', function() {});
						$('#message_box').fadeIn('fast', function() {});
						$('#message_title').html(lang.alert);
						$('#message_area').html(lang.spam);
						$('#content').blur(); 						
					}
				}
				
				$("#content").focus();
				return false;
			}
		}
	});
	
	poll_for_new_messages = function()
	{
		var oldscrollHeight = $("#messages").attr("scrollHeight") - 20;
		
		$.ajax({
			url: "core/rc.notify.php",
			cache: false,
			success: function(response)
			{
				data = response.split ( "(nxt)" );
				
				if (data[0] == 5)
				{
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html(lang.warning);
					$('#message_area').html(data[1]);
					$('#content').blur();						
				}
				else if (response == 1)
				{
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html(lang.kicked);
					$('#message_area').html(lang.kicked_txt);
					$('#content').blur();	
					
					$('#topic').fadeOut('fast', function() {});
					$('#topic_text').fadeOut('fast', function() {});
					$('#alert').fadeIn('fast', function() {});
					$('#alert_text').html(lang.kicked_ntxt);
					
					room = 0;
					gotoroom = -1;
					kicked = 1;

					clearInterval(poll);
					clearInterval(user);
					clearInterval(rm);
					clearInterval(userList);
					clearInterval(pvts);
					return false;
				}
				else if (response == 2)
				{
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html(lang.banned);
					$('#message_area').html(lang.banned_txt);
					$('#content').blur();	
					
					$('#topic').fadeOut('fast', function() {});
					$('#topic_text').fadeOut('fast', function() {});
					$('#alert').fadeIn('fast', function() {});
					$('#alert_text').html(lang.banned_ntxt);

					room = 0;
					gotoroom = -1;
					kicked = 1;

					clearInterval(poll);
					clearInterval(user);
					clearInterval(rm);
					clearInterval(userList);
					clearInterval(pvts);
					return false;
				}
				else if (response == 3)
				{
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html(lang.inactive);
					$('#close').fadeOut('fast', function() {});
					$('#message_area').html(lang.inactive_txt);
					$('#content').blur();	
					
					$('#user_list').html(lang.inactive_userlist);
					
					kicked = 1;

					clearInterval(poll);
					clearInterval(user);
					clearInterval(rm);
					clearInterval(userList);
					clearInterval(pvts);
					return false;
				}
				else if (response == 4)
				{
					$('#dimmer').fadeIn('fast', function() {});
					$('#message_box').fadeIn('fast', function() {});
					$('#message_title').html(lang.alert);
					$('#close').fadeOut('fast', function() {});
					$('#message_area').html(lang.reset);
					$('#content').blur();	
					
					$('#user_list').html(lang.inactive_userlist);
					
					room = 0;
					gotoroom = -1;
					kicked = 1;

					clearInterval(poll);
					clearInterval(user);
					clearInterval(rm);
					clearInterval(userList);
					clearInterval(pvts);
					return false;
				}
			}
		});
		
		$.ajax({
			url: "core/rc.loadRoom.php?lastpoll=" + last_time + "",
			cache: false,
			success: function(html)
			{
				if ($.trim(html) != "N/N" && $.trim(html) != "")
				{
					$("#messages").append(emotify(html));
				
					if (autoscroll)
					{
						$("#messages").animate({scrollTop:$("#messages")[0].scrollHeight}, 1000); 
					}
				
					if (audioAlerts)
					{
						sfx_newMessage.play();
					}
				}
				
				last_time = Math.floor(new Date().getTime() / 1000);
			},
		});
	};
	
	showRooms = function()
	{
		$.ajax({
			url: "core/rc.roomList.php",
			cache: false,
			success: function(html)
			{
				$("#rooms_list").html(html);
			},
		});
	}
	
	showRooms();
	poll_for_new_messages();
	
	var poll = setInterval(poll_for_new_messages, 3000);
	var user = setInterval(activeUsers, 5000);
	var userList = setInterval(showRooms, 12000);
	var rm = setInterval(showRoom, 60000);
	var pvts = setInterval(newpvts, 8000);
});