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
    
    $(".std-edit a").on("click", function(){
        var std_val = $(this).attr('data-value');
        display_standard_details(std_val);
        $("#editStandardModal").modal("show");
    });
    
    $(".std-add a").on("click", function(){
        var std_val = $(this).attr('data-parent');
        var std;
        if (std_val) {
            stds = std_val.split("-");
            std = stds[0];
        }
        if (std=="core_standards") {
            $("#addStandardModal #standard_parent_id").val(std_val);
            $("#addStandardModal #add-sub-standard").show();
        }
        $("#addStandardModal").modal("show");
    });
    
    $("#editStandardModal, #addStandardModal").on("hidden.bs.modal", function(){
        $(".hidden-block").hide();
    });
    
    $("#btnUpdateStandards").on("click", function(){
        if ($("#edit-core-standard").is(":visible")) {
            var edit_data = {
                id: $("#edit-core-standard #standard_id").val(),
                standard_name: $("#edit-core-standard #standard_name").val(),
                standard_url: $("#edit-core-standard #standard_url").val()
            };
            update_standard(edit_data);
        }
    });
    
    $("#btnSaveStandards").on("click", function(){
        if ($("#add-sub-standard").is(":visible")) {
            var add_data = {
                parent_id: $("#add-sub-standard #standard_parent_id").val(),
                standard_title: $("#add-sub-standard #standard_title").val(),
                standard_url: $("#add-sub-standard #standard_url").val()
            }
            add_standard(add_data);
        }
    });
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
                switch (type) {
                    case "core_standards":
                        details = JSON.parse(response);
                        jQuery("#editStandardModal #standard_id").val(details.id);
                        jQuery("#editStandardModal #standard_name").val(details.standard_name);
                        jQuery("#editStandardModal #standard_url").val(details.standard_url);
                        block_name = "edit-core-standard";
                        break;
                    case "sub_standards":
                        block_name = "edit-sub-standard";
                        break;
                    case "standard_notation":
                        block_name = "edit-standard-notation";
                        break;
                }   
            }
            jQuery("#"+block_name).show();
        });

    return false;
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
        jQuery('.standards-notice-success').append("<p>"+message+"</p>");
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
        jQuery('.standards-notice-success').append("<p>"+message+"</p>");
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