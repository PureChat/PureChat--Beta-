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

var SoundObject = function()
{
	var new_message_sound;

	this.init = function()
	{
		soundManager.url = pc_themesurl + '/scripts/soundmanager/';
		soundManager.flashVersion = 8;
		soundManager.useFlashBlock = false;
		soundManager.onready(function() {
		new_message_sound = soundManager.createSound({
				id: 'new_message',
				url: pc_currentthemeurl + '/sounds/new_message.mp3',
				autoPlay: false,
				loops: 1
			});
		});
	}

	this.play = function(soundID)
	{
		if (!allow_play)
			return false;

		switch (soundID)
		{
			case 'new_message':
				new_message_sound.play();
				break;
		}
	}

	this.sound_toggle = function()
	{
		var expire = new Date();
		expire.setTime(expire.getTime()+(365*24*60*60*1000));

		if (common.get_cookie('sound_toggle') == 'off')
		{
			document.cookie = 'sound_toggle=on; expires=' + expire.toGMTString() + '; path=/';
			allow_play = true;
			sound_ui.change_sound_icon();
		}
		else
		{
			document.cookie = 'sound_toggle=off; expires=' + expire.toGMTString() + '; path=/';
			allow_play = false;
			sound_ui.change_sound_icon();
		}
	}
}
var sound = new SoundObject;
var allow_play;

$(document).ready(function()
{
	// See if we have previously set the sound on or off.
	if ((common.get_cookie('sound_toggle') == 'on') || (common.get_cookie('sound_toggle') == null))
		allow_play = true;
	else
		allow_play = false;

	// Initialize the object.
	sound.init();
});