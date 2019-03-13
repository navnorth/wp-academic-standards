jQuery(document).ready(function($) {
    /** Import Other Standards URL **/
    $('#oer_standard_other').on("change", function(){
            var std_url = $("#oer_standard_other_url")
            if ($(this).is(":checked")) {
                    std_url.attr("disabled", false)
                    std_url.focus()
            } else {
                    std_url.attr("disabled", true)
            }
    });
    
    $("#admin-standard-list").on("click", ".std-edit a", function(){
        var std_val = $(this).attr('data-value');
        display_standard_details(std_val);
        $("#editStandardModal").modal("show");
    });
    
    $("#addStandardSet").on("click", function(){
        $("#addStandardModal #add-core-standard").show();
        $("#addStandardModal").modal("show");
    });
    
    $("#admin-standard-list").on("click", ".std-add a", function(){
        var std_val = $(this).attr('data-parent');
        var std;
        if (std_val) {
            stds = std_val.split("-");
            std = stds[0];
        }
        if (std=="core_standards") {
            $("#addStandardModal #standard_parent_id").val(std_val);
            $("#addStandardModal #add-sub-standard").show();
        } else {
            $("#addStandardModal #standard_parent_id").val(std_val);
            $("#addStandardModal #add-standard-notation").show();
        }
        $("#addStandardModal").modal("show");
    });
    
    $("#admin-standard-list").on("click", ".std-del a", function(){
        var std_id = $(this).attr('data-stdid');
        
        if (confirm("Are you sure you want to delete this standard?")==true) {
            delete_standard(std_id);
        }
    });
    
    $("#admin-standard-list").on("click", ".std-up a", function(){
        $(this).moveUp();
    });
    
    $("#admin-standard-list").on("click", ".std-down a", function(){
        $(this).moveDown();
    });
    
    $("#editStandardModal, #addStandardModal").on("hidden.bs.modal", function(){
        $(".hidden-block").hide();
    });
    
    $("#btnUpdateStandards").on("click", function(){
        var edit_data;
        if ($("#edit-core-standard").is(":visible")) {
            edit_data = {
                id: $("#edit-core-standard #standard_id").val(),
                standard_name: $("#edit-core-standard #standard_name").val(),
                standard_url: $("#edit-core-standard #standard_url").val()
            };
        } else if ($("#edit-sub-standard").is(":visible")) {
            edit_data = {
                id: $("#edit-sub-standard #substandard_id").val(),
                parent_id: $("#edit-sub-standard #substandard_parent_id").val(),
                standard_title: $("#edit-sub-standard #substandard_title").val(),
                url: $("#edit-sub-standard #substandard_url").val()
            };
        } else if ($("#edit-standard-notation").is(":visible")) {
            edit_data = {
                id: $("#edit-standard-notation #notation_id").val(),
                parent_id: $("#edit-standard-notation #notation_parent_id").val(),
                standard_notation: $("#edit-standard-notation #standard_notation").val(),
                description: $("#edit-standard-notation #description").val(),
                comment: $("#edit-standard-notation #comment").val(),
                url: $("#edit-standard-notation #notation_url").val()
            };
        }
        update_standard(edit_data);
    });
    
    $("#btnSaveStandards").on("click", function(){
        var add_data;
        if ($("#add-sub-standard").is(":visible")) {
            add_data = {
                parent_id: $("#add-sub-standard #standard_parent_id").val(),
                standard_title: $("#add-sub-standard #standard_title").val(),
                standard_url: $("#add-sub-standard #standard_url").val()
            }
            
        } else if ($("#add-standard-notation").is(":visible")) {
            add_data = {
                parent_id: $("#add-standard-notation #standard_parent_id").val(),
                standard_notation: $("#add-standard-notation #standard_notation").val(),
                description: $("#add-standard-notation #description").val(),
                comment: $("#add-standard-notation #comment").val(),
                url: $("#add-standard-notation #notation_url").val()
            }
        } else if ($("#add-core-standard").is(":visible")) {
            add_data = {
                standard_name: $("#add-core-standard #standard_name").val(),
                standard_url: $("#add-core-standard #standard_url").val()
            }
        }
        add_standard(add_data);
    });
    
    // move standard up
    $.fn.moveUp = function(){
        prev = $(this).parent().parent().prev();
        current = $(this).parent().parent();
        last = current.find('.std-pos').attr('data-count');
        prevPos = prev.find('.std-pos').val();
        curPos = current.find('.std-pos').val();
        prev.find('.std-pos').val(curPos);
        current.find('.std-pos').val(prevPos);
        if (prevPos==1) {
            current.find('.std-up').hide();
            prev.find('.std-up').show();
        }
        if (curPos==last) {
            prev.find('.std-down').hide();
            current.find('.std-down').show();
        }
        parent = $(this).parent().parent().parent().parent();
        
        current.insertBefore(prev);
        move_position(parent);
    }
    
    // move standard down
    $.fn.moveDown = function() {
        next = $(this).parent().parent().next();
        current = $(this).parent().parent();
        last = current.find('.std-pos').attr('data-count');
        nextPos = next.find('.std-pos').val();
        curPos = current.find('.std-pos').val();
        next.find('.std-pos').val(curPos);
        current.find('.std-pos').val(nextPos);
        if (curPos==1) {
            current.find('.std-up').show();
            next.find('.std-up').hide();
        }
        if (nextPos==last) {
            next.find('.std-down').show();
            current.find('.std-down').hide();
        }
        parent = $(this).parent().parent().parent().parent();
        $(this).parent().parent().insertAfter(next);
        
        move_position(parent);
    }
    
    
});

