var CustomWnd = function()
{
	// Puublic members
	this.imgDir = 'templates/customwnd/images';
	this.zIndex = 100000;
	this.dimmingColor = '#000000';
	this.dimmingOpacity = 0;
	this.animationType = '';
	this.padding = 15;
	this.showCloseButton = true;
	this.fixed = true;
	this.titleHtml = '';
	this.footerHtml = '';	
	
	// Callback function
	this.beforeOpen = function(){};
	this.afterOpen = function(){};
	this.beforeClose = function(){};
	this.afterClose = function(){};

	// Privat members
	var wnd_div = null;
	var dimming_div = null;
	var is_opened = false;
	
	var zIndex = this.zIndex;
	var dimmingColor = this.dimmingColor;
	var dimmingOpacity = this.dimmingOpacity;
	var animationType = this.animationType;
	var padding = this.padding;
	var showCloseButton = this.showCloseButton;
	var fixed = this.fixed;
	var titleHtml = '';
	var footerHtml = '';
	
	var beforeOpen = this.beforeOpen;
	var afterOpen = this.afterOpen;
	var beforeClose = this.beforeClose;
	var afterClose = this.afterClose;

	this.open = function(html, w, h, options)
	{
		zIndex = this.zIndex;
		dimmingColor = this.dimmingColor;
		dimmingOpacity = this.dimmingOpacity;
		animationType = this.animationType;
		padding = this.padding;
		showCloseButton = this.showCloseButton;
		fixed = this.fixed;
		titleHtml = this.titleHtml;
		footerHtml = this.footerHtml;
		
		beforeOpen = this.beforeOpen;
		afterOpen = this.afterOpen;
		beforeClose = this.beforeClose;
		afterClose = this.afterClose;
		
		if(options)
		{
			if(options.zIndex!=undefined) zIndex = options.zIndex; 
			if(options.dimmingColor!=undefined) dimmingColor = options.dimmingColor;
			if(options.dimmingOpacity!=undefined) dimmingOpacity = options.dimmingOpacity;
			if(options.animationType!=undefined) animationType = options.animationType;
			if(options.padding!=undefined) padding = options.padding;
			if(options.showCloseButton!=undefined) showCloseButton = options.showCloseButton;
			if(options.fixed!=undefined) fixed = options.fixed;
			if(options.titleHtml!=undefined) titleHtml = options.titleHtml;
			if(options.footerHtml!=undefined) footerHtml = options.footerHtml;
			
			if(options.beforeOpen!=undefined) beforeOpen = options.beforeOpen;
			if(options.afterOpen!=undefined) afterOpen = options.afterOpen;
			if(options.beforeClose!=undefined) afterOpen = options.beforeClose;
			if(options.afterClose!=undefined) afterOpen = options.afterClose;
		}
	
	    //Make dimming
	    dimming_div = jQuery('<div></div>');
	    dimming_div.css('position', 'absolute');
	    dimming_div.css('z-index', zIndex-1);
	    dimming_div.css('left', '0px');
	    dimming_div.css('top', '0px');
	    dimming_div.css('width', jQuery(document).width()+'px');
	    dimming_div.css('height', jQuery(document).height()+'px');
	    dimming_div.css('background', dimmingColor);
	    dimming_div.css('opacity', dimmingOpacity);
	    dimming_div.css('filter', 'progid:DXImageTransform.Microsoft.Alpha(opacity='+(dimmingOpacity*100)+')');
	    
	    dimming_div.click(function(e){ cw.close(); });

	    //Show dimming
	    jQuery('body').append(dimming_div);

	    //Make window
	    wnd_div = jQuery('<div></div>');
	    wnd_div.addClass('customwnd');
	    wnd_div.css('position', 'absolute');
	    wnd_div.css('display', 'none');
	    wnd_div.css('z-index', zIndex);
	    wnd_div.css('padding', padding);
	    
	    var cwbvis = 'hidden';
		if(animationType=='fade') cwbvis = 'visible';
		
		if( h != 'auto' )
		{
			h = h + 'px';
		}
		
		var wndhtml = '<div class="customwnd_content">';
		
		if(titleHtml.length>0) wndhtml += '<div class="customwnd_title">'+titleHtml+'</div>';
		wndhtml += '<div class="customwnd_body" style="width: '+w+'px; height: '+h+'; visibility: '+cwbvis+';">'+html+'</div>';
		if(footerHtml.length>0) wndhtml += '<div class="customwnd_footer">'+footerHtml+'</div>';		
		if(showCloseButton) wndhtml+= '<span onClick="cw.close(); return false;" class="customwnd_close" style="display: none;"></span>';
		
		wndhtml += '</div>'

        wnd_div.html(wndhtml);

        jQuery('body').append(wnd_div);

        wnd_div.css('left', '-100000px');
		wnd_div.css('top', '-100000px');

        wnd_div.css('display', 'block');

    	// Calc window position
        if(!fixed || (jQuery.browser.msie && jQuery.browser.version == 6))
		{
			var l = ( jQuery(window).width() - wnd_div.width() ) / 2;
			var t = jQuery(window).scrollTop() + ( jQuery(window).height() - wnd_div.height() ) / 2;
		}
		else
		{
		    var l = ( jQuery(window).width() - wnd_div.width() ) / 2;
			var t = ( jQuery(window).height() - wnd_div.height() ) / 2;

			wnd_div.css('position', 'fixed');
		}

        //Show window        
        beforeOpen();
		is_opened = true;        		
		if(animationType=='slide')
		{
		    wnd_div.css('display', 'none');
		    wnd_div.css('left', l+'px');
			wnd_div.css('top', t+'px');

			wnd_div.slideDown('slow', function(){ 
				wnd_div.find('.customwnd_body').css('visibility', 'visible'); 
				wnd_div.find('.customwnd_close').css('display', 'block'); 
				afterOpen(); 
			});
		}
		else if(animationType=='fade')
		{
		    wnd_div.css('display', 'none');
		    wnd_div.css('left', l+'px');
			wnd_div.css('top', t+'px');

			wnd_div.fadeIn('slow', function(){ 
				wnd_div.find('.customwnd_close').css('display', 'block'); 
				afterOpen(); 
			});
		}
		else if(animationType=='show')
		{
		    wnd_div.css('display', 'none');
		    wnd_div.css('left', l+'px');
			wnd_div.css('top', t+'px');

			wnd_div.show('slow', function(){ 
				wnd_div.find('.customwnd_close').css('display', 'block'); 
				wnd_div.find('.customwnd_body').css('visibility', 'visible');
				afterOpen();
			 });
		}
		else
		{
			wnd_div.find('.customwnd_body').css('visibility', 'visible');
			wnd_div.css('left', l+'px');
			wnd_div.css('top', t+'px');
			afterOpen();
		}
	}	
	
	this.openIframe = function(src, w, h, options)
	{   
		this.open('<iframe src="'+src+'" width="'+w+'" height="'+h+'" frameborder="no" /></iframe>', w, h, options);	    
	}
		
	this.openImage = function(imgsrc, options)
	{                              
	    var pdiv = jQuery('<div></div>');
	    pdiv.css('position', 'absolute');
	    pdiv.css('z-index', this.zIndex);
	    pdiv.addClass('customwnd-preloader');
	    pdiv.text('Loading...')

	    //Show preloader
	    jQuery('body').append(pdiv);

	    // Calc preloader position
        if(jQuery.browser.msie && jQuery.browser.version == 6)
		{
			var l = ( jQuery(window).width() - pdiv.width() ) / 2;
			var t = jQuery(window).scrollTop() + ( jQuery(window).height() - pdiv.height() ) / 2;
		}
		else
		{
		    var l = ( jQuery(window).width() - pdiv.width() ) / 2;
			var t = ( jQuery(window).height() - pdiv.height() ) / 2;

			pdiv.css('position', 'fixed');
		}
		pdiv.css('left', l+'px');
		pdiv.css('top', t+'px');
	    
	    // Load image and calc it sizes
  		var img = new Image(); 
  		var wnd = this;
		img.onload = function(){
		    var w = img.width;
		    var h = img.height;
		                     
			if(w>jQuery(window).width()-100)
			{
			    var ratio = h/w;
			    w = jQuery(window).width()-100;
			    h = w*ratio;
			}
			
			if(h>jQuery(window).height()-100)
			{
			    var ratio = w/h;
			    h = jQuery(window).height()-100;
			    w = h*ratio;
			}   
            document.getElementsByTagName('BODY')[0].removeChild(pdiv.get(0)); 

		    wnd.open('<div style="width: '+w+'px; height: '+h+'px; overflow: hidden;"><img src="'+this.src+'" width="'+w+'" height="'+h+'" alt="" onClick="cw.close(); return false;" /></div>', w, h, options);
		}
		img.src = imgsrc;
	}

 	this.close = function( fast )
	{
		fast = fast || false;
		beforeClose();
		
		if( fast )
		{			
			dimming_div.css('display', 'none');
			wnd_div.remove();
	    	dimming_div.remove();
	    	afterClose();		        	
		}
		else
		{
			if(animationType=='slide')
			{
				wnd_div.find('.customwnd_close').css('display', 'none'); 
				wnd_div.find('.customwnd_body').css('visibility', 'hidden');
			    wnd_div.slideUp('slow', function(){		    	
			        dimming_div.css('display', 'none');		        
					wnd_div.remove();
	    			dimming_div.remove();
	    			afterClose();
				});
			}
			else if(animationType=='fade')
			{	
				wnd_div.find('.customwnd_close').css('display', 'none'); 		
			    wnd_div.fadeOut('slow', function(){
		    	    dimming_div.css('display', 'none');
					wnd_div.remove();
		    		dimming_div.remove();
		    		afterClose();
				});
			}
			else if(animationType=='show')
			{
				wnd_div.find('.customwnd_close').css('display', 'none'); 
				wnd_div.find('.customwnd_body').css('visibility', 'hidden');
			    wnd_div.hide('slow', function(){
			        dimming_div.css('display', 'none');
					wnd_div.remove();
	    			dimming_div.remove();
	    			afterClose();
				});
			}
			else
			{
	    		wnd_div.css('display', 'none');
		    	dimming_div.css('display', 'none');
				wnd_div.remove();
	    		dimming_div.remove();
	    		afterClose();
			}
		}
		
		is_opened = false;
	}
	
	this.setTitle = function(newTitleHtml)
	{
		var wnd_title = wnd_div.find('.customwnd_title');
		if(wnd_title.length==0) return;
		
		wnd_title.html(newTitleHtml);
	}
	
	this.setFooter = function(newFooterHtml)
	{
		var wnd_footer = wnd_div.find('.customwnd_footer');
		if(wnd_footer.length==0) return;
		
		wnd_footer.html(newFooterHtml);
	}
	
	this.setHtml = function(newHtml)
	{		
		wnd_div.find('.customwnd_body').html(newHtml);
	}
	
	this.center = function(duration)	
	{
		duration = duration || 500;
	
		// Calc window position
        if(!this.fixed || (jQuery.browser.msie && jQuery.browser.version == 6))
		{
			var t = jQuery(window).scrollTop() + ( jQuery(window).height() - wnd_div.height() ) / 2;
		}
		else
		{
			var t = ( jQuery(window).height() - wnd_div.height() ) / 2;
		} 
		t = Math.ceil(t);
		
		wnd_div.animate({ top: t+'px' }, duration);
	}
	
	this.setPosition = function( xCoo, yCoo )
	{
		wnd_div.css('left', xCoo+'px');
		wnd_div.css('top', yCoo+'px');
	}
	
	this.isOpened = function() { return is_opened; }
}

var cw = new CustomWnd();
