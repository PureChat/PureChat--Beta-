this.PrivateChatUI = function()
{
	this.open_new = function(response)
	{
		response = {
			id: 1
		}

		$('body').prepend('<div class="private_chat" id="private_chat_' + response.id + '"></div>');

		$('#private_chat_' + response.id).append('<div class="private_chat_header"><div class="private_chat_grabber"></div></div>');
		$('#private_chat_' + response.id + ' .private_chat_header').prepend('<div class="private_chat_button1">Action Button?</div>');
		$('#private_chat_' + response.id + ' .private_chat_header').append('<div class="private_chat_button2">Close Chat</div>');

		$('#private_chat_' + response.id).append('<div class="private_chat_body"></div>');

		$('#private_chat_' + response.id).append('<div class="private_chat_footer"></div>');
		$('#private_chat_' + response.id + ' .private_chat_footer').append('<form action="index.php" method="post" onsubmit="alert(\'go\');"></form>');
		$('#private_chat_' + response.id + ' .private_chat_footer form').append('<div class="private_chat_input_container"><input type="text" class="private_chat_input" id="private_chat_input_' + response.id + '" /></div>');
		$('#private_chat_' + response.id + ' .private_chat_footer form').append('<input type="submit" class="private_chat_submit" value="Post" />');
	}

	this.show_private_chat_button = function(element_id)
	{
		chat_ui.online_list_hovering = true;

		var button_id = 'button_' + element_id;
		var position = $('#' + element_id).offset();
		var id_array = element_id.split('_');
		var last_item = id_array.length-1;
		var id = id_array[last_item];
		
		$('#' + button_id).detach();

		$('body').append('<div class="open_private_chat" id="' + button_id + '">Private Chat</div>');
		$('#' + button_id).offset({top: position.top - 30, left: position.left});
		$('#' + button_id).slideDown(300);
		$('#' + button_id).on('mouseleave', function()
		{
			pcui.hide_private_chat_button(element_id);
		});
		$('#' + button_id).on('click', function()
		{
			pch.load_private_chat(id);
		});
	}

	this.hide_private_chat_button = function(element_id)
	{
		ui.online_list_hovering = false;
		
		var button_id = 'button_' + element_id;

		$('#' + button_id).fadeOut(300, function()
		{
			$(this).detach();
		})
	}
}

var pcui = new PrivateChatUI;