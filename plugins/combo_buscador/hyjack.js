/**
*	Hyjack Select plugin for jQuery
*	
*	@version 
*		1.1.1
*
*	@author
*		Copyright (c) 2010 Brant Wills
*		August 31, 2010
*	
*	@requires 
*		jQuery 1.4.4+
*		Dual licensed under MIT or GPL Version 2 licenses
*
*	summary
*		Hijack the select control and replace with one which incorporates searching
*		through a textbox similar to a google suggest.
*
*	@returns 
*		the hidden original jQuery matched set 
*		along with the visible hyjacked version of the select control appeneded using before().
*
*	remarks
*		The selector control (this) is hidden from the page and the new hyjack select control is appended before.
*		Any change() to select val() are stored and triggered back to the select control (this) preventing code breaks.
*		Any modification to display needs to be handled through CSS not hardcoded.
*/

(function ($) {

    var // Plugin Variables
		index = 0;
	    version = 'v1.1.1',
	    hyjackable = 'select';

    // Extend Contains: CaSe INsensative 
    $.expr[':'].hj_contains = function (a, i, m) { return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; };

	// Generic Functions
	$.hyjack_select = {

		// Update select
		update: function (selector) {
			if(!selector){ $.hyjack_select.update_all(); return; }
			
			$('.hjsel_container').each(function(){
				if($(this).data('hyjack.container') === $(selector).data('hyjacked.index'))
				{
					$(this).hide();
					return false; // break loop
				}
			});		
			
			$(selector)
				.data('is.hyjacked',false)
				.hyjack_select();
		},
				
		// Update everything
		update_all: function()
		{
			$('.hjsel_container').hide();
			
			$(':input').filter(hyjackable).each(function(){
				if($(this).data('is.hyjacked'))
				{
					$(this)
						.data('is.hyjacked',false)
						.hyjack_select();
				}
			});
		},
		
		// Remove Hyjack
		dispose: function(selector){
			if(!selector){ $.hyjack_select.dispose_All(); return; }
			
			$('.hjsel_container').each(function(){
				if($(this).data('hyjack.container') === $(selector).data('hyjacked.index'))
				{
					$(this).hide();
					return false; // break loop
				}
			});		
			
			$(selector)
				.data('is.hyjacked',false)
				.show();
		},
		
		// Remove All Hyjack
		dispose_All: function()
		{
			$('.hjsel_container').each(function(){
				if($(this).data('hyjack.container'))
					$(this).hide();
			});
			
			$(':input').filter(hyjackable).each(function(){
				$(this)
					.data('is.hyjacked',false)
					.show();
			});
		}
	};

    // Hyjack Select
    $.fn.hyjack_select = function (settings) {
        try {

            // Default settings
            settings = jQuery.extend({

                ddImageClass: 'hjsel_ddImage',
                ddCancelClass: 'hjsel_ddCancel',
                emptyMessage: 'No se encuentra el dato solicitado',
				width: 'auto',
                restrictSearch: false
			}, settings);
			
			
            // Return hyjack control if allowed/hyjackable
            return this.filter(hyjackable).each(function () {

				// Igonor if already hyjacked or disabled
				if($(this).data('is.hyjacked')) return;
				if($(this).attr('disabled')){ $(this).show(); return;}
				index++; 
				
				$(this) // Disable autocomplete for firefox errors and hyjack
					.attr('autocomplete', 'off')
					.data('is.hyjacked', true)
					.data('hyjacked.index', index);	
                
                hj = { // Inject data and classes
                    selector: $(this),
                    container: $('<div/>')
								.data('hyjack.container', index)
								.addClass('hjsel_container'),
					
					select: $('<div/>')
								.data('hyjack.select', index)
								.addClass('hjsel_select'),
                    
					options: $('<div/>')
								.data('hyjack.options', index)
								.addClass('hjsel_options'),
                    
					txtbox: $('<input type="text"/>')
								.data('hyjack.txtbox', index)
								.addClass('hjsel_txtbox'),                    
					
					ddImage: $('<img/>')
								.data('hyjack.ddImage', index)
								.addClass(settings.ddImageClass)
								.attr('src', settings.ddImage),
								
                    ddCancel: $('<img/>')
								.data('hyjack.ddCancel', index)
								.addClass(settings.ddCancelClass)
								.attr('src', settings.ddCancel)
                };	
				
				return $(this) // Hyjack Select and return
					.hide()
				    .before(hyjack(this, hj));				
			});
        }
        catch (error) { alert('Hyjack ' + version + ' Error:\n' + error.description); }

		
		// Hyjack the Control
        function hyjack(value, hj) {

            // Build textbox and inject items
			_txtbox(hj);
            _items($(value), hj);

            hj.select // Inject hyjacked control
			    .append(hj.txtbox)
			    .append(hj.ddCancel)
			    .append(hj.ddImage);
            
            hj.container // Adjust Position and inject
			    .append(hj.select)
			    .append(hj.options);
			
            $(document) // Bind click functions
				.bind('click', function (e) { _away(e, hj); });
            
			return hj.container;
        }
		

		// Handle Tab
		function _keydown(hj, e){
			if(e.keyCode == 9){ _hide(hj) }
		}
		
		
		// Determin Key press
        function _keyup(hj, e) {
            switch (e.keyCode) {
                case 37: break; case 39: break; case 16: break; // unused
                case 17: break; case 18: break; case 19: break; // unused
                case 20: break; case 33: break; case 34: break; // unused
                case 35: break; case 36: break; case 45: break; // unused        
                case 9:  break; // tab handled on keyup
				
				case 13: // enter key 
					_dirSelect($('.hjsel_options_hover', hj.options), hj); 
					_reset(hj);
					_hide(hj);
					hj.selector.change();
					break;
                
				case 38: // up
					if($('.hjsel_noitems', hj.options).length) return; // no item check
					$dir = $('.hjsel_options_hover', hj.options).prev();
					if(hj.options.is(':visible'))
						while(!$dir.is(':visible'))
							if	($dir.prev().attr('val'))	$dir = $dir.prev(); // prev
							else $dir = $('li',hj.options).last(); // wrap
					_dirSelect($dir, hj);
                    break;
                
				case 40: // down
					if($('.hjsel_noitems', hj.options).length) return; // no item check
					$dir = $('.hjsel_options_hover', hj.options).next();
					if(hj.options.is(':visible'))
						while(!$dir.is(':visible'))
							if	($dir.next().attr('val'))	$dir = $dir.next(); // next
							else $dir = $('li',hj.options).first(); // wrap
					_dirSelect($dir, hj);
                    break;
                    
                case 27: // escape
                    _hide(hj);
                    break;
					
                default: // keyup
                    var i = 0, j = 0;
                    $('li', hj.options).remove('.hjsel_noitems').removeClass('hjsel_options_hover').hide();
                    $('li:hj_contains("' + hj.txtbox.val() + '")', hj.options).show();
                    $('li', hj.options).each(function () { if ($(this).is(':hidden')) { i++; } j++; });
                    if (i == j) {
                        hj.options.append(
							$('<li/>')
								.append(settings.emptyMessage)
								.addClass('hjsel_noitems')
						);
                    }
                    hj.options
						.scrollTop(0)
						.show();
                    break;
            }
        }


        // Direction Select Change
        function _dirSelect($dir, hj) {

			if ($dir != null) {
				hj.txtbox.val($dir.text());
				hj.selector.val($dir.attr('val'));
			}	
			
            $('li', hj.options).each(function (key, value) {
                $(value).removeClass('hjsel_options_hover');
                if (hj.selector.val() == $(value).attr('val')) {
                    $(value).addClass('hjsel_options_hover'); // Apply Highlight
                }
            });
        }

        
        // Clear any previous search and focus textbox
        function _clear(hj) {
            $('.hjsel_options').hide(); 
            hj.txtbox.val('').focus(); 
            hj.options.show(); 
		}


        // Display all option items except noitems
        function _reset(hj) {
            $('li', hj.options).show();
            $('.hjsel_noitems', hj.options).remove();
        }
		
		
		// Avoid leaving textbox text empty
		function _hide(hj)
		{
			hj.options.hide();
			if (hj.txtbox.val() === '') { hj.txtbox.val($('option:selected', hj.selector).text()); }
		}

		
        // Away click algorithms
        function _away(e, hj) {
            if ($(e.target).data('hyjack.ddImage') === hj.ddImage.data('hyjack.ddImage')) { hj.options.toggle(); }  // Dropdown Arrow Clicked
            else if ($(e.target).data('hyjack.ddCancel') === hj.ddCancel.data('hyjack.ddCancel')) { _clear(hj); }   // Cancel Button Clicked
            else if ($(e.target).data('hyjack.txtbox') === hj.txtbox.data('hyjack.txtbox') ||               		// Textbox Area Clicked
					 $(e.target).parents().data('hyjack.container') === hj.container.data('hyjack.container')) 		// Control Clicked
						{ hj.options.show(); }    
            else { _hide(hj) }
            
            // Determine fate of textbox text
            if (settings.restrictSearch) { hj.txtbox.val($('option:selected', hj.selector).text()); }
            _reset(hj);
        }

	
        // Inject the textbox values and events
        function _txtbox(hj) {
            hj.txtbox
                .bind('click', function () { _clear(hj); })
			    .bind('keydown', function (e) { _keydown(hj, e); })
				.bind('keyup', function(e) { _keyup(hj, e); })
                .val($('option:selected', hj.selector).text());
        }

		
        // Inject the listitems values and events
        function _items($value, hj) {
            var listitems = $('<ul/>');		
            $('option', $value).each(function (key, value) {
                listitems.append(
				    $('<li/>')
                        .attr('val', $(value).val())
					    .append($(value).text())
					    .bind('mouseenter', function () {
					        $('li', listitems).removeClass('hjsel_options_hover');
					        $(this).addClass('hjsel_options_hover');
							hj.txtbox.focus();
					    })
					    .bind('click', function () {
					        $(this).addClass('hjsel_options_hover');
					        hj.txtbox
                                .focus()
								.val($(value).text());

					        hj.selector
                                .val($(value).val())
                                .change();
					        _reset(hj);
					    })
			    );
            });
            hj.options.append(listitems);
        }

    };
})(jQuery);
