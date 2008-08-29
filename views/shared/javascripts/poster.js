Poster = new Object();
Object.extend(Poster, {
    
	itemCount: 0,
    
    //Everything that takes place when the form loads
   init: function() {
        // WYSIWYG Editor
		tinyMCE.init({
		mode : "textareas",
		theme: "advanced",
		theme_advanced_toolbar_location : "top",
		theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,bullist,numlist,link,formatselect",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_align : "left"
		});   
       
       // Code to run when the save poster form is submitted
       // Walks the items and indexes them
       $$('#myomeka-poster-form').invoke('observe', 'submit', function(e){
           // index the form element names
           index = 1;
           $$(".myomeka-annotation textarea").each(function(n){
               n.setAttribute("name","annotation-"+index);
               n.up(".myomeka-poster-spot").down(".myomeka-hidden-item-id").setAttribute("name","itemID-"+index);
               index++;
           });
           // Update the item count
           $("myomeka-itemCount").setAttribute("value", index-1);
       });
       
       $$("#myomeka-poster-additem button").invoke("observe", "click", function(e){
           // Show the modal box with tagged and noted items
           iBox.show(document.getElementById("myomeka-additem-modal").innerHTML,"Choose an item to add",{});
       
           // Bind an action that adds items to poster with an AJAX call
           $$(".myomeka-additem-form").invoke("observe", "submit", function(e){
               var element = Event.element(e);
               Event.stop(e);
               new Ajax.Updater('myomeka-poster-canvas', element.action, {
                   parameters: { "item-id": element.down(".myomeka-additem-item-id").readAttribute("value") },
                   insertion: Insertion.Bottom,
                   onCreate: function(){
                       // Disable submit buttons
                       $$(".myomeka-additem-submit").each(function(n){n.disable();});
                       Poster.mceExecCommand("mceRemoveControl");
                   },
                   onComplete: function(){
                      Poster.hideExtraControls();
                      Poster.mceExecCommand("mceAddControl");
                      Poster.bindControls();
                      iBox.hide();
                      // Enable submit buttons for next time
                      $$(".myomeka-additem-submit").each(function(n){n.enable();});
                   }
               });
           });
       });
       
       if(Poster.itemCount > 0){
            // When the form loads, hide up and down controls that can't be used
            // Should maybe grey them out instead
            Poster.hideExtraControls();
       
            // Bind some actions to poster item controls
           Poster.bindControls();           
       }
   },
  
    /**
    Wraps tinyMCE.execCommand
    */  
    mceExecCommand: function(command){
        $$("#myomeka-poster-canvas textarea").each(function(n){
            tinyMCE.execCommand(command, false, n.id);
        });  
    },
    
    /**
    Hides the move up and down options on the top and bottom items
    */
    hideExtraControls: function() {
        $$('.myomeka-poster-control').invoke("show");
       	$$('.myomeka-move-up').first().hide();
       	$$('.myomeka-move-top').first().hide();
       	$$('.myomeka-move-down').last().hide();
       	$$('.myomeka-move-bottom').last().hide();
		return;
    },
    
    /**
    Bind functions to items controls
    */
    bindControls: function(){
        // Bind move up buttons
        $$('.myomeka-move-up').invoke('observe', 'click', function(e){
            var element = Event.element(e);
            Poster.mceExecCommand("mceRemoveControl");
            $('myomeka-poster-canvas').insertBefore(element.up('.myomeka-poster-spot'), element.up('.myomeka-poster-spot').previous());
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });
    
        // Bind move down buttons
        $$('.myomeka-move-down').invoke('observe', 'click', function(e){
            var element = Event.element(e);
            Poster.mceExecCommand("mceRemoveControl");
            $('myomeka-poster-canvas').insertBefore(element.up('.myomeka-poster-spot').next(), element.up('.myomeka-poster-spot'));
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });
    
        // Bind move top buttons
        $$('.myomeka-move-top').invoke('observe', 'click', function(e){
            var element = Event.element(e);
            Poster.mceExecCommand("mceRemoveControl");
            $('myomeka-poster-canvas').insertBefore(element.up('.myomeka-poster-spot'), element.up('.myomeka-poster-spot').siblings().first());
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });
    
        // Bind move bottom buttons
        $$('.myomeka-move-bottom').invoke('observe', 'click', function(e){
            var element = Event.element(e);
            Poster.mceExecCommand("mceRemoveControl");
            $('myomeka-poster-canvas').appendChild(element.up('.myomeka-poster-spot'));
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });
    
        // Bind delete buttons
        $$('.myomeka-delete').invoke('observe', 'click', function(e){
            var element = Event.element(e);
            Poster.mceExecCommand("mceRemoveControl");
            element.up('.myomeka-poster-spot').remove();
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });
    }
});
Event.observe(window, 'load', Poster.init );


