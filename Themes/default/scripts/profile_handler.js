/**
 * PureChat (PC)
 *
 * @file ~/Themes/default/scripts/profile_handler.js
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

this.Profile = function()
{
	this.load_info = function(id)
	{
		if (id == null)
			return false;

		ajax_in_progress = true;
		$.ajax({
			url: pc_script + '?action=profile_controller&perform=load_info&ajax_connection=true',
			type: 'POST',
			data: {user_id: id},
			dataType: 'json',
			success: function(response)
			{
				ajax_in_progress = false;

				if (!response)
					return false;

				var allow_edit = response.allow_edit == 'true' ? true : false;

				$.each(response.data, function(index, value)
				{
					if (value == null)
						response.data[index] = '';
				});

				profile_ui.loadup(response.data, allow_edit);
			}
		});
	}

	this.update_profile = function(fieldname, UID)
	{
		var val = $('#field_' + fieldname).val();

		if (fieldname == 'email')
		{
			var reg = /^[a-z0-9\.\-\_\&\?]+@[a-z0-9\-\_\\.\:]+\.\w{2,3}$/i;
			var email_validation = reg.test(val);

			if (!email_validation)
			{
				$('#field_' + fieldname).css(
				{
					background: '#FD7C83',
					color: '#000'
				});
				return false;
			}
		}

		$('#profile_' + fieldname).append(' <img src="' + pc_currentthemeurl + '/images/ajax_loader.gif" alt="" />');
		$.ajax({
			url: pc_script + '?action=profile_controller&perform=update&ajax_connection=true',
			type: 'POST',
			data: {field: fieldname, value: val, id: UID},
			dataType: 'json',
			success: function(response)
			{
				if (response != null && response.status == 'success')
					profile_ui.update_field(fieldname, response.title, response.data);
			}
		})
	}

	this.save_avatar = function(type, formdata, member)
	{
		if (formdata == null || type == null)
			return false;

		if (type == 'file')
		{
			$.ajax({
				url: pc_script + '?action=profile_controller&perform=save_avatar&type=file&member=' + member + '&ajax_connection=true',
				type: "POST",
				data: formdata,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(response)
				{
					if (response.status != null && response.status == 'success')
					{
						var member_id = parseInt(response.member_id);
						profile_ui.update_avatar_image(response.new_url, member_id);
					}
				}
			});
		}
	}
}

var profile = new Profile;