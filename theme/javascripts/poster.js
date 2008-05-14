Poster = new Object();

Object.extend(Poster, {
        
    //Everything that takes place when the form loads
   init: function() {
       //WYSIWYG Editor
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
       $$('form').invoke('observe', 'submit', function(e){
           // index the form element names
           index = 1;
           $$(".annotation textarea").each(function(n){
               n.setAttribute("name","annotation-"+index);
               n.up(".poster-spot").down(".hidden-item-id").setAttribute("name","id-"+index);
               index++;
           });
           // Update the item count
           $("itemCount").setAttribute("value", index-1);
       });
       
        // Add favorites to poster with an AJAX call
       $$(".favorite-item form").invoke("observe", "submit", function(e){
           new Ajax.Updater('poster-canvas', this.action, {
               parameters: { "item-id": this.down(".favorite-item-id").readAttribute("value") },
               insertion: Insertion.Bottom,
               onCreate: function(){
                   Poster.mceExecCommand("mceRemoveControl");
               },
               onComplete: function(){
                  iBox.hide();
                  Poster.hideExtraControls();
                  Poster.mceExecCommand("mceAddControl");
                  Poster.bindControls();
               }
           });
           Event.stop(e);
       });
       
       // When the form loads, hide up and down controls that can't be used
       Poster.hideExtraControls();
       
       // Bind some actions to poster item controls
      Poster.bindControls();
       
   },
    
    /**
    Finds all of the poster items and sequentially indexes their textarea name.
    */
    setOrderNums: function() {
        index = 1;
        $$(".annotation textarea").each(function(n){
            n.setAttribute("name","annotation-"+index);
            n.setAttribute("id","annotation-"+index);
            index++;
        });
    },
  
    /**
    Wraps tinyMCE.execCommand
    */  
    mceExecCommand: function(command){
        $$("#poster-canvas textarea").each(function(n){
            tinyMCE.execCommand(command, false, n.id);
        });  
    },
    
    
    /**
    Hides the move up and down options on the top and bottom items
    */
    hideExtraControls: function() {
        $$('.poster-control').invoke("show");
        $$('.move-up').first().hide();
        $$('.move-top').first().hide();
        $$('.move-down').last().hide();
        $$('.move-bottom').last().hide();
    },
    
    bindControls: function(){
        // Code to run before we add, move or delete an item
        $$('.poster-control').invoke('observe', 'click', function(e){
            Poster.mceExecCommand("mceRemoveControl");
        });

        // Bind move up buttons
        $$('.move-up').invoke('observe', 'click', function(e){
            $('poster-canvas').insertBefore(this.up('.poster-spot'), this.up('.poster-spot').previous());
        });

        // Bind move down buttons
        $$('.move-down').invoke('observe', 'click', function(e){
            $('poster-canvas').insertBefore(this.up('.poster-spot').next(), this.up('.poster-spot'));
        });

        // Bind move top buttons
        $$('.move-top').invoke('observe', 'click', function(e){
            $('poster-canvas').insertBefore(this.up('.poster-spot'), this.up('.poster-spot').siblings().first());
        });

        // Bind move bottom buttons
        $$('.move-bottom').invoke('observe', 'click', function(e){
            $('poster-canvas').appendChild(this.up('.poster-spot'));
        });

        // Bind delete buttons
        $$('.delete').invoke('observe', 'click', function(e){
            this.up('.poster-spot').remove();
        });

        // Code to run after we add, move or delete an item
        $$('.poster-control').invoke('observe', 'click', function(e){
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });  
    },
});

Event.observe(window, 'load', Poster.init );