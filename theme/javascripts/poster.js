Poster = new Object();

Object.extend(Poster, {
        
    //Everything that takes place when the form loads
   formLoad: function() {
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

       Event.observe('poster-form', 'submit', function(){
         return false; 
       });     
       
       Poster.makeSortable();  
       Poster.makeDeletable();
       
       // Make the items-widget div a modal pop-up
       /*
        TODO this needs to not be a hard coded path
       */
       iBox.setPath('/omeka/plugins/MyArchive/theme/javascripts/ibox/');
   },
   saveForm: function() {
       //Save the poster every time we change the order
         $('poster-form').request({
             onComplete: function(t) {
//                  alert(t.responseText);
             }
         });       
   },
   
   getSequence: function() {
     return $$('.poster-spot');
   },
   
   getTextAreaId: function(row) {
     return 'poster-annotation-' + Poster.getRowOrder(row);
   },
   
   //Convenience shortcuts for TinyMCE
   addEditor: function(element) {
       try{
           tinyMCE.execCommand('mceAddControl', false, Poster.getTextAreaId(element));
       }catch (e) {console.debug(e);}
   },
   
   removeEditor: function(element) {
       try{
           tinyMCE.execCommand('mceRemoveControl', false, Poster.getTextAreaId(element));
       }catch (e) {console.debug(e);}
   },
   
   //Activate the drag/drop for the poster
   makeSortable: function() {
      //Make the poster spots sortables
/*       Sortable.create('poster-canvas', {
          tag: "div",
          only: "poster-spot",
//          constraint: false,
//          overlap: 'horizontal',
          scroll: window,
   //       hoverclass: 'drop-on-spot',
          onHover: function(e) {
              alert(e);
          },
          onUpdate: function() {
             alert(Sortable.sequence('poster-canvas')); 
          }
      }); */
      var moveUp = function(list, row) {
          moveRow(list, row, 1);
      };
      var moveDown = function(list, row) {
          moveRow(list, row, -1);
      };
      //Based on: http://www.neotrinity.at/2007/06/26/scriptaculous-move/
      var moveRow = function(list, row, dir) {
          var sequence=Poster.getSequence();          
          for (var j=0; j<sequence.length; j++) {
              var i = j - dir;
              if (sequence[j]==row && i >= 0 && i <= sequence.length) {
                  var temp=sequence[i];
                  sequence[i]=row;
                  sequence[j]=temp;
                  break;
              }
          }
          setSequence(sequence);          
      };
            
      //Set the sequence in place w/o using Scriptaculous
      //Hacked from Scriptaculous's Sortable.setSequence()
      var setSequence = function(newSequence) {
        //Obtain the original elements
        var originals = Poster.getSequence();
        console.debug(originals);    
        var nodeMap = {};
        originals.each(function(n){
            nodeMap[n.id] = [n, n.parentNode];
            Poster.removeEditor(n);
            n.parentNode.removeChild(n);    
        });
        
        debugOrder();
        
        newSequence.each(function(row, index) {
          var n = nodeMap[row.id];
          if (n) {
            n[1].appendChild(n[0]);
            //Put the WYSIWYG back in
            Poster.addEditor(n[0]);
            delete nodeMap[row.id];
          }
        });
        
        debugOrder();
      };
      
      var debugOrder = function() {
          var ids="";
          Poster.getSequence().each(function(row){
              ids += Poster.getRowOrder(row) + ',';
          });
          console.debug(ids);
      }
      
      //Parse the row from the ID of the entry for the clicked element
      var getRow = function(element) {
          var posterSpot = element.up('.poster-spot');
        return posterSpot;
      }
      
      var isLastRow = function(row) {
          return !(row.next('.poster-spot'));
      }
      
      var isFirstRow = function(row) {
          return !(row.previous('.poster-spot'));
      }
      
      $$('.move-up').invoke('observe', 'click', function(e){
          Event.stop(e);
          var row = getRow(this);
          if(!isFirstRow(row)) {
              moveUp('poster-canvas', row);
          }
      });
      
      $$('.move-down').invoke('observe', 'click', function(e){
         Event.stop(e);
         var row = getRow(this);
         if(!isLastRow(row)) {
             moveDown('poster-canvas', row);
         }
      });
      
      $$('.move-top').invoke('observe', 'click', function(e){
          Event.stop(e);
          var row = getRow(this);
          //Remove the current row from the sequence and unshift it to the front of the array
          var newSequence = Poster.getSequence().reject(function(entry){return (row == entry);});
          newSequence.unshift(row);
          setSequence(newSequence);
      });
      
      $$('.move-bottom').invoke('observe', 'click', function(e){
         Event.stop(e);
         var row = getRow(this);
         //Remove the current row from the sequence and push it to the end of the array
         var newSequence = Poster.getSequence().reject(function(entry){return (row == entry);});
         newSequence.push(row);
         setSequence(newSequence);
      });
    },
    
    getRowOrder: function(row) {
        return parseInt(row.id.gsub('poster-spot-', ''));          
    },
    
    reorder: function() {
        Poster.getSequence().each(function(row, index){
            Poster.removeEditor(row);
            var textarea = $(Poster.getTextAreaId(row));
            textarea.id = 'poster-annotation-' + (index + 1);
            row.id = 'poster-spot-' + (index + 1);
            console.debug(row);
            Poster.addEditor(row);
        });
    },
    
    //This will make all buttons with a class="delete" able to delete their entry on the form
    makeDeletable: function(entry) {
        $$('.delete').invoke('observe', 'click', function(e){
            Event.stop(e);
            var entry = this.up('.poster-spot');
            if(entry) {
                Poster.onDeleteEntry(entry);
                Poster.reorder();
            }
        });       
    },
    
    //Remove the entry from the page, and from the sortables list
    //@todo Fire an AJAX call to delete the entry from the database
    onDeleteEntry: function(entry) {
        if(confirm('Are you sure you want to delete this?')) {
            Poster.removeEditor(entry);
            entry.parentNode.removeChild(entry);
        }
    },
    //Some properties that need to be set via PHP on the form
    //The URL for the AJAX request to load a new entry on the poster form
    placeholderUrl: null
});

Event.observe(window, 'load', Poster.formLoad);

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

$RF = function(form, name) {return Form.getInputs(form,'radio',name).find(function(radio) { return radio.checked; }).value; };
