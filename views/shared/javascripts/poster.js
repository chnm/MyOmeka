if (!Omeka) {
    var Omeka = {}
}

Omeka.Poster = {
    itemCount: 0,

    //Everything that takes place when the form loads
    init: function () {
        // WYSIWYG Editor
        tinyMCE.init({
            mode: "textareas",
            theme: "advanced",
            theme_advanced_toolbar_location : "top",
            theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,bullist,numlist,link,formatselect",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_toolbar_align : "left"
        });

        // Code to run when the save poster form is submitted
        // Walks the items and indexes them
        jQuery('#myomeka-poster-form').submit(function () {
            var index = 1;
            jQuery('.myomeka-item-annotation').each(function () {
                jQuery(this).find('textarea')
                    .attr('name', 'annotation-' + index)
                    .end()
                    .siblings('.myomeka-hidden-item-id')
                    .attr('name', 'itemID-' + index);
                index++;
            });
            jQuery('#myomeka-itemCount').val(index - 1);
        });

        jQuery('#myomeka-poster-additem button').click(function () {
            var modalDiv = jQuery('#myomeka-additem-modal');
            modalDiv.dialog({modal: true});
            jQuery('.myomeka-additem-form').submit(function (event) {
                var form = jQuery(this), submitButtons = jQuery('.myomeka-additem-submit');
                event.preventDefault();
                submitButtons.attr('disabled', 'disabled');
                Omeka.Poster.mceExecCommand('mceRemoveControl');
                jQuery.get(form.attr('action'), form.serialize(), function (data) {
                    jQuery('#myomeka-poster-canvas').append(data);
                    Omeka.Poster.hideExtraControls();
                    Omeka.Poster.mceExecCommand('mceAddControl');
                    Omeka.Poster.bindControls();
                    modalDiv.dialog('close');
                    submitButtons.removeAttr('disabled');
                });
                jQuery('#myomeka-poster-no-items-yet').hide();
            });
        });

        if (Omeka.Poster.itemCount > 0) {
            // When the form loads, hide up and down controls that can't be used
            // Should maybe grey them out instead
            Omeka.Poster.hideExtraControls();

            // Bind some actions to poster item controls
            Omeka.Poster.bindControls();
        }
    },

    /**
     * Wraps tinyMCE.execCommand
     */
    mceExecCommand: function (command) {
        jQuery('#myomeka-poster-canvas textarea').each(function () {
            tinyMCE.execCommand(command, false, this.id);
        });
    },

    /**
     * Hides the move up and down options on the top and bottom items
     */
    hideExtraControls: function() {
        jQuery('.myomeka-poster-control').show();

        jQuery('.myomeka-move-up').first().hide();
        jQuery('.myomeka-move-top').first().hide();
        jQuery('.myomeka-move-down').last().hide();
        jQuery('.myomeka-move-bottom').last().hide();
    },

    /**
     * Bind functions to items controls
     */
    bindControls: function(){
        //Remove all previous bindings for controls
        jQuery('.myomeka-poster-control').unbind();

        // Bind move up buttons
        jQuery('.myomeka-move-up').click(function (event) {
            var element = jQuery(this).parents('.myomeka-poster-spot');
            event.preventDefault();
            Omeka.Poster.mceExecCommand('mceRemoveControl');
            element.insertBefore(element.prev());
            Omeka.Poster.hideExtraControls();
            Omeka.Poster.mceExecCommand('mceAddControl');
        });

        // Bind move down buttons
        jQuery('.myomeka-move-down').click(function (event) {
            var element = jQuery(this).parents('.myomeka-poster-spot');
            event.preventDefault();
            Omeka.Poster.mceExecCommand('mceRemoveControl');
            element.insertAfter(element.next());
            Omeka.Poster.hideExtraControls();
            Omeka.Poster.mceExecCommand('mceAddControl');
        });

        // Bind move top buttons
        jQuery('.myomeka-move-top').click(function (event) {
            var element = jQuery(this).parents('.myomeka-poster-spot');
            event.preventDefault();
            Omeka.Poster.mceExecCommand('mceRemoveControl');
            element.prependTo('#myomeka-poster-canvas');
            Omeka.Poster.hideExtraControls();
            Omeka.Poster.mceExecCommand('mceAddControl');
        });

        // Bind move bottom buttons
        jQuery('.myomeka-move-bottom').click(function (event) {
            var element = jQuery(this).parents('.myomeka-poster-spot');
            event.preventDefault();
            Omeka.Poster.mceExecCommand('mceRemoveControl');
            element.appendTo('#myomeka-poster-canvas');
            Omeka.Poster.hideExtraControls();
            Omeka.Poster.mceExecCommand('mceAddControl');
        });

        // Bind delete buttons
        jQuery('.myomeka-delete').click(function (event) {
            var element = jQuery(this).parents('.myomeka-poster-spot');
            event.preventDefault();
            Omeka.Poster.mceExecCommand('mceRemoveControl');
            element.remove();
            Omeka.Poster.hideExtraControls();
            Omeka.Poster.mceExecCommand('mceAddControl');
        });
    }
}