// display core standard details on edit
function display_standard_details(id) {
    data = {
        action: 'get_standard_details',
        std_id: id
    }
    
    var stndrd = id.split("-");
    var type = stndrd[0];
    var block_name;
    
    //* Process the AJAX POST request
    jQuery.post(
        ajaxurl,
        data
        ).done( function(response) {
            if (response) {
                details = JSON.parse(response);
                switch (type) {
                    case "core_standards":
                        jQuery("#editStandardModal #standard_id").val(details.id);
                        jQuery("#editStandardModal #standard_name").val(details.standard_name.replace(/\\/g,''));
                        jQuery("#editStandardModal #standard_url").val(details.standard_url);
                        block_name = "edit-core-standard";
                        break;
                    case "sub_standards":
                        jQuery("#editStandardModal #substandard_id").val(details.id);
                        jQuery("#editStandardModal #substandard_parent_id").val(details.parent_id);
                        jQuery("#editStandardModal #substandard_title").val(details.standard_title.replace(/\\/g,''));
                        jQuery("#editStandardModal #substandard_url").val(details.url);
                        block_name = "edit-sub-standard";
                        break;
                    case "standard_notation":
                        jQuery("#editStandardModal #notation_id").val(details.id);
                        jQuery("#editStandardModal #notation_parent_id").val(details.parent_id);
                        jQuery("#editStandardModal #standard_notation").val(details.standard_notation.replace(/\\/g,''));
                        jQuery("#editStandardModal #description").val(details.description.replace(/\\/g,''));
                        jQuery("#editStandardModal #comment").val(details.comment);
                        jQuery("#editStandardModal #notation_url").val(details.notation_url);
                        block_name = "edit-standard-notation";
                        break;
                }   
            }
            jQuery("#"+block_name).show();
        });
}

function update_standard(details) {
    data =  {
        action: "update_standard",
        details: details
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        var message;
        if (response===false) {
            message = "Updating standard failed."
        } else {
            message = "Standard successfully updated.";
        }
        jQuery('.standards-notice-success').empty().append("<p>"+message+"</p>");
        jQuery('.standards-notice-success').show();
        setTimeout(function(){
            jQuery('.standards-notice-success').hide();
        },5000);
        display_standards();
    });
}

