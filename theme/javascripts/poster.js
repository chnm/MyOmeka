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
       
       // Make the items-widget div a modal pop-up
       /*
        TODO this needs to not be a hard coded path
       */
       iBox.setPath('/omeka/plugins/MyOmeka/theme/javascripts/ibox/');

       /**
        Code to run before we move an item
       */
       $$('.poster-control').invoke('observe', 'click', function(e){
           $$("#poster-canvas textarea").each(function(n){
               tinyMCE.execCommand('mceRemoveControl', false, n.id);
           });
       });
       
       /**
        Bind move up buttons
       */
       $$('.move-up').invoke('observe', 'click', function(e){
           $('poster-canvas').insertBefore(this.up('.poster-spot'), this.up('.poster-spot').previous());
       });
       
       /**
        Bind move down buttons
       */
       $$('.move-down').invoke('observe', 'click', function(e){
           $('poster-canvas').insertBefore(this.up('.poster-spot').next(), this.up('.poster-spot'));
       });
       
       /**
        Bind move top buttons
       */
       $$('.move-top').invoke('observe', 'click', function(e){
           $('poster-canvas').insertBefore(this.up('.poster-spot'), this.up('.poster-spot').siblings().first());
       });
       
       /**
        Bind move bottom buttons
       */
       $$('.move-bottom').invoke('observe', 'click', function(e){
           $('poster-canvas').appendChild(this.up('.poster-spot'));
       });

       /**
        Bind delete buttons
       */
       $$('.delete').invoke('observe', 'click', function(e){
           this.up('.poster-spot').remove();
       });
       
       /**
        Code to run after we move an item
       */
       $$('.poster-control').invoke('observe', 'click', function(e){
           Poster.hideExtraControls();
           $$("#poster-canvas textarea").each(function(n){
               tinyMCE.execCommand('mceAddControl', false, n.id);
           });
           Event.stop(e);
       });
       
       /**
        Code to run when the form is submitted
       */
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
       
       // When the form loads, hide up and down controls that can't be used
       Poster.hideExtraControls();
       
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
    Hides the move up and down options on the top and bottom items
    */
    hideExtraControls: function() {
        $$('.poster-control').invoke("show");
        $$('.move-up').first().hide();
        $$('.move-top').first().hide();
        $$('.move-down').last().hide();
        $$('.move-bottom').last().hide();
    },
});

Event.observe(window, 'load', Poster.init );

//Separates all the widget's JS into its own window event loader
Event.observe(window, 'load', function(){
    
    ItemWidget.onLoad();
    
    ItemWidget.onChooseItem = function(id) {
        //@testing Grab the 'i' value from the poster-spot loop
        //This may not be necessary for the working version, as poster will be saved incrementally
        //And the number of current spots used will be available from the database
        function getHighest() {
            var high = 0;
            var current = 0;
            var rows = $$('.poster-spot');
            rows.each(function(el){
                current = Poster.getRowOrder(el);
                if(current > high) high = current;
            });
            
            return high;
        }
        
//        var i = parseInt($$('.poster-spot').size()) + 1;
        var i = getHighest() + 1;
        
        //This URL is defined on the form.php page that calls this JS
       new Ajax.Updater('poster-canvas', Poster.placeholderUrl, {
           parameters: 'item_id=' + id + '&i=' + i,
           insertion: Insertion.Bottom,
           onSuccess: function(t) {
               new Effect.Appear('poster-canvas');
           },
           onComplete: function(t) {
               //Adds a WYSIWYG editor to each new entry
               var textareaId = 'poster-annotation-' + i;
               tinyMCE.execCommand('mceAddControl', false, textareaId);
               Poster.makeSortable();
               
               //Make the delete buttons clickable
               var entry = $('poster-spot-' + i);
               Poster.makeDeletable(entry);
           }
       });        
    }
    ItemWidget.loadItemInfo();
});

ItemWidget = new Object();

Object.extend(ItemWidget, {
    //Everything that you want to happen when the widget loads
    onLoad: function() {
 //       $$('#choose-item .item').invoke('observe', 'mouseover', function(){alert(this);});
    },
    //Retrieve the Item ID# from the widget and 
    getItemId: function() {
        //Gotta post the widget form contents to the server, get back the proper response
        //var itemId = parseInt($RF('poster-form', 'item_id'));
        return parseInt($$('#item-info .item-id').first().innerHTML);        
    },
    //Run an AJAX updater to load the item's info for the widget
    //Then make sure the "Add Item" button is AJAX-ready
    loadItemInfo: function() {
        //Make the images on the widget clickable for more info
        //All images are clickable, so should follow the link and load AJAX into #item-form div
        var that = this;
        $$('#choose-item .item a').invoke('observe', 'click', function(e){
            //Do not follow the link
            Event.stop(e);

            //@testing THIS NEEDS ERROR HANDLING CODE DO NOT THINK ITS DONE
            new Ajax.Updater('item-view', this.href, {
                onComplete: function(t) { that.chooseItem(); }
            });
        });
        
        that.loadPagination();  
        
        //The default should also be choosable
        that.chooseItem();    
    },
    
    loadPagination: function() {
        var that = this;
        $$('#pagination a').invoke('observe', 'click', function(e){
            Event.stop(e);
            
            //@testing NEEDS ERROR HANDLING CODE
            new Ajax.Updater('item-widget', this.href, {
                onComplete: function(t) {that.loadItemInfo();}
            });
        });
    },
    
    //Run the arbitrary AJAX request when the add-item button is clicked
    chooseItem: function() {
        $('item-widget-add-item').observe('click', function(e){
            //Do not submit the form
            Event.stop(e);
            var itemId = this.getItemId();
            this.onChooseItem(itemId);
        }.bind(this));        
    },
    
    //This is the AJAX event that fires when you choose an item
    //Set this to an AJAX call in order to make it run when the button is clicked on the widget
    onChooseItem: function(itemId) {alert(itemId)},
});

// What is this?
//$RF = function(form, name) {return Form.getInputs(form,'radio',name).find(function(radio) { return radio.checked; }).value; };
