jQuery( document ).ready( function() {
   //uploading files variable
   var custom_file_frame;
   jQuery( document ).on( 'click', '.webcomic-upload-character', function( event ) {
	  var characterId = jQuery( this ).attr( 'char' );
      event.preventDefault();
      //If the frame already exists, reopen it
      if ( typeof( custom_file_frame )!== "undefined" ) {
         custom_file_frame.close();
      }
 
      //Create WP media frame.
      custom_file_frame = wp.media.frames.customHeader = wp.media({
         //Title of media manager frame
         title: "Upload a character image to Webcomic Creator Studio",
         library: {
            type: 'image'
         },
         button: {
            //Button text
            text: "Upload Image"
         },
         //Do not allow multiple files, if you want multiple, set true
         multiple: false
      });
 
      //callback for selected image
      custom_file_frame.on( 'select', function() {
         var attachment = custom_file_frame.state().get('selection').first().toJSON();

		 
		 

		  
		var securityNonce = document.getElementById( 'webcomic-admin-page-nonce' ).value;
		
		var charData = {
            action: 'webcomic_add_char_option',
            char_id: characterId,
			img_src: attachment.url,
			attachment_id: attachment.id,
			security: securityNonce,
        }

        jQuery.ajax({
            type: "POST",
            data: charData,
            dataType:"json",
            url: webcomic_vars.ajaxurl,
            success: function ( response ) {
                //console.log("Character Image added. ajax reply: " + response.reply );
				//add the image to the character box
				webcomic_insert_char_img( characterId, response.attachment, response.src );
				

            }
        }).fail(function ( data ) {
            console.log( "Failed to add character. ajax failure: " );
			console.log( data );
			
        }); 
		
      });
 
		
      //Open modal
      custom_file_frame.open();
   });
   
   jQuery( document ).on( 'click', '#webcomic-upload-background', function( event ) {
      event.preventDefault();
      //If the frame already exists, reopen it
      if ( typeof( custom_file_frame )!=="undefined" ) {
         custom_file_frame.close();
      }
 
      //Create WP media frame.
      custom_file_frame = wp.media.frames.customHeader = wp.media({
         //Title of media manager frame
         title: "Upload a background image to Webcomic Creator Studio",
         library: {
            type: 'image'
         },
         button: {
            //Button text
            text: "Upload Image"
         },
         //Do not allow multiple files, if you want multiple, set true
         multiple: false
      });
 
      //callback for selected image
		custom_file_frame.on( 'select', function() {
		 
			var attachment = custom_file_frame.state().get( 'selection' ).first().toJSON();

			var imageTitle = attachment.name;
			//send ajax request to get updated name of the image to update it on the screen

			var titleData = {
				action: 'webcomic_get_bg_title',
				image_id: attachment.id,
			};

					jQuery.ajax({
						type: "POST",
						data: titleData,
						dataType:"json",
						url: webcomic_vars.ajaxurl,
						success: function ( response ) {
							
							//console.log("Retrieved image title. ajax reply: " + response.reply + " with title of " + response.title );
							imageTitle = response.title;
							//append image data to the screen
							webcomic_insert_image( imageTitle, attachment.id, attachment.url );


						}
					}).fail(function ( data ) {
						console.log( "Failed to get image title. ajax failure:" ); 
						console.log( data );
						//append image data to the screen. It will just use the old title
						webcomic_insert_image( attachment.name, attachment.id, attachment.url );

						
					}); 
			  
			  
		});
 
      //Open modal
      custom_file_frame.open();
   });
});
function webcomic_insert_image( title, id, url ) {

	jQuery( '.webcomic-bgs' ).append( '<div class="webcomic-background-box"><div class="webcomic-background-title">'+title+'</div><div class="webcomic-background-delete"><input type="hidden" class="webcomic-background-delete-id" value="'+id+'"/>[X]</div><div class="webcomic-clear"></div><div class="webcomic-background-img"><img src="'+url+'"/></div></div>' );

}
function webcomic_insert_char_img( charid, aid, url ) {
	jQuery( 'div[char="' + charid + '"]' ).find( '.webcomic-char-noimg' ).remove();
	jQuery( 'div[char="' + charid + '"]' ).find( '.webcomic-character-imgs-box' ).append( '<div class="webcomic-char-img-box"><div class="webcomic-char-img-delete"><input type="hidden" class="webcomic-char-img-delete-id" value="'+aid+'"/>[X]</div><div class="webcomic-clear"></div><div class="webcomic-char-img"><img src="'+url+'"/></div></div>' );
	
}