+function(e){"use strict";var t=function(t,n){this.options=n;this.$element=e(t);this.$backdrop=this.isShown=null;if(this.options.remote)this.$element.load(this.options.remote)};t.DEFAULTS={backdrop:true,keyboard:true,show:true};t.prototype.toggle=function(e){return this[!this.isShown?"show":"hide"](e)};t.prototype.show=function(t){var n=this;var r=e.Event("show.bs.modal",{relatedTarget:t});this.$element.trigger(r);if(this.isShown||r.isDefaultPrevented())return;this.isShown=true;this.escape();this.$element.on("click.dismiss.modal",'[data-dismiss="modal"]',e.proxy(this.hide,this));this.backdrop(function(){var r=e.support.transition&&n.$element.hasClass("fade");if(!n.$element.parent().length){n.$element.appendTo(document.body)}n.$element.show();if(r){n.$element[0].offsetWidth}n.$element.addClass("in").attr("aria-hidden",false);n.enforceFocus();var i=e.Event("shown.bs.modal",{relatedTarget:t});r?n.$element.find(".modal-dialog").one(e.support.transition.end,function(){n.$element.focus().trigger(i)}).emulateTransitionEnd(300):n.$element.focus().trigger(i)})};t.prototype.hide=function(t){if(t)t.preventDefault();t=e.Event("hide.bs.modal");this.$element.trigger(t);if(!this.isShown||t.isDefaultPrevented())return;this.isShown=false;this.escape();e(document).off("focusin.bs.modal");this.$element.removeClass("in").attr("aria-hidden",true).off("click.dismiss.modal");e.support.transition&&this.$element.hasClass("fade")?this.$element.one(e.support.transition.end,e.proxy(this.hideModal,this)).emulateTransitionEnd(300):this.hideModal()};t.prototype.enforceFocus=function(){e(document).off("focusin.bs.modal").on("focusin.bs.modal",e.proxy(function(e){if(this.$element[0]!==e.target&&!this.$element.has(e.target).length){this.$element.focus()}},this))};t.prototype.escape=function(){if(this.isShown&&this.options.keyboard){this.$element.on("keyup.dismiss.bs.modal",e.proxy(function(e){e.which==27&&this.hide()},this))}else if(!this.isShown){this.$element.off("keyup.dismiss.bs.modal")}};t.prototype.hideModal=function(){var e=this;this.$element.hide();this.backdrop(function(){e.removeBackdrop();e.$element.trigger("hidden.bs.modal")})};t.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove();this.$backdrop=null};t.prototype.backdrop=function(t){var n=this;var r=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var i=e.support.transition&&r;this.$backdrop=e('<div class="modal-backdrop '+r+'" />').appendTo(document.body);this.$element.on("click.dismiss.modal",e.proxy(function(e){if(e.target!==e.currentTarget)return;this.options.backdrop=="static"?this.$element[0].focus.call(this.$element[0]):this.hide.call(this)},this));if(i)this.$backdrop[0].offsetWidth;this.$backdrop.addClass("in");if(!t)return;i?this.$backdrop.one(e.support.transition.end,t).emulateTransitionEnd(150):t()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");e.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(e.support.transition.end,t).emulateTransitionEnd(150):t()}else if(t){t()}};var n=e.fn.modal;e.fn.modal=function(n,r){return this.each(function(){var i=e(this);var s=i.data("bs.modal");var o=e.extend({},t.DEFAULTS,i.data(),typeof n=="object"&&n);if(!s)i.data("bs.modal",s=new t(this,o));if(typeof n=="string")s[n](r);else if(o.show)s.show(r)})};e.fn.modal.Constructor=t;e.fn.modal.noConflict=function(){e.fn.modal=n;return this};e(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(t){var n=e(this);var r=n.attr("href");var i=e(n.attr("data-target")||r&&r.replace(/.*(?=#[^\s]+$)/,""));var s=i.data("modal")?"toggle":e.extend({remote:!/#/.test(r)&&r},i.data(),n.data());t.preventDefault();i.modal(s,this).one("hide",function(){n.is(":visible")&&n.focus()})});e(document).on("show.bs.modal",".modal",function(){e(document.body).addClass("modal-open")}).on("hidden.bs.modal",".modal",function(){e(document.body).removeClass("modal-open")})}(window.jQuery);!function(e){"use strict";var t=function(e,t){this.init("popover",e,t)};t.prototype=e.extend({},e.fn.tooltip.Constructor.prototype,{constructor:t,setContent:function(){var e=this.tip(),t=this.getTitle(),n=this.getContent();if(!this.options.noTitle){e.find(".popover-title")[this.options.html?"html":"text"](t)}e.find(".popover-content")[this.options.html?"html":"text"](n);e.removeClass("fade top bottom left right in")},hasContent:function(){return this.getTitle()||this.getContent()},getContent:function(){var e,t=this.$element,n=this.options;e=(typeof n.content=="function"?n.content.call(t[0]):n.content)||t.attr("data-content");return e},tip:function(){if(!this.$tip){this.$tip=e(this.options.template)}return this.$tip},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}});var n=e.fn.popover;e.fn.popover=function(n){return this.each(function(){var r=e(this),i=r.data("popover"),s=typeof n=="object"&&n;if(!i)r.data("popover",i=new t(this,s));if(typeof n=="string")i[n]()})};e.fn.popover.Constructor=t;e.fn.popover.defaults=e.extend({},e.fn.tooltip.defaults,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'});e.fn.popover.noConflict=function(){e.fn.popover=n;return this}}(window.jQuery);+function(e){"use strict";var t=function(n,r){this.$element=e(n);this.options=e.extend({},t.DEFAULTS,r);this.transitioning=null;if(this.options.parent)this.$parent=e(this.options.parent);if(this.options.toggle)this.toggle()};t.DEFAULTS={toggle:true};t.prototype.dimension=function(){var e=this.$element.hasClass("width");return e?"width":"height"};t.prototype.show=function(){if(this.transitioning||this.$element.hasClass("in"))return;var t=e.Event("show.bs.collapse");this.$element.trigger(t);if(t.isDefaultPrevented())return;var n=this.$parent&&this.$parent.find("> .panel > .in");if(n&&n.length){var r=n.data("bs.collapse");if(r&&r.transitioning)return;n.collapse("hide");r||n.data("bs.collapse",null)}var i=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[i](0);this.transitioning=1;var s=function(){this.$element.removeClass("collapsing").addClass("in")[i]("auto");this.transitioning=0;this.$element.trigger("shown.bs.collapse")};if(!e.support.transition)return s.call(this);var o=e.camelCase(["scroll",i].join("-"));this.$element.one(e.support.transition.end,e.proxy(s,this)).emulateTransitionEnd(350)[i](this.$element[0][o])};t.prototype.hide=function(){if(this.transitioning||!this.$element.hasClass("in"))return;var t=e.Event("hide.bs.collapse");this.$element.trigger(t);if(t.isDefaultPrevented())return;var n=this.dimension();this.$element[n](this.$element[n]())[0].offsetHeight;this.$element.addClass("collapsing").removeClass("collapse").removeClass("in");this.transitioning=1;var r=function(){this.transitioning=0;this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")};if(!e.support.transition)return r.call(this);this.$element[n](0).one(e.support.transition.end,e.proxy(r,this)).emulateTransitionEnd(350)};t.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()};var n=e.fn.collapse;e.fn.collapse=function(n){return this.each(function(){var r=e(this);var i=r.data("bs.collapse");var s=e.extend({},t.DEFAULTS,r.data(),typeof n=="object"&&n);if(!i)r.data("bs.collapse",i=new t(this,s));if(typeof n=="string")i[n]()})};e.fn.collapse.Constructor=t;e.fn.collapse.noConflict=function(){e.fn.collapse=n;return this};e(document).on("click.bs.collapse.data-api","[data-toggle=collapse]",function(t){var n=e(this),r;var i=n.attr("data-target")||t.preventDefault()||(r=n.attr("href"))&&r.replace(/.*(?=#[^\s]+$)/,"");var s=e(i);var o=s.data("bs.collapse");var u=o?"toggle":n.data();var a=n.attr("data-parent");var f=a&&e(a);if(!o||!o.transitioning){if(f)f.find('[data-toggle=collapse][data-parent="'+a+'"]').not(n).addClass("collapsed");n[s.hasClass("in")?"addClass":"removeClass"]("collapsed")}s.collapse(u)})}(window.jQuery);+function(e){"use strict";function t(){var e=document.createElement("bootstrap");var t={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var n in t){if(e.style[n]!==undefined){return{end:t[n]}}}}e.fn.emulateTransitionEnd=function(t){var n=false,r=this;e(this).one(e.support.transition.end,function(){n=true});var i=function(){if(!n)e(r).trigger(e.support.transition.end)};setTimeout(i,t);return this};e(function(){e.support.transition=t()})}(window.jQuery)