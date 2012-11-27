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
		$.each(captcha.random, function(key, data) {

			// Some Variables...
			var id_container, id_start, id_land;
			var append_first, append_last;
			var final_width, extra_pad;

			id_container = "#captcha";
			id_start = "#tile_" + (key + 1);
			id_land = "#landing_" + (key + 1);

			// Draggable Tiles.
			$(id_start + " img").draggable({
				containment: id_container,
				cursor: "move",
				revert: "invalid",
				snap: id_land + " img",
				snapTolerance: 30,
				drag: function() {
					$(id_land + " .label").css({"font-weight": "bold"});
				},
				stop: function() {
					$(id_land + " .label").css({"font-weight": "normal"});
				}
			});

			// Tile Landings.
			$(id_land + " img").droppable({
				accept: id_start + " img",
				tolerance: "intersect",
				drop: function(event, jui)
				{
					jui.draggable.draggable("disable");
					$(this).droppable("disable");
					append_first = $(id_start).hasClass("top_left") ? true : false;
					append_last = $(id_start).hasClass("top_right") ? true : false;
					captcha.correct = captcha.correct + 1;

					$(id_start).remove();

					final_width = captcha.boxes - captcha.correct;
					extra_pad = ((((captcha.correct * 17) / (captcha.boxes - captcha.correct)) / 2) + 8);
					$("#captcha .start").css({"width": captcha.full_width / final_width + "%", "padding-left": extra_pad, "padding-right": extra_pad});

					if (append_first == true)
						$("#captcha .first_column div").first().addClass("top_left").removeClass("middle");
					else if (append_last == true)
						$("#captcha .first_column div").last().addClass("top_right");

					$(id_land + " img").css({"opacity": "0.2", "filter": "alpha(opacity=20)"});
					$(id_land).append("<img src='" + pc_imagesurl + "/fugue/16/tick-circle.png' class='success_" + (captcha.use_labels == 1 ? 'u' : 'n') + "l' alt='' />");
					$(id_land + " .label").css({"font-weight": "normal"});

					if (captcha.correct == captcha.boxes)
					{
						$("#captcha .second_column div").first().addClass("top_left");
						$("#captcha .second_column div").last().addClass("top_right");
						$("#" + captcha.form_id).attr("action", captcha.form_action);
						$("#" + captcha.submit_id).removeAttr("disabled");
					}
					jui.draggable.detach();
				}
			});
			return true;
		});
	}
}

var c_ui = new CaptchaUI;
jQuery(document).ready(function($)
{
	c_ui.load();
});