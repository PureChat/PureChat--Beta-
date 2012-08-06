this.PrivateChatHandler = function()
{
	this.load_private_chat = function(member_id)
	{
		pcui.open_new();
	}
}

var pch = new PrivateChatHandler;

$(document).ready(function()
{
	//pcui.bind_private_chat_launcher();
});