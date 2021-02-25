$(document).ready(function()
{
	$("#update").click(function(event)
	{
		event.preventDefault();
		
		$("#dimmer").fadeIn("slow");
		$("#message_box").fadeIn("Slow");
	});
	
	$("#close").click(function(event)
	{
		event.preventDefault();
		
		$("#dimmer").fadeOut("fast");
		$("#message_box").fadeOut("fast");
	});
});