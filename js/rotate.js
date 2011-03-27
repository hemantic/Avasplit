/*
 * jQuery JavaScript Plugin jquery.imgRotator.js 0.9.0
 * http://bugsoftware.co.uk/jQuery/imgRotator/
 *
 * Copyright (c) 2010 Ritchie Comley
 * Dual licensed under the MIT and GPL licenses.
 *
 * Date: 2010-02-24 (Wed, 24 February 2010)
 * Revision: 43
 *
 * Dependencies:
 * jQuery 1.4.1 (jquery.com)
 * ExplorerCavas Release 3 (http://code.google.com/p/explorercanvas/)
 * ExplorerCavas Patch (http://www.extjs.com/playpen/tm/excanvas-patch/)
 * ExplorerCavas also contains one modification by BugSoftware labeled RTAC
 * 
 */
 
 /*global jQuery window */

jQuery.fn.imgRotator = function(options) {

	var defaults = {
		startRotation:0,
		dragstart:function(angle){},
		dragfinish:function(angle){},
		drag:function(angle){},
		disableDrag:false
	}; 

	var opts = jQuery.extend(defaults, options);

	function sawFunc(a)
	{
		var PI = Math.PI;
		var PI2 = PI / 2;
		// make sure a is within 0 to PI
		a = a % PI;
		
		if (a < 0)
		{
			a += PI;
		}
		
		if (a < PI2)
		{
			return a / PI2;
		}
		else
		{
			return (PI - a) / PI2;
		}
	}
	
	function ImageRotator(el)
	{
		this.element = el;
		
		el.css({position: 'absolute'});
		
		this.canvas = el.find('canvas').eq(0);
		this.image = el.find("img").eq(0);

		this.iw = this.image.width();
		this.ih = this.image.height();
		
		var elWidth = el.width();
		var elHeight = el.height();
		
		var self = this;
		
		this.rotation = opts.startRotation;
		
		if(!opts.disableDrag)
		{

			var dragging = false;
			var dragStartAngle = false;
			var currRotation = false;
			var currOffset = false;
			
			el.mousedown(function (evt)
				{
					evt.preventDefault();
				
					dragging = true;
					
					currOffset  = el.offset();

					var x = (elWidth / 2) - (evt.pageX - currOffset.left);
					var y = (elHeight / 2) - (evt.pageY - currOffset.top);
					
					dragStartAngle = Math.atan2(x, y);
					currRotation = self.rotation;
					opts.dragstart(self.rotation);
				});
				
			jQuery('body').mouseup(function (evt)
				{
					if(dragging)
					{
						dragging = false;
						opts.dragfinish(self.rotation);
					}
				});

			el.mousemove(function (evt)
				{
					if(dragging)
					{
						evt.preventDefault();
					
						var x = (elWidth / 2) - (evt.pageX - currOffset.left);
						var y = (elHeight / 2) - (evt.pageY - currOffset.top);

						var diff = dragStartAngle - Math.atan2(x, y);

						self.rotation = currRotation + diff;
						
						self.layout();
						
						opts.drag(self.rotation);
					}
				});
			
		}
		
		this.drawn = false;

		this.layout();

		jQuery(window).resize(function() { self.layout(); });
		
		jQuery(window).load(function() {
		
				self.windowLoaded = true;
				
				self.initCanvas();
			});
	}

	ImageRotator.prototype = {

		layout: function ()
		{
			var PI2 = Math.PI / 2;
			var h = this.element.height();
			var w = this.element.width();
			
			if (!this.ctx)
			{
				this.canvas.css({display: "none"});
				this.image.css({display: "block"});
				
				var ratio = Math.min(w / this.image.width(), h / this.image.height(), 1);
				var imgW = this.image.width() * ratio;
				var imgH = this.image.height() * ratio;
				var y = (h - imgH) / 2;
				var x = (w - imgW) / 2;
				
				this.image.css({
					position:'absolute',
					left: Math.round(x),
					top: Math.round(y),
					width: Math.round(imgW),
					height: Math.round(imgH)});
			}
			else
			{				
				this.image.css({display:'none'});

				this.canvas.css({
					top:0,
					left:0,
					width: w,
					height: h,
					display: 'block'});

				// set the canvas to the current width (internaly)
				this.canvas[0].width = w;
				this.canvas[0].height = h;

				this.ctx.save();

				this.ctx.clearRect(0,0,w,h);

				// rotate around the middle
				this.ctx.translate(w / 2, h / 2);
				this.ctx.rotate(this.rotation);

				var scale = 1;

				if (this.iw <= w && this.iw <= h && this.ih <= h && this.ih <= w)
				{
					scale = 1;
				}
				else
				{
					var sinr = sawFunc(this.rotation);
					var cosr = sawFunc(this.rotation + PI2);
					var ratio1 = sinr * Math.min(w / this.ih, h / this.iw);
					var ratio2 = cosr * Math.min(w / this.iw, h / this.ih);

					scale = Math.min(1, ratio1 + ratio2);
				}
				
				// draw image in center
				this.ctx.scale(scale, scale);
				this.ctx.translate(-this.iw / 2, -this.ih / 2);
				this.ctx.drawImage(this.image[0], 0, 0, this.iw, this.ih);
			
				this.ctx.restore();
			}
		},

		rotation: 0,

		initCanvas: function ()
		{
			this.ctx = this.canvas[0].getContext("2d");

			if (!this.ctx)
			{
				return;
			}

			this.layout();
		}
	};
	
	
	return this.each(function()
	{
		var jImgRotator = jQuery(this);
		var rotator = new ImageRotator(jImgRotator);
	});
};