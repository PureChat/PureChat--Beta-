var AdminObjectModelUI = function(params)
{
	this.params = {};
	
	var alive_ac_fields = new Object;
	
	this.init = function()
	{
		bind();
	}
	
	function bind()
	{		
		// onSubmit binding.
		if (params.form != null)
		{
			var obj = '#' + params.form;
			
			$(obj).on('submit', aom.submit_form);
		}
		
		// Auto Complete fields.
		if (params.ac != null && typeof params.ac == 'object')
		{
			$.each(params.ac, function(key, field)
			{
				if (field.url != null)
					url = field.url;
				else
					return false;
				
				if (field.id != null)
					id = field.id
				else
					return false;
					
				$('#' + id).on('keyup', function()
				{
					aom.auto_suggest_func(url, id);
				});
				$('#' + id).on('blur', function()
				{
					aom.ui.hide_ac_list(id);
				});
			});
		}
	}
	
	this.serialize_fields = function()
	{
		// Thank you, jQuery, for saving me about 50 lines of code.
		var form = $('#' + params.form ).serializeArray();
		var fields = new Object;
		
		$.each(form, function(index, obj)
		{
			fields[obj.name] = obj.value;
		});
		
		return fields;
	}
	
	this.point_error = function(fieldset)
	{
		if (!fieldset)
			return false;
			
		$('#' + fieldset).parent().addClass('field_error');
	}
	this.reset_errors = function()
	{
		$('.form_information').removeClass('field_error');
	}
	
	this.show_ac_list = function(field_id, ajax_response)
	{
		// Offsets.
		var field_offset = $(field_id).offset();
		var list_offset_x = (field_offset.left + 20) + 'px';
		var list_offset_y = (field_offset.top + 37) + 'px';
		
		// Useful identifier.
		var uf_id = field_id.replace('#', '');
	
		// Toggle variable.
		var is_open = alive_ac_fields[uf_id] ? true : false;
		
		// Count the items in the list, because we don't want an li border if there's only one.
		var total_list_items = ajax_response.length - 1;
		
		// Only append the list if it isn't already there.
		if (!is_open)
			$('body').append('<ul class="ac_list" id="ac_list_' + uf_id + '"></ul>');
		
		// Put it in the right spot, relative to the field.
		$('#ac_list_' + uf_id).css({top: list_offset_y, left: list_offset_x});
		
		// Append the list items.
		$('#ac_list_' + uf_id + ' li').detach(); // Remove old ones first.
		$.each(ajax_response, function(i, list_item)
		{
			var id = 'ac_li_' + uf_id + '_' + i;
			var border = (i == 0 && total_list_items == 0) || (i == total_list_items) ? ' class="no_border"' : '';
			
			$('#ac_list_' + uf_id).append('<li id="' + id + '"' + border + '>' + list_item + '</li>');
			
			$('#' + id).on('click', function()
			{
				$(field_id).val($(this).text());
				
				$(this).parent().slideUp(300, function()
				{
					$(this).detach();
				});
				
				alive_ac_fields[uf_id] = false;
			});
		});
		
		if (!is_open)
		{
			$('#ac_list_' + uf_id).slideDown(400);
			alive_ac_fields[uf_id] = true;
		}
	}
	
	this.hide_ac_list = function(field)
	{
		if (field)
		{
			var uf_id = field.replace('#', '');
			
			$('#ac_list_' + uf_id).slideUp(300, function()
			{
				$(this).detach();
			});
			
			alive_ac_fields[uf_id] = false;
		}
	}
	
	this.show_ac_load = function(f)
	{
		$(f).css({background: 'url(' + pc_currentthemeurl + '/images/ajax_loader.gif) right center no-repeat'});
	}
	this.hide_ac_load = function(f)
	{
		$(f).removeAttr('style');
	}
	
	this.show_hide_progress = function(status)
	{		
		switch (status)
		{
			case 'show':
				$('body').append('<img src="' + pc_currentthemeurl + '/images/ajax_loader.gif" alt="" id="status_loader" />');
				$('#status_loader').fadeIn(300);
				break;
			
			case 'hide':
				$('#status_loader').fadeOut(300, function()
				{
					$(this).detach();
				});
				break;
				
			default:
				$('#status_loader').fadeOut(300, function()
				{
					$(this).detach();
				});
				break;
		}
	}
	
	this.show_hide_success = function(status)
	{
		switch (status)
		{
			case 'show':
				$('body').append('<div class="success_notifier"></div>');
				$('.success_notifier').slideDown(500);
				
				break;
				
			case 'hide':
				$('.success_notifier').fadeOut(300, function()
				{
					$(this).detach();
				});
				break;
				
			default:
			
				break;
		}
	}
}
