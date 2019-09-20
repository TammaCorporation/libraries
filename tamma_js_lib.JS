let tamma_library = {
	initialize: function() {
		$('.copy-to-clipboard').click(this.copyToClipBoard); //
		$('.scroll-to-element-top').click(this.scrollToElement); //
        $('form').submit(this.formSubmitHandler);
	},
	Toast: function(snack,timeOut=0,color=0) {
		if (color == 0) { color = 'bg-dark'; } 
        let snackbar = $('<div class="smass-snackbar '+color+'"></div>');
        let snackbar_height = snackbar.css("margin-bottom");
        // place snackbar into dom
        $("body").append(snackbar);
        // attach effect to show snackbar
        snackbar.addClass('snackbar-effect');
        // Insert data into snackbar
        snackbar.append(snack);
        // hide snackbar on touch
        snackbar.click(function() {
            $(this).fadeOut(400, function() {
             $(this).remove();
            });       
        });
  
        if (timeOut == '') {
            timeOut = 10000;
        } 
        // remove snackbar if user ignores it
        setTimeout(function() {
        snackbar.fadeOut(400, function() {
            $(this).remove();
        });  
        }, timeOut); 		
	},
    copyToClipBoard: function() {
        let containerid = $(this).data('target');
        if (document.selection) { 
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select().createTextRange();
            document.execCommand("copy"); 

            window.getSelection().removeAllRanges();// to deselect
        
        } else if (window.getSelection) {
            var range = document.createRange();
             range.selectNode(document.getElementById(containerid));
             window.getSelection().addRange(range);
             document.execCommand("copy");

             window.getSelection().removeAllRanges();// to deselect
             docs.Toast(containerid + " code has been copied", 1000) ;
        }
    },
    scrollToElement: function() {
    	let element = $(this).data('target-element');
    	let elemTop = $("."+element).position();
    	$("html, body").animate({ scrollTop: elemTop.top });
    },
    formSubmitHandler: function( event ) {
    	event.preventDefault();
    	let formElements = $(this).find('input, select, textarea'); // traverse through form and pickup all fields
    	let formElementsId = []; // container that that holds the unique ids for all identified fields
        let formLength = formElements.length;

        let incrementer = 1;
        let limit = [];

    	// stack form fields unique ids in [formElementsId]
    	formElements.each(function() {
            limit = incrementer++;

    		let elementID = '#'+ $(this).attr('id');

    		// raise exception if field has no id
    		if ( $(this).attr('id') == undefined ) { 
    			console.error( 'tamma_library_error: \nthe following field has no ID: ' );
                return false;
    		} 
    		// raise exception if field id has no value
    		else if( $(this).attr('id') == "") { 
    			console.error( 'tamma_library_error: \nthe following field ID has no value: ');
                return false;
    		}
    		// stack ids
    		else { 
          		formElementsId.push( elementID );

                if (limit == formLength) {
                    validator();
                }
          	}
        });

        function validator() {
        	// find and flag empty fields
        	for (var i = 0; i < formElements.length; i++) {
        		const element = formElements[i];
        	
    			if ($(element).attr('type') == 'radio' || $(element).attr('type') == 'checkbox') {
    	        	if ( $(element+":checked").val() == '' || $(element+":checked").val() == null) {
    	        		tamma_library.Toast('Error: see console');
        				console.error( 'tamma_library_error: \nthe following field ID has no value: '+ $(element) );
                        break;
    	        	} 
    	      	} 
    	      	else {
    	        	if ( $(element).val() == '' ) {
                        tamma_library.Toast('Error: see console');
                        $(element).css('outline', '2px solid red');
                        console.error( 'tamma_library_error: \nthe following field ID has no value: ' );
                        break;
                    } 
    	      	}
    		}


            

        }




    }
}

tamma_library.initialize();


