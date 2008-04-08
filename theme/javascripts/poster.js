Event.observe(window, 'load', function(){
   
   Event.observe('poster-form', 'submit', function(){
      return false; 
   });
   
   //Make the poster spots sortables
   Sortable.create('poster-canvas', {
       tag: "div",
       only: "poster-spot",
       constraint: false,
       overlap: 'horizontal',
//       hoverclass: 'drop-on-spot',
       onHover: function(e) {
           alert(e);
       },
       onUpdate: function() {
          alert(Sortable.sequence('poster-canvas')); 
       }
   });
   
   
   //Make the textareas expandable
/*    $$('.add-more-text').invoke('observe', 'click', function(){
       
       var textarea = this.previous('textarea');
       new Effect.Scale(textarea, 200);
       return false;
   }); */
 
});