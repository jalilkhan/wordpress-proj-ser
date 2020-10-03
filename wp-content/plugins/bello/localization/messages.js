/*
 * Translated default messages for the jQuery validation plugin.
 */
'use strict';

(function( $ ) {	
	$(document).ready(function(){
		if (typeof $.validator != "undefined") {
			$.extend( $.validator.messages, {
				required:		plugin_localization_messages_object.required,
				remote:			plugin_localization_messages_object.remote,
				email:			plugin_localization_messages_object.email,
				url:			plugin_localization_messages_object.url,
				date:			plugin_localization_messages_object.date,
				dateISO:		plugin_localization_messages_object.dateISO,
				number:			plugin_localization_messages_object.number,
				digits:			plugin_localization_messages_object.digits,
				equalTo:		plugin_localization_messages_object.equalTo,
				maxlength:		$.validator.format( plugin_localization_messages_object.maxlength ),
				minlength:		$.validator.format( plugin_localization_messages_object.minlength ),
				rangelength:	$.validator.format( plugin_localization_messages_object.rangelength ),
				range:			$.validator.format( plugin_localization_messages_object.range ),
				max:			$.validator.format( plugin_localization_messages_object.max ),
				min:			$.validator.format( plugin_localization_messages_object.min ),
				step:			$.validator.format( plugin_localization_messages_object.step )
			} );
		}
	});
})( jQuery );
