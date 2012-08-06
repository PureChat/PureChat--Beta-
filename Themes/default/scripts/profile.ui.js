/**
 * PureChat (PC)
 *
 * @file ~/Themes/default/scripts/profile.ui.js
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

this.ProfileUI = function()
{
	var global_user_id;

	this.loadup = function(data, allow_edit)
	{
		// Now we have access to this user's ID for future reference.
		global_user_id = data.id_user;

		// Close any other open profiles first.
		$('.profile_container').detach();

		// Add the profiles frames.
		$('body').append('<div class="profile_container"></div>');
		$('.profile_container').append('<div class="leftside"></div>');
		$('.profile_container').append('<div class="rightside"></div>');

		// Fill in the left side.
		$('.leftside').append('<div class="profile_username">' + data.display_name + '</div>');
		
		// Avatar.
		if (data.avatar != '')
			$('.leftside').append('<img src="' + data.avatar + '" alt="" class="profile_avatar" />');


		// Fill in the right side, starting with the closing icon.
		$('.rightside').append('<img src="' + pc_currentthemeurl + '/images/close.png" id="profile_closer" alt="" />');
		$('#profile_closer').click(function()
		{
			profile_ui.close_window();
		});

		var profile_elements =
		{
			display_name:
			{
				id: 'display_name',
				title: pc_lang.fieldname_display_name,
				data: data.display_name
			},
			first_name:
			{
				id: 'first_name',
				title: pc_lang.fieldname_first_name,
				data: data.first_name
			},
			last_name:
			{
				id: 'last_name',
				title: pc_lang.fieldname_last_name,
				data: data.last_name
			},
			email:
			{
				id: 'email',
				title: pc_lang.fieldname_email,
				data: data.email
			}
		};

		$.each(profile_elements, function(index, info)
		{
			// Load up the stuffs.
			$('.rightside').append('<div id="profile_' + info.id + '"></div>');
			$('.rightside #profile_' + info.id).append('<strong>' + info.title + '</strong>');
			$('.rightside #profile_' + info.id).append(' ' + info.data);

			// If we're allowed to edit fields, let's add the icon for it.
			/*
			 * Yo hackers, hear me now, think about it later.
			 * We check permissions on the low down, so adding the icon manually won't get you far.
			 */
			if (allow_edit)
			{
				$('.rightside #profile_' + info.id).append(' <a href="' + pc_script + '" class="edit_icon"></a>');

				// Bind the action.
				$('.rightside #profile_' + info.id + ' a.edit_icon').on('click', function()
				{
					profile_ui.edit_field(info.id, info.data, data.id_user);
					return false;
				});
			}
		});

		// Time for the avatar uploader. Better check that the browser supports ajax file requests.
		if (window.FormData && allow_edit)
		{
			$('.rightside').append('<div id="profile_avatar"></div>');
			$('.rightside #profile_avatar').append('<strong>' + pc_lang.fieldname_avatar + '</strong>');
			$('.rightside #profile_avatar').append(' <input type="file" name="avatar_upload" id="avatar_upload" />');

			// One of the very few places where a jQuery selector just isn't suited.
			document.getElementById('avatar_upload').addEventListener('change', function()
			{
				var formdata = new FormData();
				formdata.append('avatar', this.files[0]);
				profile.save_avatar('file', formdata, data.id_user);
			});
		}

		// Now show make it visible.
		$('.profile_container').show(600, function()
		{
			$('.profile_container > div').fadeIn(500);
		});
	}

	// Put the form elements into the field div.
	this.edit_field = function(fieldname, value, user_id)
	{
		if (fieldname == null || value == null)
			return false;

		// Display name has a char limit enforced.
		if (fieldname == "display_name")
			$('#profile_' + fieldname).html('<input type="text" value="' + value + '" id="field_' + fieldname + '" maxlength="35" />');
		else
			$('#profile_' + fieldname).html('<input type="text" value="' + value + '" id="field_' + fieldname + '" />');
		$('#profile_' + fieldname).append(' <input type="submit" value="' + pc_lang.update + '"/>');
		$('#profile_' + fieldname).append(' <input type="submit" value="' + pc_lang.cancel + '" />');
		$('#profile_' + fieldname).append(' <input type="hidden" value="' + value + '" id="orig" />');

		$('#profile_' + fieldname + ' input').eq(1).on('click', function()
		{
			profile.update_profile(fieldname, user_id);
		});
		$('#profile_' + fieldname + ' input').eq(2).on('click', function()
		{
			profile_ui.cancel_field(fieldname);
		});
	}

	//Update the image displayed in the sidebar.
	this.update_avatar_image = function(url, member)
	{
		if (url != null && member != null)
		{
			var pavatar = $('.profile_avatar');

			if (pavatar.length > 0)
				$('.profile_avatar').attr('src', url);
			else
				$('.leftside').append('<img src="' + url + '" alt="" class="profile_avatar" />');

			if (pc_user_id == member)
				$('#sidebar_avatar').attr('src', url);
			
			$('#profile_avatar').append(' <span id="avatar_upload_status">' + pc_lang.success + '</span>');
			setTimeout(function()
			{
				$('#avatar_upload').val('');
				$('#avatar_upload_status').fadeOut(1000);
			}, 2000);
		}
	}

	// Cancel the edit operation.
	this.cancel_field = function(fieldname)
	{
		var title = pc_lang['fieldname_' + fieldname];
		var text = $('#profile_' + fieldname + ' #orig').val();
		
		$('#profile_' + fieldname).html('<strong>' + title + '</strong>');
		$('#profile_' + fieldname).append(' ' + text);
		$('#profile_' + fieldname).append(' <a href="' + pc_script + '" class="edit_icon"></a>');

		$('#profile_' + fieldname + ' a.edit_icon').on('click', function()
		{
			profile_ui.edit_field(fieldname, text, global_user_id);
			return false;
		});
	}

	// Put the field back together and bind an event to the edit icon.
	this.update_field = function(fieldname, title, text)
	{
		$('#profile_' + fieldname).html('<strong>' + title + '</strong>');
		$('#profile_' + fieldname).append(' ' + text);
		$('#profile_' + fieldname).append(' <a href="' + pc_script + '" class="edit_icon"></a>');

		$('#profile_' + fieldname + ' a.edit_icon').on('click', function()
		{
			profile_ui.edit_field(fieldname, text, global_user_id);
			return false;
		});
	}


	this.close_window = function()
	{
		$('.profile_container > div').fadeOut(300, function()
		{
			$('.profile_container').slideUp(500, function()
			{
				$(this).detach();
			});
		});
	}
}

var profile_ui = new ProfileUI;