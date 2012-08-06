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

var IRC = function()
{
	// Control methods.
	this.test = function(string)
	{
		var ircfound = false;
		var commands = new Array();
		commands[0] = new Array('quit', /^(\/quit)/i);
		commands[1] = new Array('quit', /^(\/leave)/i);
		commands[2] = new Array('quit', /^(\/exit)/i);
		commands[3] = new Array('quit', /^(\/logout)/i);
		commands[4] = new Array('available', /^(\/available)/i);
		commands[5] = new Array('available', /^(\/here)/i);
		commands[6] = new Array('available', /^(\/back)/i);
		commands[7] = new Array('available', /^(\/avail)/i);
		commands[8] = new Array('busy', /^(\/busy)/i);
		commands[9] = new Array('away', /^(\/away)/i);
		commands[10] = new Array('away', /^(\/brb)/i);
		commands[11] = new Array('invisible', /^(\/invisible)/i);
		commands[12] = new Array('invisible', /^(\/hide)/i);

		if (string == null)
			return false;

		$.each(commands, function(i, vals)
		{
			var matched = vals[1].test(string);

			if (matched)
			{
				irc.execute(vals[0]);
				ircfound = true;
			}
		});

		if (ircfound)
			return true;
		else
			return false;
	}

	this.execute = function(command)
	{
		if (command == null)
			return false;

		command = 'cmnd_' + command;
		irc[command]();
	}

	// Commands.
	this.cmnd_quit = function()
	{
		window.location = pc_script + '?action=user&perform=logout';
	}

	this.cmnd_available = function()
	{
		$.ajax({
			url: pc_script + '?action=user&perform=status_update&ajax_connection=true',
			type: 'POST',
			data: {status: 'available'}
		});

		chat_ui.status_bar('available');
	}
	this.cmnd_busy = function()
	{
		$.ajax({
			url: pc_script + '?action=user&perform=status_update&ajax_connection=true',
			type: 'POST',
			data: {status: 'busy'}
		});

		chat_ui.status_bar('busy');
	}
	this.cmnd_away = function()
	{
		$.ajax({
			url: pc_script + '?action=user&perform=status_update&ajax_connection=true',
			type: 'POST',
			data: {status: 'away'}
		});

		chat_ui.status_bar('away');
	}
	this.cmnd_invisible = function()
	{
		$.ajax({
			url: pc_script + '?action=user&perform=status_update&ajax_connection=true',
			type: 'POST',
			data: {status: 'invisible'}
		});

		chat_ui.status_bar('invisible');
	}
}
var irc = new IRC;