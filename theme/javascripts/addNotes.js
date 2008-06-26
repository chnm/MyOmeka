Event.observe(window, 'load', function(){

    tinyMCE.init({
    	mode : "textareas",
    	theme: "advanced",
    	theme_advanced_toolbar_location : "top",
    	theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,bullist,numlist,link,formatselect",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_align : "left"
    });    
    // // Hide the form
    // $$("#myomeka-edit-note").invoke("hide");
    // 
    // // Set up edit link
    // $$("#myomeka-edit-link").invoke("observe", "click", function(e){
    //     $$("#myomeka-display-note").invoke("hide");
    //     $$("#myomeka-edit-note").invoke("show");
    //     Event.stop(e);
    // });
    // 
    // // Set up cancel link
    // $$("#myomeka-cancel-edit").invoke("observe", "click", function(e){
    //     $$("#myomeka-display-note").invoke("show");
    //     $$("#myomeka-edit-note").invoke("hide");
    //     Event.stop(e);
    // });
});