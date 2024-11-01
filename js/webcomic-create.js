
 window.onload=function() { 
 
 
 		document.getElementById( "webcomic-comic" ).style.width = comicWidth + "px";
		document.getElementById( "webcomic-comic" ).style.height = comicHeight + "px";
		document.getElementById( "webcomic-comic" ).width = comicWidth;
		document.getElementById( "webcomic-comic" ).height = comicHeight;
		document.getElementById( "webcomic-comic-preview" ).style.width = Math.floor( ( 290/906 ) * comicWidth ) + "px";
		document.getElementById( "webcomic-comic-preview" ).style.height = Math.floor( ( 294/345 ) * comicHeight ) + "px";
		document.getElementById( "webcomic-comic-preview" ).width = Math.floor( ( 290/906 ) * comicWidth );
		document.getElementById( "webcomic-comic-preview" ).height = Math.floor( ( 294/345 ) * comicHeight );
		
		
		primeComic();
		

		
		
		document.getElementById( "webcomic-char-select-p1" ).innerHTML = charSelect( "p1" );
		document.getElementById( "webcomic-char-select-p2" ).innerHTML = charSelect( "p2" );
		document.getElementById( "webcomic-char-select-p3" ).innerHTML = charSelect( "p3" );
		document.getElementById( "webcomic-landscape-select-p1" ).innerHTML = bgSelect( "p1" );
		document.getElementById( "webcomic-landscape-select-p2" ).innerHTML = bgSelect( "p2" );
		document.getElementById( "webcomic-landscape-select-p3" ).innerHTML = bgSelect( "p3" );
	
		var ilen = 0;
		var charChanges = '';
		charChanges = document.getElementsByClassName( "webcomic-char-select" );
		var thisId = '';
		var thisElement = '';
		
		for ( var i = 0, ilen = charChanges.length; i < ilen; i++ ) {
		
			thisId = charChanges[i].id;
			thisElement = document.getElementById( thisId );
			
			
			thisElement.addEventListener( "change", prepareSubChars );
		};
		webcomicSaveTriggers();
	};
 
 
 
		//set up the comic
		
