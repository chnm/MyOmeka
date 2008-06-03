Poster = new Object();
Object.extend(Poster, {
    
    itemCount: 0,
    
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
       // Walks the items and indexes them
       $$('form').invoke('observe', 'submit', function(e){
           // index the form element names
           index = 1;
           $$(".myomeka-annotation textarea").each(function(n){
               n.setAttribute("name","annotation-"+index);
               n.up(".myomeka-poster-spot").down(".myomeka-hidden-item-id").setAttribute("name","id-"+index);
               index++;
           });
           // Update the item count
           $("myomeka-itemCount").setAttribute("value", index-1);
       });
       
       // Add Items to poster with an AJAX call
       $$(".myomeka-additem-form").invoke("observe", "submit", function(e){
           new Ajax.Updater('myomeka-poster-canvas', this.action, {
               parameters: { "item-id": this.down(".myomeka-additem-item-id").readAttribute("value") },
               insertion: Insertion.Bottom,
               onCreate: function(){
                   // Disable submit buttons
                   $$(".myomeka-additem-submit").each(function(n){n.disable();});
                   Poster.mceExecCommand("mceRemoveControl");
               },
               onComplete: function(){
                  Poster.setOrderNums();
                  Poster.hideExtraControls();
                  Poster.mceExecCommand("mceAddControl");
                  Poster.bindControls();
                  // Enable submit buttons for next time
                  $$(".myomeka-additem-submit").each(function(n){n.enable();});
                  iBox.hide();
               }
           });
           Event.stop(e);
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
    Finds all of the poster items and sequentially indexes their textarea name.
    */
    setOrderNums: function() {
        index = 1;
        $$("#myomeka-poster-canvas textarea").each(function(n){
            n.setAttribute("name","annotation-"+index);
            n.setAttribute("id","myomeka-annotation-"+index);
            index++;
        });
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
    },
    
    /**
    Bind functions to items controls
    */
    bindControls: function(){
        // Code to run before we add, move or delete an item
        $$('.myomeka-poster-control').invoke('observe', 'click', function(e){
            Poster.mceExecCommand("mceRemoveControl");
        });

        // Bind move up buttons
        $$('.myomeka-move-up').invoke('observe', 'click', function(e){
            $('myomeka-poster-canvas').insertBefore(this.up('.myomeka-poster-spot'), this.up('.myomeka-poster-spot').previous());
        });

        // Bind move down buttons
        $$('.myomeka-move-down').invoke('observe', 'click', function(e){
            $('myomeka-poster-canvas').insertBefore(this.up('.myomeka-poster-spot').next(), this.up('.myomeka-poster-spot'));
        });

        // Bind move top buttons
        $$('.myomeka-move-top').invoke('observe', 'click', function(e){
            $('myomeka-poster-canvas').insertBefore(this.up('.myomeka-poster-spot'), this.up('.myomeka-poster-spot').siblings().first());
        });

        // Bind move bottom buttons
        $$('.myomeka-move-bottom').invoke('observe', 'click', function(e){
            $('myomeka-poster-canvas').appendChild(this.up('.myomeka-poster-spot'));
        });

        // Bind delete buttons
        $$('.myomeka-delete').invoke('observe', 'click', function(e){
            this.up('.myomeka-poster-spot').remove();
        });

        // Code to run after we add, move or delete an item
        $$('.myomeka-poster-control').invoke('observe', 'click', function(e){
            Poster.hideExtraControls();
            Poster.mceExecCommand("mceAddControl");
            Event.stop(e);
        });  
    },
});

Event.observe(window, 'load', Poster.init );