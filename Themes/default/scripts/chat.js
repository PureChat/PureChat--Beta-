/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/scripts/main.js
 * @author The PureChat Team
 * @copyright 2012 PureChat.org <http://www.purechat.org>
 * @license GPL <http://www.gnu.org/licenses/>
 *
 * @version 0.0.9 (Alpha)
 */
/**
 * This file is part of PureChat.

 * PureChat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PureChat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PureChat.  If not, see <http://www.gnu.org/licenses/>.
 */

var PureChatSource = function()
{

	this.post_new = function()
	{
		var message = $('#message_input').val();
		chat_ui.clear_field();

		// The field is empty, we might as well quit now...
		if (!message)
			return false;

		if (irc.test(message) == false)
		{
			ajax_in_progress = true;
			$.ajax({
				url: pc_script + '?action=chat_controller&perform=add_post&ajax_connection=true',
				type: 'POST',
				data: {post: message},
				success: function()
				{
					ajax_in_progress = false;
					chat_ui.detach_old();
				}
			});
		}
		return false;
	}

	this.remove_post = function(input_obj)
	{
		ajax_in_progress = true;
		$.ajax({
			url: pc_script + '?action=chat_controller&perform=remove_post&ajax_connection=true',
			type: 'POST',
			data: {message_obj: input_obj},
			dataType: 'json',
			success: function(response)
			{
				ajax_in_progress = false;
				if (response == null || parseInt(response.id_msg) == 0)
					return false;
				chat_ui.remove_message(response);
			}
		});
	}

	this.load_online_list = function()
	{
		ajax_in_progress = true;
		$.ajax({
			url: pc_script + '?action=online_list&perform=return_list&ajax_connection=true',
			dataType: 'json',
			success: function(response)
			{
				ajax_in_progress = false;

				// This should never happen, but you can't be too careful.
				if (response == null || parseInt(response.total) == 0)
					return false;

				chat_ui.update_online_list(response);
			}
		});
	}

	this.refresh = function()
	{
		ajax_in_progress = true;
		$.ajax({
			url: pc_script + '?action=chat_controller&perform=load_new&ajax_connection=true',
			type: 'POST',
			dataType: 'json',
			data: {last: pc_last_message},
			success: function(response)
			{
				ajax_in_progress = false;
				chat_ui.show_new(response);
			}
		});
	}

	this.repeater = function()
	{
		s.call_waiting(s.refresh);
		s.call_waiting(s.load_online_list);

		// Repeat this method every second.
		setTimeout(function()
		{
			s.repeater();
		}, 1500);
	}

	

	this.call_waiting = function(callback)
	{
		if (!ajax_in_progress)
		{
			if (callback != null)
				callback();

			return false;
		}
		else if (ajax_in_progress)
		{
			setTimeout(function()
			{
				s.call_waiting(callback);
			}, 0100);
		}
	}
}
var s = new PureChatSource;

$(document).ready(function()
{
	// Start loading informations. :D
	s.repeater();
});