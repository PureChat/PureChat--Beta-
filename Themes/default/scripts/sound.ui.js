var SoundUI = function()
{
	this.change_sound_icon = function()
	{
		if (allow_play)
			$('#sound_toggle').attr('class', 'sound_toggle_on');
		else
			$('#sound_toggle').attr('class', 'sound_toggle_off');

		return true;
	}

	this.bind_events = function()
	{
		$('#sound_toggle').on('click', sound.sound_toggle);

		return true;
	}
}
var sound_ui = new SoundUI;

$(document).ready(function()
{
	sound_ui.bind_events();
	sound_ui.change_sound_icon();
});