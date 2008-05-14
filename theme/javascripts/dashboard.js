Event.observe(window, 'load', function(){
    $$(".delete-poster-link").invoke("observe", "click", function(e){
        if(!confirm("Are you sure you want to delete this poster?")){
            Event.stop(e);
        }
    });
});