dump = function(object, showTypes) {
  var dump = '';
  var st = typeof showTypes == 'undefined' ? true : showTypes;
  var winName = 'dumpWin';
  var browser = _dumpIdentifyBrowser();	
  var w = 760;
  var h = 500;
  var leftPos = screen.width ? (screen.width - w) / 2 : 0;
  var topPos = screen.height ? (screen.height - h) / 2 : 0;
  var settings = 'height=' + h + ',width=' + w + ',top=' + topPos + ',left=' + leftPos + ',scrollbars=yes,menubar=yes,status=yes,resizable=yes';
  var title = 'Dump';
  var script = 'function tRow(s) {t = s.parentNode.lastChild;tTarget(t, tSource(s)) ;}function tTable(s) {var switchToState = tSource(s) ;var table = s.parentNode.parentNode;for (var i = 1; i < table.childNodes.length; i++) {t = table.childNodes[i] ;if (t.style) {tTarget(t, switchToState);}}}function tSource(s) {if (s.style.fontStyle == "italic" || s.style.fontStyle == null) {s.style.fontStyle = "normal";s.title = "click to collapse";return "open";} else {s.style.fontStyle = "italic";s.title = "click to expand";return "closed" ;}}function tTarget (t, switchToState) {if (switchToState == "open") {t.style.display = "";} else {t.style.display = "none";}}';		
dump += (/string|number|undefined|boolean/.test(typeof(object)) || object == null) ? object : recurse(object, typeof object);
  winName = window.open('', winName, settings);
  if (browser.indexOf('ie') != -1 || browser == 'opera' || browser == 'ie5mac' || browser == 'safari') {
	winName.document.write('<html><head><title> ' + title + ' </title><script type="text/javascript">' + script + '</script><head>');
	winName.document.write('<body>' + dump + '</body></html>');
  } else {
	winName.document.body.innerHTML = dump;
	winName.document.title = title;
	var ffs = winName.document.createElement('script');
	ffs.setAttribute('type', 'text/javascript');
	ffs.appendChild(document.createTextNode(script));
	winName.document.getElementsByTagName('head')[0].appendChild(ffs);
  }
  winName.focus();  
  function recurse(o, type) {
    var i;
	var j = 0;
	var r = '';
	type = _dumpType(o);
	switch (type) {		
	  case 'regexp':
	    var t = type;
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>RegExp: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'date':
	    var t = type;
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>Date: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'function':
	    var t = type;
	    var a = o.toString().match(/^.*function.*?\((.*?)\)/im); 
	    var args = (a == null || typeof a[1] == 'undefined' || a[1] == '') ? 'none' : a[1];
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>Arguments: </i></td><td' + _dumpStyles(type,'td-value') + '>' + args + '</td></tr><tr><td' + _dumpStyles('arguments','td-key') + '><i>Function: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'domelement':
	    var t = type;
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Name: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeName.toLowerCase() + '</td></tr>';  
		r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Type: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeType + '</td></tr>'; 
		r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Value: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeValue + '</td></tr>'; 					
		r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>innerHTML: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.innerHTML + '</td></tr>';  
	    j++;
	    break;		
	}
	if (/object|array/.test(type)) {
      for (i in o) {
	    var t = _dumpType(o[i]);
	    if (j < 1) {
	      r += '<table' + _dumpStyles(type,'table') + '><tr><th colspan="2"' + _dumpStyles(type,'th') + '>' + type + '</th></tr>';
		  j++;	  
	    }
	    if (typeof o[i] == 'object' && o[i] != null) { 
		  r += '<tr><td' + _dumpStyles(type,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + recurse(o[i], t) + '</td></tr>';	
	    } else if (typeof o[i] == 'function') {
		  r += '<tr><td' + _dumpStyles(type ,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + recurse(o[i], t) + '</td></tr>';  	
		} else {
		  r += '<tr><td' + _dumpStyles(type,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + o[i] + '</td></tr>';  
	    }
	  }
	}
	if (j == 0) {
	  r += '<table' + _dumpStyles(type,'table') + '><tr><th colspan="2"' + _dumpStyles(type,'th') + '>' + type + ' [empty]</th></tr>'; 	
	}
	r += '</table>';
	return r;
  };	
};
_dumpStyles = function(type, use) {
  var r = '';
  var table = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;cell-spacing:2px;';
  var th = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;text-align:left;color: white;padding: 5px;vertical-align :top;cursor:hand;cursor:pointer;';
  var td = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;vertical-align:top;padding:3px;';
  var thScript = 'onClick="tTable(this);" title="click to collapse"';
  var tdScript = 'onClick="tRow(this);" title="click to collapse"';
  switch (type) {
	case 'string':
	case 'number':
	case 'boolean':
	case 'undefined':
	case 'object':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#0000cc;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#4444cc;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#ccddff;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }
	  break;
	case 'array':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#006600;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#009900;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#ccffcc;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }	
	  break;
	case 'function':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#aa4400;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#cc6600;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#fff;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }	
	  break;
	case 'arguments':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#dddddd;cell-spacing:3;"';
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#eeeeee;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;	  
	  }	
	  break;
	case 'regexp':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#CC0000;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#FF0000;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#FF5757;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;
	case 'date':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#663399;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#9966CC;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#B266FF;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;
	case 'domelement':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#FFCC33;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#FFD966;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#FFF2CC;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;	  
  }
  return r;
};
_dumpIdentifyBrowser = function() {
  var agent = navigator.userAgent.toLowerCase();
  if (typeof window.opera != 'undefined') {
    return 'opera';
  } else if (typeof document.all != 'undefined') {
    if (typeof document.getElementById != 'undefined') {
      var browser = agent.replace(/.*ms(ie[\/ ][^ $]+).*/, '$1').replace(/ /, '');
      if (typeof document.uniqueID != 'undefined') {
        if (browser.indexOf('5.5') != -1) {
          return browser.replace(/(.*5\.5).*/, '$1');
        } else {
          return browser.replace(/(.*)\..*/, '$1');
        }
      } else {
        return 'ie5mac';
      }
    }
  } else if (typeof document.getElementById != 'undefined') {
    if (navigator.vendor.indexOf('Apple Computer, Inc.') != -1) {
      return 'safari';
    } else if (agent.indexOf('gecko') != -1) {
      return 'mozilla';
    }
  }
  return false;
};
_dumpType = function (obj) {
  var t = typeof(obj);
  if (t == 'function') {
    var f = obj.toString();
    if ( ( /^\/.*\/[gi]??[gi]??$/ ).test(f)) {
      return 'regexp';
    } else if ((/^\[object.*\]$/i ).test(f)) {
      t = 'object'
    }
  }
  if (t != 'object') {
    return t;
  }
  switch (obj) {
    case null:
      return 'null';
    case window:
      return 'window';
	case document:
	  return document;
    case window.event:
      return 'event';
  }
  if (window.event && (event.type == obj.type)) {
    return 'event';
  }
  var c = obj.constructor;
  if (c != null) {
    switch(c) {
      case Array:
        t = 'array';
        break;
      case Date:
        return 'date';
      case RegExp:
        return 'regexp';
      case Object:
        t = 'object';	
      break;
      case ReferenceError:
        return 'error';
      default:
        var sc = c.toString();
        var m = sc.match(/\s*function (.*)\(/);
        if(m != null) {
          return 'object';
        }
    }
  }
  var nt = obj.nodeType;
  if (nt != null) {
    switch(nt) {
      case 1:
        if(obj.item == null) {
          return 'domelement';
        }
        break;
      case 3:
        return 'string';
    }
  }
  if (obj.toString != null) {
    var ex = obj.toString();
    var am = ex.match(/^\[object (.*)\]$/i);
    if(am != null) {
      var am = am[1];
      switch(am.toLowerCase()) {
        case 'event':
          return 'event';
        case 'nodelist':
        case 'htmlcollection':
        case 'elementarray':
          return 'array';
        case 'htmldocument':
          return 'htmldocument';
      }
    }
  }
  return t;
};