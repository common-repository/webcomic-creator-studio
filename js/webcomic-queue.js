jQuery( document ).ready( function () {


	jQuery( document ).on('click', '.webcomic-delete-comic', function () {
		//turn this button into a confirmation button
		jQuery( this ).addClass( 'webcomic-confirm-delete-comic' )
		.removeClass( 'webcomic-delete-comic')
		.prop( 'value', ' Click here to Confirm ');
		
	});

	jQuery( document ).on('click', '.webcomic-confirm-delete-comic', function () {
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		var comicID = jQuery( this ).siblings( 'input[type=hidden]' ).eq( 0 ).val(); //there is an input type="hidden" in the div with the button that contains the comic ID
		if ( !isNaN( parseFloat( comicID ) ) && isFinite( comicID ) ) { //only process numbers
			var comicData = {
				action: 'webcomic_delete_queued_comic',
				comic: comicID,
				security: securityNonce,
				
				
			}

			jQuery.ajax({
				type: "POST",
				data: comicData,
				dataType: "json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log( "Comic Deleted. ajax reply: " + response.reply );
					var deletedcomicID = response.reply;
					jQuery( document ).find( '#webcomic-queue-' + deletedcomicID).remove();
				},
			}).fail( function ( data ) {
				console.log( "Failed to delete comic. ajax failure: " );
				console.log( data );
				
			}); 
	   }
    });
	
	//jQuery( '.webcomic-queue-comic' ).click( function () {
	jQuery( document ).on('click', '.webcomic-queue-comic', function () {
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		var comicID = jQuery( this ).siblings( 'input[type=hidden]' ).eq( 0 ).val(); //there is an input type="hidden" in the div with the button that contains the comic ID
		if ( !isNaN( parseFloat( comicID ) ) && isFinite( comicID ) ) { //only process numbers
			var queueData = {
				action: 'webcomic_add_comic_to_active_queue',
				id: comicID,
				security: securityNonce,
				
				
			}

			jQuery.ajax({
				type: "POST",
				data: queueData,
				dataType: "json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log( "Comic Queued. ajax reply: " + response.reply );
					var queuedcomicID = response.reply;
					var queueButton = '<input type="button" class="webcomic-early-queue-comic" value="[ /\\ ] Move Up" /><input type="button" class="webcomic-late-queue-comic" value="[ \\/ ] Move Down" /><input type="button" class="webcomic-unqueue-comic" value="[ &lt;&lt; ] Unqueue" />';
					queueButton = queueButton + '<input type="hidden" name="comic-id" value="' +  queuedcomicID + '"/>';
					var oldNum = 0;
					jQuery( document ).find( '#webcomic-queue-' + queuedcomicID ).nextAll().each( function ( index, value ) { 
						oldNum = parseInt( jQuery( this ).find( '.webcomic-pos' ).text(), 10 );
						jQuery(this).find( '.webcomic-pos' ).text( oldNum - 1 );
					});
					jQuery( document ).find( '.webcomic-no-queued').remove();
					jQuery( document ).find( '#webcomic-queue-' + queuedcomicID ).detach().appendTo('#webcomic-queued-comics');
					var newPos = jQuery( document ).find( '#webcomic-queued-comics' ).find( 'tr' ).length;
					jQuery( document ).find( '#webcomic-queue-' + queuedcomicID ).find( '.webcomic-pos' ).text( newPos );
					jQuery( document ).find( '#webcomic-queue-' + queuedcomicID ).find( '.webcomic-button-options' ).html( queueButton );
					var queuedCount = jQuery( '#webcomic-queued-comics' ).find( 'tr ').length - 1;
					jQuery( document ).find( '#webcomic-queue-' + queuedcomicID ).find( '.webcomic-pos' ).text( queuedCount );
				},
			}).fail( function ( data ) {
				console.log( "Failed to queue comic. ajax failure: " );
				console.log( data );
				
			}); 
	   }
    });
	
	//jQuery( '.webcomic-unqueue-comic' ).click( function () {
	jQuery( document ).on('click', '.webcomic-unqueue-comic', function () {
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		var comicID = jQuery( this ).siblings( 'input[type=hidden]' ).eq( 0 ).val(); //there is an input type="hidden" in the div with the button that contains the comic ID
		if ( !isNaN( parseFloat( comicID ) ) && isFinite( comicID ) ) { //only process numbers
			var unqueueData = {
				action: 'webcomic_remove_comic_from_active_queue',
				id: comicID,
				security: securityNonce,
				
			}

			jQuery.ajax({
				type: "POST",
				data: unqueueData,
				dataType: "json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log( "Comic unQueued. ajax reply: " + response.reply );
					var unqueuedcomicID = response.reply;
					var delQueueButton = '<input type="button" class="webcomic-delete-comic" value="[ X ] Delete" /><input type="button" class="webcomic-queue-comic" value="[ &gt;&gt; ] Add to Queue"/>';
					delQueueButton = delQueueButton + '<input type="hidden" name="comic-id" value="' +  unqueuedcomicID + '"/>';
					//move all following comics up 1 in position
					var oldNum = 0;
					jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).nextAll().each( function ( index, value ) { 
						oldNum = parseInt( jQuery( this ).find( '.webcomic-pos' ).text(), 10 );
						jQuery(this).find( '.webcomic-pos' ).text( oldNum - 1 );
					});
					
					//var pos = parseInt( jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).find( '.webcomic-pos' ).text(), 10 );
					jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).detach().appendTo( '#webcomic-unqueued-comics' );
					var newPos = jQuery( document ).find( '#webcomic-unqueued-comics' ).find( 'tr' ).length;
					jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).find( '.webcomic-pos' ).text( newPos );
					jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).find( '.webcomic-button-options' ).html( delQueueButton );
					var unqueuedCount = jQuery( '#webcomic-unqueued-comics' ).find( 'tr ').length - 1;
					jQuery( document ).find( '#webcomic-queue-' + unqueuedcomicID ).find( '.webcomic-pos' ).text( unqueuedCount );
				},
			}).fail( function ( data ) {
				console.log( "Failed to unqueue comic. ajax failure: " );
				console.log( data );
				
			}); 
	   }
    });
	
	jQuery( document ).on('click', '.webcomic-early-queue-comic', function () { //move a comic up earlier in the queue
		
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		var comicID = jQuery( this ).siblings( 'input[type=hidden]' ).eq( 0 ).val(); //there is an input type="hidden" in the div with the button that contains the comic ID
		var comicEarlyPos = parseInt( jQuery( this ).closest( 'td' ).siblings('.webcomic-pos' ).text(), 10 ) - 1;
		var row = jQuery( this ).closest( "tr" );
		
		var earlyQueueData = {
				action: 'webcomic_move_queue',
				id: comicID,
				pos: comicEarlyPos,
				security: securityNonce,
		}
		var nextToLastComic = jQuery('#webcomic-queued-comics').find('tr').length - 2; //minus 1 for the header and 1 to get to the next-to-last comic

		jQuery.ajax({
				type: "POST",
				data: earlyQueueData,
				dataType: "json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log( "Comic Moved. ajax reply: " + response.reply );
					var earlyQueuedComicPos = response.reply;

					if ( row.prev() ) {
						
						if ( parseInt( earlyQueuedComicPos, 10 ) == 1 )	{
							//if this is the first row now, remove the "move up" option
							row.find( '.webcomic-early-queue-comic' ).remove();
							//and add the move up button to the old 1st row
							row.prev().find( '.webcomic-button-options' ).prepend( '<input type="button" class="webcomic-early-queue-comic" value="[ /\\ ] Move Up" />' );
						}
						if ( parseInt( earlyQueuedComicPos, 10 ) == nextToLastComic )	{
							//if this was the last row add the "move down" button
							row.find( '.webcomic-button-options' ).find(':button:last').before( '<input type="button" class="webcomic-late-queue-comic" value="[ \\/ ] Move Down">' );
							//and remove it to the row that is getting moved to last
							row.prev().find( '.webcomic-late-queue-comic' ).remove();
						}
						
						var thisRow = parseInt( row.find( '.webcomic-pos' ).text(), 10 );
						row.find( '.webcomic-pos' ).text( thisRow - 1 );
						row.prev().find( '.webcomic-pos' ).text( thisRow );
						//move this row to before the previous row
						row.insertBefore( row.prev() );
							
					}
					
				},
			}).fail( function ( data ) {
				console.log( "Failed to unqueue comic. ajax failure: " );
				console.log( data );
				
			}); 
	});
	
	jQuery( document ).on('click', '.webcomic-late-queue-comic', function () { //move a comic back later in the queue
		
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		var comicID = jQuery( this ).siblings( 'input[type=hidden]' ).eq( 0 ).val(); //there is an input type="hidden" in the div with the button that contains the comic ID
		var comicLatePos = parseInt( jQuery( this ).closest( 'td' ).siblings('.webcomic-pos' ).text(), 10 ) + 1;
		var row = jQuery( this ).closest( "tr" );
		var lateQueueData = {
				action: 'webcomic_move_queue',
				id: comicID,
				pos: comicLatePos,
				security: securityNonce,
		}
		var lastComic = jQuery('#webcomic-queued-comics').find('tr').length - 1; //minus 1 for the header
		//console.log("moving " + comicID + " to pos " + comicLatePos);
		jQuery.ajax({
				type: "POST",
				data: lateQueueData,
				dataType: "json",
				url: webcomic_vars.ajaxurl,
				success: function ( response ) {
					//console.log( "Comic Moved. ajax reply: " + response.reply );
					var lateQueuedComicPos = response.reply;
					

					
					if ( row.next() ) {
						
						if ( parseInt( lateQueuedComicPos, 10 ) == 2 )	{
							
							//if this is moved down from the first row, add the "move up" option
							row.find( '.webcomic-button-options' ).prepend( '<input type="button" class="webcomic-early-queue-comic" value="[ /\\ ] Move Up" />' );
							//and remove the "move up" button from the new 1st row
							row.next().find( '.webcomic-early-queue-comic' ).remove();
						}
						if ( parseInt( lateQueuedComicPos, 10 ) == lastComic )	{
							//if this is now the last row remove the "move down" button
							row.find( '.webcomic-late-queue-comic' ).remove();
							//and add it to the row that is getting moved up from last
							row.next().find( '.webcomic-button-options' ).find(':button:last').before( '<input type="button" class="webcomic-late-queue-comic" value="[ \\/ ] Move Down">' );
						}
						var thisRow = parseInt( row.find( '.webcomic-pos' ).text(), 10 );
						row.find( '.webcomic-pos' ).text( thisRow + 1 );
						row.next().find( '.webcomic-pos' ).text( thisRow );
						
						
						//move this row to after the next row
						row.insertAfter( row.next() );
					}
					
					
					
					
				},
			}).fail( function ( data ) {
				console.log( "Failed to unqueue comic. ajax failure: " );
				console.log( data );
				
			}); 
	});
	
	jQuery( document ).on('click', '#webcomic-force-update', function () { //post the top comic from the queue
	
		var securityNonce = jQuery( document ).find( '#webcomic-queue-page-nonce' ).val();
		jQuery( document ).find( '#webcomic-queue-status').empty(); //clear warning messages
		var comicID = jQuery( '#webcomic-queued-comics' ).find( 'tr' ).eq( 1 ).find('.webcomic-button-options'); //first we find the row
		if ( comicID && comicID.length > 0 ) { //if the row exists find the comic ID
			comicID = comicID.find( 'input[type=hidden]' ).eq( 0 ).val(); //the hidden input contains the comic ID
			
			var postComicData = {
				action: 'webcomic_force_post_comic',
				id: comicID,
				security: securityNonce,
			}
			jQuery.ajax({
					type: "POST",
					data: postComicData,
					dataType: "json",
					url: webcomic_vars.ajaxurl,
					success: function ( response ) {
						//console.log( "Comic Posted. ajax reply: " + response.reply );	

						//remove the top comic from the queue on the screen and move all other comics up 1 position
						
						var oldNum = 0;
						jQuery( document ).find( '#webcomic-queue-' + comicID ).nextAll().each( function ( index, value ) { 
							oldNum = parseInt( jQuery( this ).find( '.webcomic-pos' ).text(), 10 );
							jQuery(this).find( '.webcomic-pos' ).text( oldNum - 1 );
						});
						jQuery( document ).find( '#webcomic-queue-' + comicID ).remove();
						jQuery( document ).find( '#webcomic-queue-status').html( '<strong>Comic successfully posted from queue.</strong>' );
					
					}
			}).fail( function ( data ) {
				console.log( "Failed to post comic. ajax failure: " );
				console.log( data );
				jQuery( document ).find( '#webcomic-queue-status').html( '<strong>Error: Failed to post comic. Check your connection and try again.</strong>' );
			}); 

				
		}
		else {	//if the row doesn't exist, there is no top queued comic. Print a warning error
			console.log("No queued comic found.");
			jQuery( document ).find( '#webcomic-queue-status').html( '<strong>Error: You don\'t have any comics in the queue.</strong>' );
		}
	
	});
			
});