function add_standard(details) {
    data =  {
        action: "add_standard",
        details: details
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        var message;
        if (response===false) {
            message = "Adding standard failed."
        } else {
            message = "Standard successfully added.";
        }
        jQuery('.standards-notice-success').empty().append("<p>"+message+"</p>");
        jQuery('.standards-notice-success').show();
        setTimeout(function(){
            jQuery('.standards-notice-success').hide();
        },5000);
        jQuery("#addStandardModal input").each(function(){
            jQuery(this).val("");
        });
        display_standards();
    });
}

function delete_standard(id) {
        data = {
            action: "delete_standard",
            standard_id: id
        }
        
        jQuery.post(
            ajaxurl,
            data
        ).done(function( response ){
            var message;
            if (response===false) {
                message = "Deleting standard failed."
            } else {
                message = "Standard successfully deleted.";
            }
            jQuery('.standards-notice-success').empty().append("<p>"+message+"</p>");
            jQuery('.standards-notice-success').show();
            setTimeout(function(){
                jQuery('.standards-notice-success').hide();
            },5000);
            display_standards();
        });
}

function display_standards() {
    data =  {
        action: "load_admin_standards"
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        jQuery("#admin-standard-list").html("");
        jQuery("#admin-standard-list").html(response);
    });
}

function move_position(parent) {
    id = parent.attr("id");
    parent.find("ul").first().children("li").each(function(){
        std_id = jQuery(this).find('.std-pos').attr('data-value');
        pos = jQuery(this).find('.std-pos').val();
        update_position(std_id,pos);
    });
}

function update_position(standard_id,pos) {
    data = {
        action: "update_standard_position",
        standard_id: standard_id,
        position: pos
    }
    
     jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        
    });
}

// Get File Extension
function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}

// Get URL Remote Extension
function getRemoteExtension(url) {
    var extension = url.match(/\.([^\./\?]+)($|\?)/)[1]
    return extension
}

//Import Standards
function importWASStandards(frm,btn) {
    if (jQuery(frm).find(':checkbox:checked').length==0){
        return(false);
    }
    
    if (jQuery(frm).find(':checkbox:checked').length){
        var ext = getRemoteExtension(jQuery('#oer_standard_other_url').val())
        if (ext!=="xml") {
            jQuery(frm).find(".field-error").show();
            setTimeout(function(){
                    jQuery(frm).find(".field-error").hide();
            }, 1500)
            return(false);	
        }
    }
    
    jQuery(btn).prop('value','Processing...');
    setTimeout(function() {
        var Top = document.documentElement.scrollTop || document.body.scrollTop;
        jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
        jQuery('.loader').show();
        } ,1000);
    jQuery('#importAcademicStandards .oer-import-row input[type=submit]').prop('disabled',true);
    return(true);
}

// Check All
function was_check_all(ref) {
    if(ref.checked)
    {
        jQuery(ref).parent('div').parent('li').children('ul').find("input:checkbox").each(function() {
            jQuery(this).prop('checked', true);
        });
    }
    else
    {
        jQuery(ref).parent('div').parent('li').children('ul').find("input:checkbox").each(function() {
            jQuery(this).prop('checked', false);
        });
    }
}

// Check Child
function was_check_myChild(ref) {
    if(jQuery(ref).parent('div').parent('li').has('ul')){
        if(ref.checked)
        {
            jQuery(ref).parent('div').parent('li').children('ul').children('li').find("input:checkbox").each(function() {
                jQuery(this).prop('checked', true);
            });
        }
        else
        {
            /*jQuery(ref).parent('div').parent('li').parent('ul').parent('li').children("div").find("input:checkbox").each(function() {
                jQuery(this).prop('checked', false);

            });*/
            jQuery(ref).parent('div').parent('li').children('ul').children('li').find("input:checkbox").each(function() {
                jQuery(this).prop('checked', false);
            });
        }
    }
}

//Show Loader
function wasShowLoader(form) {
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader').show();
	} ,1000);
	return true;
}