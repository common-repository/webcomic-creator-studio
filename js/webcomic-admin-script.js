(function($) {
	
	webcomic_hide_all_chars();
	jQuery( '#webcomic-add-char-form' ).hide();
	
	
	$( document ).on( 'click', '.nav-tab-wrapper a', function() {
		$('section').hide();
		$('section').eq($(this).index()).show();
		var securityNonce = jQuery( document ).find( '#webcomic-admin-page-nonce' ).val();

		
		
		var postData = {
            action: 'webcomic_update_admin_tab',
            current_tab: $( this ).index(),
			security: securityNonce,
        }

        $.ajax({
            type: "POST",
            data: postData,
            dataType:"json",
            url: webcomic_vars.ajaxurl,
            success: function ( response ) {

                //console.log("ajax reply: " + response.reply );

            }
        }).fail(function ( data ) {
            console.log( "ajax failure: " );
			console.log( data );
        }); 
		
		
		return false;
	});
	$( document ).on( 'click', '.webcomic-background-delete', function() {
	
		var securityNonce = jQuery( document ).find( '#webcomic-admin-page-nonce' ).val();
		var thisId = $( this ).find( '.webcomic-background-delete-id' ).val();
		var thisBox = $( this ).closest( '.webcomic-background-box' );
		var deleteData = {
            action: 'webcomic_delete_bg_image',
            background_id: thisId,
			security: securityNonce,
        }

        $.ajax({
            type: "POST",
            data: deleteData,
            dataType:"json",
            url: webcomic_vars.ajaxurl,
            success: function ( response ) {
				thisBox.remove();
                //console.log("Successful Delete. ajax reply: " + response.reply );
				

            }
        }).fail(function ( data ) {
            console.log( "Failed Delete. ajax failure: " );
			console.log( data );
			
        }); 
		
	});
	$(document).on( 'click', '#webcomic-create-new-character', function() {
		jQuery( '#webcomic-add-char-form' ).show();
		
	});
	$(document).on( 'click', '#webcomic-create-new-char-cancel', function() {
		jQuery( '#webcomic-add-char-form' ).hide();
		
	});
	
	$(document).on( 'click', '#webcomic-create-new-char-save', function() {
		
		var securityNonce = jQuery( document ).find( '#webcomic-admin-page-nonce' ).val();
		var newCharName = jQuery( '#webcomic-new-char-name' ).val();
		if ( ( newCharName == "none" ) || ( newCharName == "None" ) ) {
			alert( 'Sorry, but you cannot name a character "none" as that name is reserved for the default empty character.' );
		}
		else if ( newCharName == "" ) {
			alert( 'A character must have a name.' );
		}
		else {
			var charData = {
				action: 'webcomic_add_new_character',
				char_name: newCharName,
				security: securityNonce,
			}

			$.ajax({
				type: "POST",
				data: charData,
				dataType:"json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log("Character added. ajax reply: " + response.reply );
					webcomic_insert_character( response.charid, newCharName );

				}
			}).fail(function ( data ) {
				console.log( "Failed to add character. ajax failure: " );
				console.log( data );
				
			}); 
			
			jQuery( '#webcomic-add-char-form' ).hide();
		}
	});
	$( "#webcomic-character-select" ).change( function() {
		characterId = $( this ).val();
		webcomic_hide_all_chars();
		if ( characterId != 0 ) {
			$( 'div[char="' + characterId + '"]' ).css( "display", "inline-block" );
		}
	});
	
	$(document).on( 'click', '.webcomic-char-img-delete', function() {
	
		var securityNonce = jQuery( document ).find( '#webcomic-admin-page-nonce' ).val();
		var thisId = $( this ).find( '.webcomic-char-img-delete-id' ).val();
		var thisBox = $( this ).closest( '.webcomic-char-img-box' );
		var deleteData = {
            action: 'webcomic_delete_char_image',
            img_id: thisId,
			security: securityNonce,
        }

        $.ajax({
            type: "POST",
            data: deleteData,
            dataType:"json",
            url: webcomic_vars.ajaxurl,
            success: function ( response ) {
				thisBox.remove();
                //console.log("Successful Delete. ajax reply: " + response.reply );
				

            }
        }).fail(function ( data ) {
            console.log( "Failed Delete. ajax failure: " );
			console.log( data );
			
        }); 
		
	});
	
	$( document ).on( 'click', '.webcomic-character-header-delete', function() {
	
		var thisId = $( this ).find( '.webcomic-char-delete-id' ).val();
		var thisBox = $( this ).closest( '.webcomic-character-choice' );
		
		
		//Ask for confirmation and only delete if they say yes
		
		if ( confirm( "Are you sure you want to delete this character? All of the images and information associated with this character will also be deleted." ) ){
			webcomic_delete_character( thisId, thisBox );
		}
		else {
			return false;
		}
	});

	
	
	
})( jQuery );

function webcomic_hide_all_chars() {
	jQuery( document ).find( '.webcomic-character-choice' ).hide();
}
function webcomic_insert_character(charid, name) {
	jQuery( document ).find( '#webcomic-no-chars').remove();
	webcomic_hide_all_chars();
	jQuery( document ).find( '#webcomic-character-select' ).append( '<option value="' + charid + '">' + name + '</option>' );
	jQuery( document ).find( '#webcomic-character-select-box' ).append( '<div class="webcomic-character-choice" char="' + charid + '"><div class="webcomic-character-header"><div class="webcomic-character-header-name">' + name + '</div><div class="webcomic-character-header-delete"><input type="hidden" class="webcomic-char-delete-id" value="' + charid + '"/>[X]</div><div class="webcomic-clear"></div></div><div class="webcomic-character-imgs-box"><div class="webcomic-char-img-box webcomic-char-noimg">No images added for this character</div></div><div class="webcomic-clear"></div><a href="javascript:;" class="webcomic-upload-media webcomic-admin-button webcomic-upload-character" char="' + charid + '">Upload Image</a></div></div>' );
	jQuery( document ).find( '#webcomic-character-select' ).val( charid );
	jQuery( 'div[char="' + charid + '"]' ).css( "display", "inline-block" );
}
function webcomic_delete_character( charId, box ) {
	
	var securityNonce = jQuery( document ).find( '#webcomic-admin-page-nonce' ).val();
	var deleteData = {
            action: 'webcomic_delete_character',
            char_id: charId,
			security: securityNonce,
        }

        jQuery.ajax({
            type: "POST",
            data: deleteData,
            dataType:"json",
            url: webcomic_vars.ajaxurl,
            success: function ( response ) {
				box.remove();
				jQuery( document ).find( '#webcomic-character-select' ).val( charId ); //reset character select
				jQuery( document ).find( '#webcomic-character-select' ).find( 'option[value="' + charId + '"]' ).remove();
                //console.log("Successful Delete. ajax reply: " + response.reply );
				

            }
        }).fail(function ( data ) {
            console.log( "Failed Delete. ajax failure: " );
			console.log( data );
			
        }); 
}