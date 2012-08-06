/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/scripts/login_register.js
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

$(document).ready(function()
{
	$('#username_field').focus();
	$('.error, .success').show(350);

	// Forward and back buttons.
	$('#next1').on('click', function()
	{
		$('#registration_form').animate({left: '-110%', right: '110%'}, 1000);
		$('#user_verification').animate({left: '0%', right: '0%'}, 1000);
	});
	$('#back1').on('click', function()
	{
		$('#user_verification').animate({left: '110%', right: '10%'}, 1000);
		$('#registration_form').animate({left: '0%', right: '0%'}, 1000);
	});

	$('.form_input').bind('keypress', function(key)
	{
		if ((key.keyCode == 13) && (no_bot == false))
		{
			$('#registration_form').animate({left: '-110%', right: '110%'}, 1000);
			$('#user_verification').animate({left: '0%', right: '0%'}, 1000);
		}
	})
});