function primeComic() {
		var canvas = document.getElementsByTagName( 'canvas' )[0];
        var ctx = canvas.getContext( "2d" );
        
        ctx.lineWidth   = 6;
        ctx.strokeStyle = '#FFFFFF';
        ctx.fillStyle   = '#FFFFFF';
		ctx.clearRect( 0, 0, ctx.canvas.width, ctx.canvas.height );
		ctx.fillRect( 0, 0, comicWidth, comicHeight );
		ctx.strokeStyle = '#000000';
        ctx.strokeRect( 3, 3, comicWidth - 6, comicHeight - 6 );
        ctx.lineWidth   = 3;
        
        ctx.strokeRect( Math.floor( ( 17/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
        ctx.strokeRect( Math.floor( ( 310/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
        ctx.strokeRect( Math.floor( ( 603/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
		
		var canvasPreview = document.getElementsByTagName( 'canvas' )[1];
		ctx = canvasPreview.getContext( "2d" );
		ctx.lineWidth   = 6;
        ctx.strokeStyle = '#FFFFFF';
        ctx.fillStyle   = '#FFFFFF';
		ctx.clearRect( 0, 0, ctx.canvas.width, ctx.canvas.height );
		ctx.fillRect( 0, 0, Math.floor( ( 290/906 ) * comicWidth ), Math.floor( ( 296/345 ) * comicHeight ) );
		ctx.strokeStyle = '#000000';
        ctx.strokeRect( 3, 3, Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
		ctx.lineWidth   = 3;
}
function createComic() {		
		primeComic();
        var canvas = document.getElementsByTagName( 'canvas' )[0];
        var ctx = canvas.getContext( "2d" );
		
		var canvas2 = document.getElementsByTagName( 'canvas' )[1]; //for preview square
        var ctx2 = canvas2.getContext( "2d" );  //for preview square
		
		var mainFont = document.getElementById( 'webcomic-comic-font' ).value;
		
		ctx.lineWidth   = 3;
		ctx2.lineWidth   = 3;
		
		
		//load speech 
		
		ctx.font = "12pt " + mainFont;
		ctx2.font = "12pt " + mainFont;
		var speechbubble = document.getElementById( "webcomic-speech" ).src;
		//var speechbubble = 'words.svg';
		var speechoff = document.getElementById( "webcomic-speech-off" ).src;
		//var speechoff = 'wordsoff.svg';
		var thoughtbubble = document.getElementById( "webcomic-thought" ).src;
		//var thoughtbubble = 'thought.svg';
		var thoughtoff = document.getElementById( "webcomic-thought-off" ).src;
		//var thoughtoff = 'thoughtoff.svg';
		var thisWordType = 'word';
		var wordTypeLoop = '';
		var emptyChar = document.getElementById( 'webcomic-empty-character' ).src;
		
		//load background for each panel
		
		var bg1 = document.getElementById( "webcomic-background-p1" ).value;
		bg1 = document.getElementById( bg1 ).src;
		var bg2 = document.getElementById( "webcomic-background-p2" ).value;
		bg2 = document.getElementById( bg2 ).src;
		var bg3 = document.getElementById( "webcomic-background-p3" ).value;
		bg3 = document.getElementById( bg3 ).src;

		//load characters for each panel

		var cleft1 = '';
		cleft1 = document.getElementById( "webcomic-p1-left-char" ).value;
		cleft1 = loadCharAlt( cleft1, "webcomic-char-left-alt-select-p1" );
		
		var cleft2 = '';
		cleft2 = document.getElementById( "webcomic-p2-left-char" ).value;
		cleft2 = loadCharAlt( cleft2, "webcomic-char-left-alt-select-p2" );
		
		var cleft3 = '';
		cleft3 = document.getElementById( "webcomic-p3-left-char" ).value;
		cleft3 = loadCharAlt( cleft3, "webcomic-char-left-alt-select-p3" );

		var cright1 = '';
		cright1 = document.getElementById( "webcomic-p1-right-char" ).value;
		cright1 = loadCharAlt( cright1, "webcomic-char-right-alt-select-p1" );

		var cright2 = '';
		cright2 = document.getElementById( "webcomic-p2-right-char" ).value;
		cright2 = loadCharAlt( cright2, "webcomic-char-right-alt-select-p2" );

		var cright3 = '';
		cright3 = document.getElementById( "webcomic-p3-right-char" ).value;
		cright3 = loadCharAlt( cright3, "webcomic-char-right-alt-select-p3" );

		
		//draw the comic
		
		var adjustmentSize = 0; //used for adjusting text
		
		var desiredWidth = 0; //used for scaling
		var desiredHeight = 0; //used for scaling
		
		var thisText = '';
		var moveRightText = 0;
		var fontSize = 12;
		var font = mainFont;
		
		//panel 1
		
		
				//background
				
				var background1 = new Image();
				background1.onload = function() {
					ctx.drawImage( background1, Math.floor( ( 17/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
					ctx2.drawImage( background1, 3, 3, Math.floor( ( 284/906 ) * comicWidth ), Math.floor( ( 290/345 ) * comicHeight ) );
					//caption
					var cap1 = document.getElementById( "webcomic-p1-cap" ).value;
					var cap1Bottom = 0;
					var cap1BottomPrev = 0;
					if ( cap1 ) {
						ctx.font = "bold 15px " + mainFont;
						ctx2.font = "bold 15px " + mainFont;
						
						cap1Bottom = wrapText(ctx, cap1, Math.floor( ( 20/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
						cap1Bottom = cap1Bottom - 28;
						cap1BottomPrev = wrapText( ctx2, cap1, 6, Math.floor( ( 20/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
						cap1BottomPrev = cap1BottomPrev - 6;
						
						ctx.fillStyle = '#FFFFFF';
						ctx.strokeStyle = '#000000';
						ctx2.fillStyle = '#FFFFFF';
						ctx2.strokeStyle = '#000000';
						//ctx.globalCompositeOperation = "destination-over";
						ctx.fillRect( Math.floor( ( 17/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), cap1Bottom );
						ctx.strokeRect( Math.floor( ( 17/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ), cap1Bottom );
						ctx2.fillRect( 3, 3, Math.floor( ( 284/906 ) * comicWidth ), cap1BottomPrev );
						ctx2.strokeRect( 3, 3, Math.floor( ( 284/906 ) * comicWidth ), cap1BottomPrev );
						ctx.globalCompositeOperation = "source-over";
						ctx.fillStyle = '#000000';
						ctx.strokeStyle = '#000000';
						wrapText( ctx, cap1, Math.floor( ( 20/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
						ctx2.globalCompositeOperation = "source-over";
						ctx2.fillStyle = '#000000';
						ctx2.strokeStyle = '#000000';
						wrapText( ctx2, cap1, 6, Math.floor( ( 20/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
						ctx.font = "12pt " + mainFont;
						ctx2.font = "12pt " + mainFont;
						
					}
				
					//characters
						
					var charLeft1 = new Image();
						charLeft1.onload = function() {
							
							ctx.drawImage( charLeft1, Math.floor( ( 21/906 ) * comicWidth ), ( Math.floor( ( 315/345 ) * comicHeight ) - charLeft1.height ) );
							ctx2.drawImage( charLeft1, 7, ( Math.floor( ( 293/345 ) * comicHeight ) - charLeft1.height ) );
							
							//speech bubble
							thisText = document.getElementById( 'webcomic-p1-left' ).value;
						
							if ( thisText != "" ) { //generate speech bubble if the text is entered
								var speechLeft1 = new Image();
								speechLeft1.onload = function() {
									var tmpImg = new Image();
									tmpImg.onload = function() {
										
										thisText = document.getElementById( 'webcomic-p1-left' ).value;
										if ( document.getElementById( 'webcomic-p1-right' ).value == "" ) {
											if ( document.getElementById( 'webcomic-p1-ext' ).checked == true ) {
												desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
											}
											else {
												desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
											}
										}
										else {
											desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
										}
										desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft1.height - Math.floor( ( 10/345 ) * comicHeight ) - cap1Bottom;
										
										if ( tmpImg.height > charLeft1.height ) {
											if ( ( document.getElementById( 'webcomic-p1-right' ).value == "" ) && ( document.getElementById( 'webcomic-p1-ext' ).checked == true ) ) {
												desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - tmpImg.height - Math.floor( ( 10/345 ) * comicHeight ) - cap1Bottom;
											}
										}
										
										ctx.drawImage( speechLeft1, Math.floor( ( 21/906 ) * comicWidth ), ( Math.floor( ( 30/345 ) * comicHeight ) + cap1Bottom ), desiredWidth, desiredHeight );
										ctx2.drawImage( speechLeft1, 7, ( Math.floor( ( 8/345 ) * comicHeight ) + cap1BottomPrev ), desiredWidth, ( desiredHeight + cap1Bottom - cap1BottomPrev ) );
										
										//text
										fontSize = document.getElementById( 'webcomic-p1-left-font-size' ).value;
										font = document.getElementById( 'webcomic-p1-left-font' ).value;
										ctx.fillStyle = "#000000";
										ctx.font = fontSize + "pt " + font;
										ctx2.fillStyle = "#000000";
										ctx2.font = fontSize + "pt " + font;
										
										cap1Bottom = Math.floor( cap1Bottom * 1.17 );
										adjustmentSize = Math.floor( ( 345 - comicHeight ) / 8 );
										
										wrapText( ctx, thisText, Math.floor( ( 32/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap1Bottom + Math.floor( fontSize ) + adjustmentSize ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
										wrapText( ctx2, thisText, Math.floor( ( 18/906 ) * comicWidth ), ( Math.floor( ( 23/345 ) * comicHeight ) + cap1BottomPrev + Math.floor( fontSize ) + adjustmentSize ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), ( desiredHeight + cap1Bottom - cap1BottomPrev ), Math.floor( fontSize * 1.6 ) );
										ctx.fillStyle = "#FFFFFF";
										ctx.font = "12pt " + mainFont;
										ctx2.fillStyle = "#FFFFFF";
										ctx2.font = "12pt " + mainFont;
									};
									tmpImg.src = cright1;
									
								};
								thisWordType = document.getElementsByName( 'webcomic-p1-left-word-type' );
								thisWordType = getWordRadio( thisWordType );
								if ( thisWordType == 'word' ) {
									if ( cleft1 == emptyChar ) {
										speechLeft1.src = speechoff;
									}
									else {
										speechLeft1.src = speechbubble;
									}
								}
								else {
									if ( cleft1 == emptyChar ) {
										speechLeft1.src = thoughtoff;
									}
									else {
										speechLeft1.src = thoughtbubble;
									}
								}
							}
						
						
						
						};
						charLeft1.src = cleft1;
				
					
				var charRight1 = new Image();
					charRight1.onload = function() {
						
						ctx.drawImage( charRight1, ( Math.floor( ( 300/906 ) * comicWidth ) - charRight1.width ), ( Math.floor( ( 315/345 ) * comicHeight ) - charRight1.height ) );
						ctx2.drawImage( charRight1, ( Math.floor( ( 286/906 ) * comicWidth ) - charRight1.width ), ( Math.floor( ( 293/345 ) * comicHeight ) - charRight1.height ) );
						
						//speech bubble
						thisText = document.getElementById( 'webcomic-p1-right' ).value;
						if ( thisText != "" ) { //generate speech bubble if there is text
							var speechRight1 = new Image();
							speechRight1.onload = function() {
								thisText = document.getElementById( 'webcomic-p1-right' ).value;
								if ( document.getElementById( 'webcomic-p1-left' ).value == "" ) {
									if ( document.getElementById( 'webcomic-p1-ext' ).checked == true ) {
										desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
										moveRightText = 1;
									}
									else {
										desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
									}
								}
								else {
									desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
								}
							
								desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charRight1.height - Math.floor( ( 10/345 ) * comicHeight ) - cap1Bottom;
									if ( charLeft1.height > charRight1.height ) {
										if ( ( document.getElementById( 'p1left' ).value == "" ) && ( document.getElementById( 'webcomic-p1-ext' ).checked == true ) ) {
											desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft1.height - Math.floor( ( 10/345 ) * comicHeight ) - cap1Bottom;
										}
									}
								
								
								ctx.save();
								ctx.scale( -1, 1 );							
								ctx2.save();
								ctx2.scale( -1, 1 );							
								ctx.drawImage( speechRight1, -( Math.floor( ( 295/906 ) * comicWidth ) ), ( Math.floor( ( 30/345 ) * comicHeight ) + cap1Bottom ), desiredWidth, desiredHeight );
								ctx2.drawImage( speechRight1, -( Math.floor( ( 281/906 ) * comicWidth ) ), ( Math.floor( ( 8/345 ) * comicHeight ) + cap1Bottom ), desiredWidth, ( desiredHeight + cap1Bottom - cap1BottomPrev ) );
								ctx.restore();
								ctx2.restore();
								
								//text
								
								fontSize = document.getElementById( 'webcomic-p1-right-font-size' ).value;
								font = document.getElementById( 'webcomic-p1-right-font' ).value;
								ctx.fillStyle = "#000000";
								ctx.font = fontSize + "pt " + font;
								ctx2.fillStyle = "#000000";
								ctx2.font = fontSize + "pt " + font;
								if ( moveRightText == 1 ) {
									wrapText( ctx, thisText, Math.floor( ( 56/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap1Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.5 ) );
									wrapText( ctx2, thisText, Math.floor( ( 42/906 ) * comicWidth ), ( Math.floor( ( 25/345 ) * comicHeight ) + cap1BottomPrev + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), ( desiredHeight + cap1Bottom - cap1BottomPrev ), Math.floor( fontSize * 1.6 ) );
									moveRightText = 0;
								}
								else {
									wrapText( ctx, thisText, Math.floor( ( 172/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap1Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.5 ) );
									wrapText( ctx2, thisText, Math.floor( ( 158/906 ) * comicWidth ), ( Math.floor( ( 25/345 ) * comicHeight ) + cap1Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), ( desiredHeight + cap1Bottom - cap1BottomPrev ), Math.floor( fontSize * 1.6 ) );
								}
								ctx.fillStyle = "#FFFFFF";
								ctx.font = "12pt " + mainFont;
								ctx2.fillStyle = "#FFFFFF";
								ctx2.font = "12pt " + mainFont;
								
							};
								thisWordType = document.getElementsByName( 'webcomic-p1-right-word-type' );
								thisWordType = getWordRadio( thisWordType );
								if ( thisWordType == 'word' ) {
									if ( cright1 == emptyChar ) {
										speechRight1.src = speechoff;
									}
									else {
										speechRight1.src = speechbubble;
									}
								}
								else {
									if ( cright1 == emptyChar ) {
										speechRight1.src = thoughtoff;
									}
									else {
										speechRight1.src = thoughtbubble;
									}
								}
						}
						
					};
					charRight1.src = cright1;
				
			};
			background1.src = bg1;	
		
		
		//panel2
		
		//background
				
		var background2 = new Image();
		background2.onload = function() {
			ctx.drawImage( background2, Math.floor( ( 310/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ), Math.floor( ( 284/906 ) * comicWidth ),Math.floor( ( 290/345 ) * comicHeight ) );
				
			//caption
				var cap2 = document.getElementById( "webcomic-p2-cap" ).value;
				var cap2Bottom = 0;
				if ( cap2 ) {
					ctx.font = "bold 15px " + mainFont;
					
					cap2Bottom = wrapText( ctx, cap2, Math.floor( ( 313/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
					cap2Bottom = cap2Bottom - 28;
					ctx.fillStyle = '#FFFFFF';
					ctx.strokeStyle = '#000000';
					//ctx.globalCompositeOperation = "destination-over";
					ctx.fillRect( Math.floor( ( 310/906 ) * comicWidth ),Math.floor( ( 25/345 ) * comicHeight ),Math.floor( ( 284/906 ) * comicWidth ),cap2Bottom );
					ctx.strokeRect( Math.floor( ( 310/906 ) * comicWidth ),Math.floor( ( 25/345 ) * comicHeight ),Math.floor( ( 284/906 ) * comicWidth ),cap2Bottom );
					ctx.globalCompositeOperation = "source-over";
					ctx.fillStyle = '#000000';
					ctx.strokeStyle = '#000000';
					wrapText( ctx, cap2, Math.floor( ( 313/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
					ctx.font = "12pt " + mainFont;
					
				}

					
			//characters
			
			
			var charLeft2 = new Image();
				charLeft2.onload = function() {
					
					ctx.drawImage( charLeft2, Math.floor( ( 314/906 ) * comicWidth ), ( Math.floor( ( 315/345 ) * comicHeight ) - charLeft2.height ) );
					
					//speech bubble
					thisText = document.getElementById( 'webcomic-p2-left' ).value;
						if ( thisText != "" ) { //generate speech bubble if the text is entered	
							
							var speechLeft2 = new Image();
								speechLeft2.onload = function() {
								var tmpImg2 = new Image();
									tmpImg2.onload = function() {
										
										thisText = document.getElementById( 'webcomic-p2-left' ).value;
										if ( document.getElementById( 'webcomic-p2-right' ).value == "" ) {
											if ( document.getElementById( 'webcomic-p2-ext' ).checked == true ) {
												desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
											}
											else {
												desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
											}
										}
										else {
											desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
										}
										desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft2.height - Math.floor( ( 10/345 ) * comicHeight ) - cap2Bottom;
										
										if ( tmpImg2.height > charLeft2.height ) {
											if ( ( document.getElementById( 'webcomic-p2-right' ).value == "" ) && ( document.getElementById( 'webcomic-p2-ext' ).checked == true ) ) {
												desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - tmpImg2.height - Math.floor( ( 10/345 ) * comicHeight ) - cap2Bottom;
											}
										}
									
									ctx.drawImage( speechLeft2, Math.floor( ( 314/906 ) * comicWidth ), Math.floor( ( 30/345 ) * comicHeight ) + cap2Bottom, desiredWidth, desiredHeight );
									
									//text
									
									fontSize = document.getElementById( 'webcomic-p2-left-font-size' ).value;
									font = document.getElementById( 'webcomic-p2-left-font' ).value;
									ctx.fillStyle = "#000000";
									ctx.font = fontSize + "pt " + font;
									wrapText( ctx, thisText, Math.floor( ( 325/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap2Bottom  + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
									ctx.fillStyle = "#FFFFFF";
									ctx.font = "12pt " + mainFont;
								};
								tmpImg2.src = cright2;
							};
								thisWordType = document.getElementsByName( 'webcomic-p2-left-word-type' );
								thisWordType = getWordRadio( thisWordType );
								if ( thisWordType == 'word' ) {
									if ( cleft2 == emptyChar ) {
										speechLeft2.src = speechoff;
									}
									else {
										speechLeft2.src = speechbubble;
									}
								}
								else {
									if ( cleft2 == emptyChar ) {
										speechLeft2.src = thoughtoff;
									}
									else {
										speechLeft2.src = thoughtbubble;
									}
								}
						}	
							
				};
				charLeft2.src = cleft2;
			
		var charRight2 = new Image();
			charRight2.onload = function() {
				
				ctx.drawImage( charRight2, ( Math.floor( ( 591/906 ) * comicWidth ) - charRight2.width ), ( Math.floor( ( 315/345 ) * comicHeight ) - charRight2.height ) );
				
			//speech bubble
						thisText = document.getElementById( 'webcomic-p2-right' ).value;
						if ( thisText != "" ) { //generate speech bubble if there is text
							var speechRight2 = new Image();
							speechRight2.onload = function() {
								thisText = document.getElementById( 'webcomic-p2-right' ).value;
								if ( document.getElementById( 'webcomic-p2-left' ).value == "" ) {
									if ( document.getElementById( 'webcomic-p2-ext' ).checked == true ) {
										desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
										moveRightText = 1;
									}
									else {
										desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
									}
								}
								else {
									desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
								}
							
								desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charRight2.height - Math.floor( ( 10/345 ) * comicHeight ) - cap2Bottom;
									if ( charLeft2.height > charRight2.height ) {
										if ( ( document.getElementById( 'webcomic-p2-left' ).value == "" ) && ( document.getElementById( 'webcomic-p2-ext' ).checked == true ) ) {
											desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft2.height - Math.floor( ( 10/345 ) * comicHeight ) - cap2Bottom;
										}
									}
								
								
								ctx.save();
								ctx.scale( -1, 1 );							
								ctx.drawImage( speechRight2, -( Math.floor( ( 588/906 ) * comicWidth ) ), ( Math.floor( ( 30/345 ) * comicHeight ) + cap2Bottom ), desiredWidth, desiredHeight );
								ctx.restore();
								
								//text
								
								fontSize = document.getElementById( 'webcomic-p2-right-font-size' ).value;
								font = document.getElementById( 'webcomic-p2-right-font' ).value;
								ctx.fillStyle = "#000000";
								ctx.font = fontSize + "pt " + font;
								if ( moveRightText == 1 ) {
									wrapText( ctx, thisText, Math.floor( ( 350/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap2Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
									moveRightText = 0;
								}
								else {
									wrapText( ctx, thisText, Math.floor( ( 466/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap2Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
								}
								ctx.fillStyle = "#FFFFFF";
								ctx.font = "12pt " + mainFont;
								
							};
								thisWordType = document.getElementsByName( 'webcomic-p2-right-word-type' );
								thisWordType = getWordRadio( thisWordType );
								if ( thisWordType == 'word' ) {
									if ( cright2 == emptyChar ) {
										speechRight2.src = speechoff;
									}
									else {
										speechRight2.src = speechbubble;
									}
								}
								else {
									if ( cright2 == emptyChar ) {
										speechRight2.src = thoughtoff;
									}
									else {
										speechRight2.src = thoughtbubble;
									}
								}
						}
						
					};
				charRight2.src = cright2;
			};
			background2.src = bg2;
		//panel3
		
		//background
				
			var background3 = new Image();
			background3.onload = function() {
				ctx.drawImage( background3, Math.floor( ( 603/906 ) * comicWidth ), Math.floor( ( 25/345 ) * comicHeight ),Math.floor( ( 284/906 ) * comicWidth ),Math.floor( ( 290/345 ) * comicHeight ) );
				
				//caption
				var cap3 = document.getElementById( "webcomic-p3-cap" ).value;
				var cap3Bottom = 0;
				if ( cap3 ) {
					ctx.font = "bold 15px " + mainFont;
					cap3Bottom = wrapText( ctx, cap3, Math.floor( ( 606/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
					cap3Bottom = cap3Bottom - 28;
					ctx.fillStyle = '#FFFFFF';
					ctx.strokeStyle = '#000000';
					//ctx.globalCompositeOperation = "destination-over";
					ctx.fillRect( Math.floor( ( 603/906 ) * comicWidth ),Math.floor( ( 25/345 ) * comicHeight ),Math.floor( ( 284/906 ) * comicWidth ),cap3Bottom );
					ctx.strokeRect( Math.floor( ( 603/906 ) * comicWidth ),Math.floor( ( 25/345 ) * comicHeight ),Math.floor( ( 284/906 ) * comicWidth ),cap3Bottom );
					ctx.globalCompositeOperation = "source-over";
					ctx.fillStyle = '#000000';
					ctx.strokeStyle = '#000000';
					wrapText( ctx, cap3, Math.floor( ( 606/906 ) * comicWidth ), Math.floor( ( 42/345 ) * comicHeight ), Math.floor( ( 281/906 ) * comicWidth ), 30, 18 );
					ctx.font = "12pt " + mainFont;

					
				}
				
				//characters
				
				
				var charLeft3 = new Image();
					charLeft3.onload = function() {
						
						ctx.drawImage( charLeft3, Math.floor( ( 607/906 ) * comicWidth ), ( Math.floor( ( 315/345 ) * comicHeight ) - charLeft3.height ) );
						
					//speech bubble
						thisText = document.getElementById( 'webcomic-p3-left' ).value;
							if ( thisText != "" ) { //generate speech bubble if the text is entered	
								
								var speechLeft3 = new Image();
									speechLeft3.onload = function() {
									var tmpImg3 = new Image();
										tmpImg3.onload = function() {
											
											thisText = document.getElementById( 'webcomic-p3-left' ).value;
											if ( document.getElementById( 'webcomic-p3-right' ).value == "" ) {
												if ( document.getElementById( 'webcomic-p3-ext' ).checked == true ) {
													desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
												}
												else {
													desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
												}
											}
											else {
												desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
											}
											desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft3.height - Math.floor( ( 10/345 ) * comicHeight ) - cap3Bottom;
											
											if ( tmpImg3.height > charLeft3.height ) {
												if ( ( document.getElementById( 'webcomic-p3-right' ).value == "" ) && ( document.getElementById( 'webcomic-p3-ext' ).checked == true ) ) {
													desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - tmpImg3.height - Math.floor( ( 10/345 ) * comicHeight ) - cap3Bottom;
												}
											}
										
										
										ctx.drawImage( speechLeft3, Math.floor( ( 609/906 ) * comicWidth ), ( Math.floor( ( 30/345 ) * comicHeight ) + cap3Bottom ), desiredWidth, desiredHeight );
										
										//text
										
										fontSize = document.getElementById( 'webcomic-p3-left-font-size' ).value;
										font = document.getElementById( 'webcomic-p3-left-font' ).value;
										ctx.fillStyle = "#000000";
										ctx.font = fontSize + "pt " + font;
										wrapText( ctx, thisText, Math.floor( ( 620/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap3Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
										ctx.fillStyle = "#FFFFFF";
										ctx.font = "12pt " + mainFont;
									};
									tmpImg3.src = cright3;
								};
								thisWordType = document.getElementsByName( 'webcomic-p3-left-word-type' );
								thisWordType = getWordRadio( thisWordType );
								if ( thisWordType == 'word' ) {
									if ( cleft3 == emptyChar ) {
										speechLeft3.src = speechoff;
									}
									else {
										speechLeft3.src = speechbubble;
									}
								}
								else {
									if ( cleft3 == emptyChar ) {
										speechLeft3.src = thoughtoff;
									}
									else {
										speechLeft3.src = thoughtbubble;
									}
								}
							}	
								
					};
					charLeft3.src = cleft3;
					
					var charRight3 = new Image();
					charRight3.onload = function() {
						
						ctx.drawImage( charRight3, ( Math.floor( ( 884/906 ) * comicWidth ) - charRight3.width ), ( Math.floor( ( 315/345 ) * comicHeight ) - charRight3.height ) );
						
							//speech bubble
								thisText = document.getElementById( 'webcomic-p3-right' ).value;
								if ( thisText != "" ) { //generate speech bubble if there is text
									var speechRight3 = new Image();
									speechRight3.onload = function() {
										thisText = document.getElementById( 'webcomic-p3-right' ).value;
										if ( document.getElementById( 'webcomic-p3-left' ).value == "" ) {
											if ( document.getElementById( 'webcomic-p3-ext' ).checked == true ) {
												desiredWidth = Math.floor( ( 250/906 ) * comicWidth );
												moveRightText = 1;
											}
											else {
												desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
											}
										}
										else {
											desiredWidth = Math.floor( ( 135/906 ) * comicWidth );
										}
									
										desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charRight3.height - Math.floor( ( 10/345 ) * comicHeight ) - cap3Bottom;
											if ( charLeft3.height > charRight3.height ) {
												if ( ( document.getElementById( 'webcomic-p3-left' ).value == "" ) && ( document.getElementById( 'webcomic-p3-ext' ).checked == true ) ) {
													desiredHeight = Math.floor( ( 290/345 ) * comicHeight ) - charLeft3.height - Math.floor( ( 10/345 ) * comicHeight ) - cap3Bottom;
												}
											}
										
										
										ctx.save();
										ctx.scale( -1, 1 );							
										ctx.drawImage( speechRight3, -( Math.floor( ( 881/906 ) * comicWidth ) ), ( Math.floor( ( 30/345 ) * comicHeight ) + cap3Bottom ), desiredWidth, desiredHeight );
										ctx.restore();
										
										//text
										
										fontSize = document.getElementById( 'webcomic-p3-right-font-size' ).value;
										font = document.getElementById( 'webcomic-p3-right-font' ).value;
										ctx.fillStyle = "#000000";
										ctx.font = fontSize + "pt " + font;
										if ( moveRightText == 1 ) {
											wrapText( ctx, thisText, Math.floor( ( 643/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap3Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, 18 );
											moveRightText = 0;
										}
										else {
											wrapText( ctx, thisText, Math.floor( ( 759/906 ) * comicWidth ), ( Math.floor( ( 44/345 ) * comicHeight ) + cap3Bottom + Math.floor( fontSize ) ), ( desiredWidth - Math.floor( ( 20/906 ) * comicWidth ) ), desiredHeight, Math.floor( fontSize * 1.6 ) );
										}
										ctx.fillStyle = "#FFFFFF";
										ctx.font = "12pt " + mainFont;
										
									};
									thisWordType = document.getElementsByName( 'webcomic-p3-right-word-type' );
									thisWordType = getWordRadio( thisWordType );
									if ( thisWordType == 'word' ) {
										if ( cright3 == emptyChar ) {
											speechRight3.src = speechoff;
										}
										else {
											speechRight3.src = speechbubble;
										}
									}
									else {
										if ( cright3 == emptyChar ) {
											speechRight3.src = thoughtoff;
										}
										else {
											speechRight3.src = thoughtbubble;
										}
									}
								}
								
							};
					charRight3.src = cright3;
				};
				background3.src = bg3;
			
			//Add URL to bottom
			ctx.fillStyle = "#000000";
			ctx.font = "11pt " + mainFont;
			var thisUrl = document.getElementById( 'webcomic-url' ).value;
			ctx.fillText( thisUrl, Math.floor( ( 603/906 ) * comicWidth ), Math.floor( ( 332/345 ) * comicHeight ) );
			ctx.font = "bold 11pt " + mainFont;
			var thisName = document.getElementById( 'webcomic-title' ).value;
			ctx.fillText( thisName, Math.floor( ( 17/906 ) * comicWidth ), Math.floor( ( 332/345 ) * comicHeight ) );
			
			//add save button
			addSaveButton();
}			
//notes ctx.drawImage( speechRight3, -881, 30, desiredWidth, desiredHeight );
//notes wrapText( ctx, "Put it in my mouth.", 747, 60, desiredWidth-20, desiredHeight, 18 );
			
			//functions
			
function wrapText( context, text, x, y, maxWidth, bubbleHeight, lineHeight ) {
	var words = text.split( ' ' );
	var line = '';
	var metrics='';
	var totalHeight = 0;
	var totalLines = [];
	var tempY = y;
	var testWidth = '';
	var testLine = '';
	var iterations = 1;
	for( var n = 0; n < words.length; n++ ) {
		testLine = line + words[n] + ' ';
		metrics = context.measureText( testLine );
		testWidth = metrics.width;
		if ( testWidth > maxWidth && n > 0 ) {
			//thisHeight = parseInt( context.font );
			thisHeight = parseInt( lineHeight ) + 2;
			totalHeight += thisHeight;
			line = words[n] + ' ';
	  }
	  else {
		line = testLine;
	  }
	  
	}
	thisHeight = parseInt( lineHeight );
	totalHeight += thisHeight;
	totalHeight -= 1;
	//if ( ( n == 0 ) || ( n == 1 ) ) {
	if ( lineHeight > 12 ) {
		totalHeight -= Math.floor( lineHeight / 4 );
	}
	//}
	line = '';
	if ( bubbleHeight > 60 ) { //prevent triggering for captions
		bubbleHeight = bubbleHeight - 60;
		var adjustHeight = ( ( bubbleHeight - totalHeight ) / 2 );
		y = y + adjustHeight;
	}

	for( var n = 0; n < words.length; n++ ) {
	  testLine = line + words[n] + ' ';
	  metrics = context.measureText( testLine );
	  testWidth = metrics.width;
	  if ( testWidth > maxWidth && n > 0 ) {
		metrics=context.measureText( line );
		//console.log( "drawing with line " + line + " x " + ( x + ( ( maxWidth - metrics.width ) / 2 ) ) + " y " + y );
		context.fillText( line, x + ( ( maxWidth - metrics.width ) / 2 ), y );
		

		line = words[n] + ' ';
		y += lineHeight;
		iterations = iterations + 1;
	  }
	  else {
		line = testLine;
	  }
	}
	metrics=context.measureText( line );
	context.fillText( line, x + ( ( maxWidth - metrics.width ) / 2 ), y );

	return( y + ( lineHeight / 2 ) ); //return the bottom of the text
}


function charSelect( panel ) {
	var returnvalue = '<div class="webcomic-character-select"><div>Left Char:</div><div><select id="webcomic-' + panel + '-left-char" class="webcomic-char-select"';
	if ( panel == 'p1' ) {
		returnvalue += ' onchange="updateLeftChars()"';
	}
	returnvalue += '><option value="None" selected>None</option>';
	returnvalue += getCharList();
	returnvalue += '</select></div><div id="webcomic-char-left-alt-select-' + panel + '" class="webcomic-char-alt"></div></div><div class="webcomic-character-select"><div>Right Char:</div><div><select id="webcomic-' + panel + '-right-char" class="webcomic-char-select"';
	if ( panel == 'p1' ) {
		returnvalue += ' onchange="updateRightChars()"';
	}
	returnvalue += '><option value="None" selected>None</option>';
	returnvalue += getCharList();
	returnvalue += '</select></div><div id="webcomic-char-right-alt-select-' + panel + '" class="webcomic-char-alt"></div></div>';
	return( returnvalue );
}
function getCharList() {
	var returnvalue = '';
	var ilen = 0;
	var clist = '';
	clist = document.getElementsByClassName( "webcomic-character-choice" );
	var thisId = '';
	var prefix = 'webcomic-char-';
	for ( var i = 0, ilen = clist.length; i < ilen; i++ ) {
		thisId = clist[i].id;
		
		returnvalue += '<option value="' + thisId + '">' + thisId.replace(/_/g," ").replace(prefix,'') + '</option>';
	}
	return( returnvalue );
}
function getWordRadio( wordtype ) {
	for ( var i = 0, length = wordtype.length; i < length; i++ ) {
		if ( wordtype[i].checked ) {
			return( wordtype[i].value );
		}
	}
	return( 0 ); //this should never happen
}
function getCharAlts( pane, orientation, character ) {
	var returnvalue = '';
	if ( character != "None" ) {
		var ilen = 0;
		var i = 0;
		var j = 0;
		var theseElements = '';
		returnvalue = '<form>';
		theseElements = document.getElementById( character ).children;
		for ( i = 0, ilen = theseElements.length; i < ilen; i++ ) {
			if ( ( ( theseElements[i].className == "left" ) && ( orientation == "left" ) ) || ( ( theseElements[i].className == "right" ) && ( orientation == "right" ) ) ) {
				returnvalue += '<input type="radio" name="webcomic-' + pane + '-'+ orientation + '-alt" value="' + theseElements[i].id + '" ';
				if ( j == 0 ) {
					returnvalue += 'checked';
				}
				returnvalue += '/>';
				returnvalue += '<img src="' + theseElements[i].src + '" width="50"/><br/>';
				j++;
			}
			
		}
		returnvalue += '</form>';
	}
	return( returnvalue );
}
function loadCharAlt( character, idToFind ) {
	var returnvalue = document.getElementById( 'webcomic-empty-character' ).src;
	if ( character != "None" ) {
		character = document.getElementById( idToFind ).children;
		var llen = 0;
		var i = 0;
		for ( i = 0, llen = character.length; i < llen; i++ ) {
			if ( ( character[i] ) && ( character[i].tagName == "INPUT" ) ) {
				if ( character[i].checked ) {
					returnvalue = document.getElementById( character[i].value ).src;
					character = '';
					return( returnvalue );
				}
			}
				
		};
	}
	return( returnvalue );
} 
function bgSelect( panel ) {
	var returnvalue = '<strong>Background:</strong> <select id="webcomic-background-' + panel + '"';
	if (panel == 'p1') {
		returnvalue += ' onchange="updateBackgrounds()"';
	}
	returnvalue += '><option value="white" selected>None</option>'; 
	var theseBackgrounds = '';
	theseBackgrounds = document.getElementsByClassName( "webcomic-background-choice" );
	var llen = 0;
	var i = 0;
	var prefix = 'webcomic-bg-';
	var thisBackground = '';
	for ( i = 0, llen = theseBackgrounds.length; i < llen; i++ ) {
		thisBackground = theseBackgrounds[i].id;
		returnvalue += '<option value="' + thisBackground + '">' + thisBackground.replace(/_/g," ").replace(prefix,'') + '</option>';
	};
	
	returnvalue += '</select>';
	return( returnvalue );
}

function addSaveButton() {
	var saveSpace = document.getElementById( "webcomic-save-comic" );
	var saveButton = '<form><input type="button" id="webcomic-save-comic-to-server" value="Save Comic to Server"/></form>';
	saveSpace.innerHTML = saveButton;
	document.getElementById( "webcomic-save-comic-to-server" ).onclick = function() { 
		removeSaveButton( "Please wait...." );
		saveComicToServer(); 
	}
}
function removeSaveButton( text ) {
	var saveSpace = document.getElementById( "webcomic-save-comic" );
	var saveButton = '<div><strong>' + text + '</strong></div>';
	saveSpace.innerHTML = saveButton;
	
}
function saveComicToServer() {
	var securityNonce = document.getElementById( 'webcomic-create-page-nonce' ).value;
	var canvas = document.getElementsByTagName( 'canvas' )[0];
	var canvas2 = document.getElementsByTagName( 'canvas' )[1]; //the image preview

	var dataURL = canvas.toDataURL();
	var data2URL = canvas2.toDataURL();
	var charPrefix = 'webcomic-char-';
	var bgPrefix = 'webcomic-bg-';
	var comicTitle = encodeURIComponent( document.getElementById( 'webcomic-comic-title' ).value );
	var cap1 = encodeURIComponent( document.getElementById( 'webcomic-p1-cap' ).value );
	var cap2 = encodeURIComponent( document.getElementById( 'webcomic-p2-cap' ).value );
	var cap3 = encodeURIComponent( document.getElementById( 'webcomic-p3-cap' ).value );
	var bg1 = encodeURIComponent( document.getElementById( 'webcomic-background-p1' ).value.replace(/_/g," ").replace(bgPrefix,'') );
	var bg2 = encodeURIComponent( document.getElementById( 'webcomic-background-p2' ).value.replace(/_/g," ").replace(bgPrefix,'') );
	var bg3 = encodeURIComponent( document.getElementById( 'webcomic-background-p3' ).value.replace(/_/g," ").replace(bgPrefix,'') );
	var p1left = encodeURIComponent( document.getElementById( 'webcomic-p1-left' ).value );
	var p1right = encodeURIComponent( document.getElementById( 'webcomic-p1-right' ).value );
	var p2left = encodeURIComponent( document.getElementById( 'webcomic-p2-left' ).value );
	var p2right = encodeURIComponent( document.getElementById( 'webcomic-p2-right' ).value );
	var p3left = encodeURIComponent( document.getElementById( 'webcomic-p3-left' ).value );
	var p3right = encodeURIComponent( document.getElementById( 'webcomic-p3-right' ).value );
	var c1left = encodeURIComponent( document.getElementById( 'webcomic-p1-left-char' ).value.replace(/_/g," ").replace(charPrefix,'') );
	var c1right = encodeURIComponent( document.getElementById( 'webcomic-p1-right-char' ).value.replace(/_/g," ").replace(charPrefix,'') );
	var c2left = encodeURIComponent( document.getElementById( 'webcomic-p2-left-char' ).value.replace(/_/g," ").replace(charPrefix,'') );
	var c2right = encodeURIComponent( document.getElementById( 'webcomic-p2-right-char' ).value.replace(/_/g," ").replace(charPrefix,'') );
	var c3left = encodeURIComponent( document.getElementById( 'webcomic-p3-left-char' ).value.replace(/_/g," ").replace(charPrefix,'') );
	var c3right = encodeURIComponent( document.getElementById( 'webcomic-p3-right-char' ).value.replace(/_/g," ").replace(charPrefix,'') );


	if ( comicTitle == '' ) {
		comicTitle = 'Untitled';
	}
	/*
	var xmlHttpReq = false;
	var self = this;
	var strURL = 'saveComic.php';
	// Mozilla/Safari
	if (window.XMLHttpRequest) {
		self.xmlHttpReq = new XMLHttpRequest();
	}
	// IE
	else if (window.ActiveXObject) {
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	self.xmlHttpReq.open('POST', strURL, true);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	self.xmlHttpReq.onreadystatechange = function() {
		if (self.xmlHttpReq.readyState == 4) {
			//alert(self.xmlHttpReq.responseText);
			var savedComicUrl = document.getElementById("newComicUrl");
			savedComicUrl.innerHTML = '<a href="'+ self.xmlHttpReq.responseText + '">' + self.xmlHttpReq.responseText + '</a>';
			removeSaveButton("Comic Saved.");
		}
	}
	*/
	
	var comicData = {
		action: 'webcomic_add_comic_to_inactive_queue',
		comic: dataURL,
		preview: data2URL,
		title: comicTitle,
		cap1: cap1,
		cap2: cap2,
		cap3: cap3,
		bg1: bg1,
		bg2: bg2,
		bg3: bg3,
		p1: p1left,
		p2: p1right,
		p3: p2left,
		p4: p2right,
		p5: p3left,
		p6: p3right,
		char1: c1left,
		char2: c1right,
		char3: c2left,
		char4: c2right,
		char5: c3left,
		char6: c3right,
		security: securityNonce,
		
	}

	jQuery.ajax({
		type: "POST",
		data: comicData,
		dataType: "json",
		url: webcomic_vars.ajaxurl,
		success: function ( response ) {
			var savedComicUrl = document.getElementById( "webcomic-new-comic-url" );
			savedComicUrl.innerHTML = '<a href="'+ response.reply + '">' + response.reply + '</a>';
			removeSaveButton( "Comic Saved." );
		},
	}).fail(function ( data ) {
		console.log( "Failed to create comic. ajax failure: " );
		console.log( data );
		
	}); 
}

function updateAllFonts() {
	var chosenFont = document.getElementById( "webcomic-comic-font" ).selectedIndex;
	var fontSelects = document.getElementsByTagName( "select" );
	var llen = 0;
	var i = 0;
	var thisId = '';
	var testRegExp = new RegExp( ".*[font]$" );
	for ( i = 0, llen = fontSelects.length; i < llen; i++ ) {
		thisId = fontSelects[i].id;
		if ( testRegExp.test( thisId ) ) {
			fontSelects[i].selectedIndex = chosenFont;
		}
					
	}
}

function updateLeftChars() {
	var leftSelectedCharIndex = document.getElementById( "webcomic-p1-left-char" ).selectedIndex;
	var leftSelectedCharVal = document.getElementById( "webcomic-p1-left-char" ).value;
	if ( document.getElementById( "webcomic-p2-left-char" ).value == "None" ) {
		document.getElementById( "webcomic-p2-left-char" ).selectedIndex = leftSelectedCharIndex;
		updateSubChars( "webcomic-p2-left-char", leftSelectedCharVal );
	}
	if ( document.getElementById( "webcomic-p3-left-char" ).value == "None" ) {
		document.getElementById( "webcomic-p3-left-char" ).selectedIndex = leftSelectedCharIndex;
		updateSubChars( "webcomic-p3-left-char", leftSelectedCharVal );
	}
}

function updateRightChars() {
	var rightSelectedCharIndex = document.getElementById( "webcomic-p1-right-char" ).selectedIndex;
	var rightSelectedCharVal = document.getElementById( "webcomic-p1-right-char" ).value;
	if ( document.getElementById( "webcomic-p2-right-char" ).value == "None" ) {
		document.getElementById( "webcomic-p2-right-char" ).selectedIndex = rightSelectedCharIndex;
		updateSubChars( "webcomic-p2-right-char", rightSelectedCharVal );
	}
	if ( document.getElementById( "webcomic-p3-right-char" ).value == "None" ) {
		document.getElementById( "webcomic-p3-right-char" ).selectedIndex = rightSelectedCharIndex;
		updateSubChars( "webcomic-p3-right-char", rightSelectedCharVal );
	}
}
function updateBackgrounds() {
	var backgroundChoice = document.getElementById( "webcomic-background-p1" ).selectedIndex;
	if ( document.getElementById( "webcomic-background-p2" ).value == "white" ) {
		document.getElementById( "webcomic-background-p2" ).selectedIndex = backgroundChoice;
	}
	if ( document.getElementById( "webcomic-background-p3" ).value == "white" ) {
		document.getElementById( "webcomic-background-p3" ).selectedIndex = backgroundChoice;
	}
}

function prepareSubChars( e ) {
	updateSubChars( e.target.id, e.target.value );
}
function updateSubChars( theId, theValue ) {
	var thisTarget = '';
	var theseChildren = '';
	thisTarget = document.getElementById( theId ).parentNode.parentNode;
	theseChildren = thisTarget.children;
	var jlen = 0;
	for ( var i = 0, jlen = theseChildren.length; i < jlen; i++ ) {
		if ( theseChildren[i].className == "webcomic-char-alt" ) {
			theseChildren[i].innerHTML = "";
			//we have located the space to add alternate choices. Generate a list.
			
			var thisPane = theId.match(/[p][0-9]/i)[0];
			var regExpr = /webcomic-[p][0-9]-(left|right)/ig;
			var thisOrientation = regExpr.exec( theId )[1];

			theseChildren[i].innerHTML = getCharAlts( thisPane, thisOrientation, theValue );
		}
	};
	createComic();
	webcomicSaveTriggers();
}


jQuery( document ).ready( function () {

	webcomicSaveTriggers();

});
function throttle( f, delay ){ //credit to Remy Sharp (https://remysharp.com/2010/07/21/throttling-function-calls) for this handy throttle function
    var timer = null;
    return function(){
        var context = this, args = arguments;
        clearTimeout( timer );
        timer = window.setTimeout( function(){
            f.apply( context, args );
        },
        delay || 500 );
    };
}
function webcomicSaveTriggers() {
	
	jQuery( '#webcomic-panel-setup' ).find( 'select' ).change( function() {
		setTimeout(function(){
			createComic();
		}, 500); 
	});
	jQuery( '#webcomic-panel-setup' ).find( 'textarea' ).keyup( throttle( function() {
		createComic();
	}));
	jQuery( '#webcomic-panel-setup' ).find( 'input[type="radio"]' ).click( function() {
		createComic();
	});
	jQuery( '#webcomic-panel-setup' ).find( 'input[type="checkbox"]' ).change( function() {
		createComic();
	});
	jQuery( '#webcomic-panel-setup' ).find( 'input[type="text"]' ).keyup( throttle( function() {
		createComic();
	}));
}