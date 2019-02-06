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
});

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