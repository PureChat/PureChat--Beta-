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

var ChatInterface = function()
{
	this.online_list_hovering;
	this.focused;
	
	this.scroll_down = function()
	{
		var down = $('#messages').prop('scrollHeight');
		$('#messages').animate({scrollTop: down}, 800);
	}

	this.clear_field = function()
	{
		$('#message_input').val('');
	}

	this.show_new = function(posts)
	{
		if (!posts)
			return false;

		$.each(posts, function(i, post)
		{
			// Let's highlight our name, so we know when we're being spoken to.
			var reg = new RegExp(pc_display_name, 'gi');
			post.text = post.text.replace(reg, '<span class="red">' + pc_display_name + '</span>');

			$('#messages ul').append('<li class="chat_post hidden" id="post_' + post.id + '"><div class="message">' + post.text + '<div class="floatright"><span id="message_timestamp_cont"><em>' + post.time + '</em></span><span id="remove_' + post.id_poster + '_' + post.id + '" onclick="s.remove_post(this.id)" class="remove_message_cont"><img src="' + pc_currentthemeurl + '/images/fugue/16/cross.png" alt="' + pc_lang['remove_msg'] + '" title="' + pc_lang['remove_msg'] + '" /></span></div><br class="clear" /></div><div class="user"><div class="message_pointer"></div>' + post.poster + (post.avatar != null ? '<img src="' + post.avatar + '" alt="" class="avatar" />' : '') + '</div><br class="clear" /></li>');

			++pc_last_message;
		});

		$('#messages ul li.hidden').fadeIn(500);

		chat_ui.scroll_down();

		if (!chat_ui.focused)
			chat_ui.flash_title();
		
		sound.play('new_message');

		return true;
	}

	this.remove_message = function(data)
	{
		var shortcut = '#post_' + data.id_msg;
		$(shortcut).slideUp(300);
		setTimeout(function() {
			$(shortcut).detach();
		}, 300);
	}

	this.detach_old = function()
	{
		var total = $('.chat_post').length;

		while (total > 50)
		{
			$('.chat_post').first().detach();
			--total;
		}
	}

	this.update_online_list = function(data)
	{
		if (data == null || chat_ui.online_list_hovering)
			return false;

		var person_s = data.total < 2 ? pc_lang.person_online : pc_lang.people_online;
		$('#online_text').html(data.total + ' ' + person_s);

		$('#users_online ul').empty();
		$.each(data.members, function(i, info)
		{
			$('#users_online ul').append('<li id="online_list_' + info.id + '" onclick="profile.load_info(' + info.id + ');"><img src="' + info.status.icon + '" alt="" class="status_icon" title="' + info.status.text + '" /> ' + info.name + '</li>');
		});
	}

	

	this.insert_smily = function(param)
	{
		if (document.selection)
		{
			$('#message_input').focus();
			sel = document.selection.createRange();
			sel.text = ' ' + param.data.insert + ' ';
		}

		else if (document.getElementById('message_input').selectionStart || document.getElementById('message_input').selectionStart == '0')
		{
			var startPos = document.getElementById('message_input').selectionStart;
			var endPos = document.getElementById('message_input').selectionEnd;
			document.getElementById('message_input').value = document.getElementById('message_input').value.substring(0, startPos) + ' ' + param.data.insert + ' ' + document.getElementById('message_input').value.substring(endPos, document.getElementById('message_input').value.length);
		}
		else
		{
			document.getElementById('message_input').value += ' ' + param.data.insert + ' ';
		}
	}

	this.insert_bbc = function(param)
	{
		if (document.selection)
		{
			$('#message_input').focus();
			sel = document.selection.createRange();
			sel.text = ' ' + param.data.insert + ' ';
		}

		else if (document.getElementById('message_input').selectionStart || document.getElementById('message_input').selectionStart == '0')
		{
			var startPos = document.getElementById('message_input').selectionStart;
			var endPos = document.getElementById('message_input').selectionEnd;
			document.getElementById('message_input').value = document.getElementById('message_input').value.substring(0, startPos) + ' ' + param.data.insert + ' ' + document.getElementById('message_input').value.substring(endPos, document.getElementById('message_input').value.length);
		}
		else
		{
			document.getElementById('message_input').value += ' ' + param.data.insert + ' ';
		}
	}

	this.status_bar = function(status)
	{
		if (status == null || status == '')
			return false;

		switch (status)
		{
			case 'available':
				$('#status_bar').attr('class', 'green');
				break;

			case 'busy':
				$('#status_bar').attr('class', 'red');
				break;

			case 'away':
				$('#status_bar').attr('class', 'orange');
				break;

			case 'invisible':
				$('#status_bar').attr('class', 'silver');
				break;
		}
	}

	this.bind_events = function()
	{
		$('#smilies_link').click(function()
		{
			$('#list_smilies').show(750);
		});
		$('#tag_image').click(function()
		{
			$('#list_irc').show(750);
		});
		$('#bbc_link').click(function()
		{
			$('#list_bbc').show(750);
		});

		$('#list_smilies').hover('', function()
		{
			$('#list_smilies').slideUp(300);
		});
		$('#list_irc').hover('', function()
		{
			$('#list_irc').slideUp(300);
		});
		$('#list_bbc').hover('', function()
		{
			$('#list_bbc').slideUp(300);
		});

		$(window).on('focus', function()
		{
			$('#message_input').focus();
		});
		$('#message_input').on('focus', function()
		{
			chat_ui.focused = true;
		});
		$('#message_input').on('blur', function()
		{
			chat_ui.focused = false;
		});
	}

	this.load_smilies = function()
	{
		$.each(pc_smilies, function(i, data)
		{
			$('#list_smilies').append('<img src="' + data.img + '" alt="" title="' + data.name + ' - ' + data.code + '" id="sm_' + data.id + '" class="smily_link" />');
			$('#sm_' + data.id).on('click', {insert: data.code}, chat_ui.insert_smily);
		});
	}

	this.load_irc = function()
	{
		$.each(pc_irc_commands, function(i, data)
		{
			$('#list_irc').append('<span id="' + data.id + '" title="' + data.name + ' - ' + data.command + '">' + (data.img != null ? '<img src="' + data.img + '" alt="" /> ' : '') + data.name + '</span>');
			$('#' + data.id).on('click', function()
			{
				$('#message_input').val(data.command);
			});
		});
	}

	this.load_bbc = function()
	{
		$.each(pc_bbc_codes, function(i, data)
		{
			$('#list_bbc').append('<span id="bbcl_' + data.id + '" title="' + data.name +'"></span>');
			$('#bbcl_' + data.id).append(data.name);

			$('#bbcl_' + data.id).on('click', {insert: data.code}, chat_ui.insert_bbc);
		});
	}

	this.hightlight_me = function()
	{
		var messages = $('.message');
		$.each(messages, function(index, data)
		{
			var m_text = $(data).html();
			var reg = new RegExp(pc_display_name, 'gi');
			$(data).html(m_text.replace(reg, '<span class="red">' + pc_display_name + '</span>'));
		});
	}

	this.flash_title = function()
	{
		var alerting = false;
		inter = setInterval(function()
		{
			if (chat_ui.focused)
			{
				document.title = 'PureChat';
				alerting = false;
				clearInterval(inter);
				return true;
			}
				

			if (!alerting)
			{
				alerting = true;
				document.title = 'New Messages!';
			}
			else
			{
				alerting = false;
				document.title = 'PureChat';
			}
			
		}, 1500);
	}
}
var chat_ui = new ChatInterface;

$(document).ready(function()
{
	chat_ui.scroll_down();
	chat_ui.status_bar(status);

	chat_ui.load_smilies();
	chat_ui.load_bbc();
	chat_ui.load_irc();

	chat_ui.bind_events();

	chat_ui.hightlight_me();
	
	$('#message_input').attr('autocomplete', 'off').focus();
	chat_ui.focused = true;
});
