$(function() {

	

		var form = $('#bb_listing_marker_contact_form"');

		$(form).submit(function(event) {
				event.preventDefault();
				submitForm();    
		});

		function submitForm(){

			var name	= $("#name").val();	
			var email	= $("#email").val();
			var phone	= $("#phone").val();
			var message = $("#message").val();
			var contact_admin_email = $("#contact-admin-email").val();

			var data = {
				'name': name,
					'email': email,
					'phone': phone,
					'message': message,
					'contact_admin_email': contact_admin_email
			};

			$.ajax({
				type: "POST",
				url: "php/bb_listing_marker_contact_form-process.php",
				async: true,
				//data: "name=" + name + "&email=" + email + "&phone=" + phone + "&message=" + message + "&contact-admin-email=" + contact_admin_email,
				data: data,
				success : function(msg){
					$(".submitMessage").text(msg);
				}
			});
		}

	

});
