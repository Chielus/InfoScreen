window.log = function(){
  log.history = log.history || [];  
  log.history.push(arguments);
  arguments.callee = arguments.callee.caller;  
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});


(function($) {
	/**
	 * Combines $.replaceWith and $.jqote while returning the new element
	 */
	$.fn.templateReplace = function templateReplace(templateName, data) {		
		var prev = this.prev()[0];
		//console.log(this.parent());
		var parent = this.parent();
		//console.log(this);
		this.replaceWith($('#'+templateName+'-template').jqote(data));
		//console.log(this);
		if (prev) {
			return $(prev).next();
		} else {
			//console.log(parent.children().first());
			return parent.children().first();
		}
	};

	/**
	 * Combines $.before and $.jqote while returning the new element
	 */
	$.fn.templateBefore = function templateBefore(templateName, data) {
		this.before($('#'+templateName+'-template').jqote(data));
		return this.prev();
	};

	/**
	 * Combines $.append and $.jqote while returning the new element
	 */
	$.fn.templateAppend = function templateAppend(templateName, data) {
		this.append($('#'+templateName+'-template').jqote(data));
		return this.children().last();
	};
})(jQuery);
