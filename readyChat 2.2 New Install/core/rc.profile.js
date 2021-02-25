/*
	ReadyChat 2.2.0
	Profile JavaScript
*/

var saving = false;

// Function to open pages in a new window
function url(title, url, width, height) 
{
	var left = (screen.width/2)-(width/2);
	var top = (screen.height/2)-(height/1);
	
	newwindow=window.open(url, title, 'height='+height+', width='+width+', scrollbars=yes, top='+top+', left='+left);
	newwindow.focus();
	
	return false;
}

$( document ).ready(function() {
	$(document).on("click", ".close, .overlay", function()
	{
		event.preventDefault();
		
		if (!saving)
		{
			$(".overlay").fadeOut();
			$(".modal").fadeOut();
		}
	});
	
	$("form").submit(function(event)
	{
		event.preventDefault();
		
		$("#loader").removeClass("tick");
		$("#loader").addClass("loading");
		$("#waitText").html("Please Wait<br /><span style=\"font-size:13px;\">Saving your profile</span>");
		
		$(".overlay").fadeIn();
		$("#saving").fadeIn();
		
		var 
			bio = $("#bio_text").val(),
			gender = $("#gender_text").val(),
			location = $("#location_text").val()
		;
		
		$.ajax(
		{
			type: "POST",
			url: "core/rc.profileSave.php",
			data: {save: "1", bio_text: bio, gender_text: gender, location_text: location},
			
			success: function(response) 
			{
				saving = true;
				res = response.split("|");
				
				$("#loader").removeClass("loading");
				$("#loader").addClass("tick");
				$("#waitText").html("Profile Saved!<br /><span style=\"font-size:13px;\"><a class=\"close\">Close Window</a></span>");
				
				saving = false;
			}
		});
	});
});