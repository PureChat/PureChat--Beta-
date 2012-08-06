var AdminObjectModel = function(params)
{
	var ui;
	
	this.init = function()
	{
		aom.ui = new AdminObjectModelUI(params);
		aom.ui.init();
	}
	
	this.submit_form = function()
	{
		var form_action = ($('#' + params.form).attr('action')) + '&ajax_connection=true';
		var fields = aom.ui.serialize_fields();
		
		aom.ui.show_hide_progress('show');
		
		$.ajax({
			url: form_action,
			type: 'POST',
			data: fields,
			dataType: 'json',
			success: function(response)
			{
				aom.ui.show_hide_progress('hide');
				if (response)
				{
					if (response.success)
					{
						aom.ui.reset_errors();
						aom.ui.show_hide_success('show');
						setTimeout('aom.ui.show_hide_success(\'hide\')', 2500);
					}
						
					else if (response.errors)
					{
						$.each(response.errors, function(index, id)
						{
							aom.ui.point_error(id);
						});
					}
				}
			}
		});
		
		return false;
	}
	
	this.auto_suggest_func = function(to, input)
	{
		if (to == null || input == null)
			return false;
		
		input_id = '#' + input;
		input_val = $(input_id).val();
		
		// This helps optimize things a little. There's no point in matching every name.
		if (input_val.length < 3)
		{
			aom.ui.hide_ac_list(input_id);
			return false;
		}
		
		aom.ui.show_ac_load(input_id);
		
		$.ajax({
			url: to + '&ajax_connection=true',
			type: 'POST',
			data: {value: input_val},
			dataType: 'json',
			success: function(response)
			{
				aom.ui.hide_ac_load(input_id);
				if (response)
					aom.ui.show_ac_list(input_id, response);
			}
		});
	}
}

if (aom_params)
{
	var aom = new AdminObjectModel(aom_params);
	
	$(document).ready(function()
	{
		aom.init();
	});
}
