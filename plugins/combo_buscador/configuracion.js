// JavaScript Document
		////////////////////////////////////////////////
		// jQuery on page load
		////////////////////////////////////////////////
		$(function () {
			var wait = 3000;
			$('.old').fadeTo('fast',.4);

			// Hyjack Onload with all defaults
			$('.hyjack').hyjack_select();				
			
			// Display Function to help explain whats going on
			$(':input').change(function () {
	            $('.onchange', $(this).parent()).html('onChange event fired, value is now - ' + $(this).val()).show().delay(wait).fadeOut();
	        });

	    });
		
		
		////////////////////////////////////////////////
		// Perform real time DOM hyjacking
		////////////////////////////////////////////////
		function hyjack_realtime(selector) {
			if(!selector) return;

			alert('hijacking ' + selector);
			$(selector).hyjack_select();
		}
		
		
		////////////////////////////////////////////////
		// Perform real time disable
		////////////////////////////////////////////////
		function disable(selector)
		{
			if(!selector) return;
			alert('disabled ' + selector);
			
			$(selector).attr('disabled','disabled');
			$.hyjack_select.update(selector); /* Dont forget to update the selector!! */
		}
		
		////////////////////////////////////////////////
		// Perform real time enabel
		////////////////////////////////////////////////
		function enable(selector)
		{
			if(!selector) return;
			alert('enable ' + selector);
			
			$(selector).removeAttr('disabled');
			$.hyjack_select.update(selector); /* Dont forget to update the selector!! */
		}

		
		////////////////////////////////////////////////
		// Perform disposal of hyjacked control
		////////////////////////////////////////////////
		function dispose_hyjack(selector)
		{
			if(!selector) return;
			alert('disposing hyjack from ' + selector);
			
			$.hyjack_select.dispose(selector);
		}

		////////////////////////////////////////////////
		// Dispose all the Hyjacks on the Page
		////////////////////////////////////////////////
		function dispose_all()
		{
			alert('disposing all hyjacks');						
			$.hyjack_select.dispose(); 
			// $.hyjack_select.dispose_all();
		}
		
		
		////////////////////////////////////////////////
		// Perform adding 5 items and update
		////////////////////////////////////////////////
		function addItems(selector){
			if(!selector) return;
							
			var items = 5;
			alert('adding ' + items + ' items to ' + selector + ' and hyjacking!');
			
			// Add new items
			for(var i=0; i<items; i++){
				$(selector).append( 
					$('<option/>').val('newItem' + i).html('newItem' + i) 
				); 
			}

			$.hyjack_select.update(selector); /* Dont forget to update the selector!! */
		}

		
		////////////////////////////////////////////////
		// Update all the Hyjacks on the Page
		////////////////////////////////////////////////
		function update_all(){
			alert('updating all hyjacks');
			$.hyjack_select.update();
			$.hyjack_select.update_all();		
		}