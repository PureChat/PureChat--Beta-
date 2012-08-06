/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/scripts/captcha.ui.js
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

var CaptchaUI = function()
{
	this.load = function()
	{
		$.each(captchaRandom, function(key, data)
		{
			// Draggable tile
			$('#tile_' + (key+1)).draggable({
				containment: '#captchaBounds',
				cursor: 'move',
				revert: 'invalid',
				snap: '#landing_' + (key+1)
			});

			// Landing pad.
			$('#landing_' + (key+1)).droppable({
				accept: '#tile_' + (key+1),
				tolerance: 'intersect',
				drop: function(event, jui)
				{
					jui.draggable.draggable('disable');
					$(this).droppable('disable').addClass('captchaCorrect');
					captchaCorrect = captchaCorrect + 1;
					if (captchaCorrect == boxes)
					{
						$('#' + form_id).attr('action', form_action);
						$('#' + submit_id).removeAttr('disabled');
						$('#captchaFinish').addClass('captchaCorrectFaded');
					}
				}
			});
			return true;
		});
	}
}

var c_ui = new CaptchaUI;
$(document).ready(function()
{
	c_ui.load();
});