jQuery('document').ready( function($) {


	// ====== Hide color picker function ====== //


	$("#drop_down2").change(function(){

			if (this.value === 'Custom') {
				$('.wp-picker-container').parent().parent().show();
			}

			else if (this.value === 'Dark' || 'Light'){
				$('#drop_down2').parent().parent().next().hide();
			}

	});


// ====== Upload Image Loader ====== //


	    var custom_uploader;
	    $('#upload_image_button').click(function(e) {
	        e.preventDefault();
	        //If the uploader object has already been created, reopen the dialog
	        if (custom_uploader) {
	            custom_uploader.open();
	            return;
	        }
	        //Extend the wp.media object
	        custom_uploader = wp.media.frames.file_frame = wp.media({
	            title: 'Choose Image',
	            button: {
	                text: 'Choose Image'
	            },
	            multiple: false
	        });
	        //When a file is selected, grab the URL and set it as the text field's value
	        custom_uploader.on('select', function() {
	            attachment = custom_uploader.state().get('selection').first().toJSON();
	            $('#upload_image').val(attachment.url);
	        });
	        //Open the uploader dialog
	        custom_uploader.open();
	    });


});
