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
    
    $("#admin-standard-list,#admin-standard-children-list").on("click", ".std-edit a", function(){
        var std_val = $(this).attr('data-value');
        was_display_standard_details(std_val);
        $("#editStandardModal").modal("show");
    });
    
    $("#addStandardSet").on("click", function(){
        $("#addStandardModal #add-core-standard").show();
        $("#addStandardModal").modal("show");
    });
    
    $("#addStandard").on("click", function(){
        parent_std = $(this).attr('data-parent');
        siblings = jQuery('#admin-standard-children-list > div > ul > li.was_sbstndard:last-child input.std-pos').attr('data-count');
        $("#addStandardModal #add-sub-standard #standard_parent_id").val(parent_std);
        $("#addStandardModal #add-sub-standard #sibling_count").val(siblings);
        $("#addStandardModal #add-sub-standard").show();
        $("#addStandardModal").modal("show");
    });
    
    $("#admin-standard-list,#admin-standard-children-list").on("click", ".std-add a", function(){
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
        var std_type = $(this).attr('stdtyp');
        var std_value = $(this).attr('data-value');
        console.log('std_value: '+std_value);
        if (confirm("Are you sure you want to delete this standard?")==true) {
            was_delete_standard(std_id,std_type,std_value,this);
        }
    });
    
    $("#admin-standard-children-list").on("click", ".std-del a", function(){
        var std_id = $(this).attr('data-stdid');
        var std_type = $(this).attr('stdtyp');
        var std_value = $(this).attr('data-value');
        console.log('std_value: '+std_value);
        if (confirm("Are you sure you want to delete this standard?")==true) {
            was_delete_standard(std_id,std_type,std_value,this);
        }
    });
    
    $("#admin-standard-children-list").on("click", ".std-up a", function(){
        $(this).moveUp();
    });
    
    $("#admin-standard-children-list").on("click", ".std-down a", function(){
        $(this).moveDown();
    });
    
    $("#editStandardModal, #addStandardModal").on("hidden.bs.modal", function(){
        $(".hidden-block").hide();
    });
    
    $("#btnUpdateStandards").on("click", function(){
        var edit_data, std;
        if ($("#edit-core-standard").is(":visible")) {
            edit_data = {
                id: $("#edit-core-standard #standard_id").val(),
                standard_name: $("#edit-core-standard #standard_name").val(),
                standard_url: $("#edit-core-standard #standard_url").val()
            };
            std = "core_standards";
        } else if ($("#edit-sub-standard").is(":visible")) {
            edit_data = {
                id: $("#edit-sub-standard #substandard_id").val(),
                parent_id: $("#edit-sub-standard #substandard_parent_id").val(),
                standard_title: $("#edit-sub-standard #substandard_title").val(),
                url: $("#edit-sub-standard #substandard_url").val()
            };
            std = "sub_standards";
        } else if ($("#edit-standard-notation").is(":visible")) {
            edit_data = {
                id: $("#edit-standard-notation #notation_id").val(),
                parent_id: $("#edit-standard-notation #notation_parent_id").val(),
                standard_notation: $("#edit-standard-notation #standard_notation").val(),
                description: $("#edit-standard-notation #description").val(),
                comment: $("#edit-standard-notation #comment").val(),
                url: $("#edit-standard-notation #notation_url").val()
            };
            std = "standard_notation";
        }
        was_update_standard(edit_data, std);
    });
    
    $("#btnSaveStandards").on("click", function(){
        var add_data, std;
        if ($("#add-sub-standard").is(":visible")) {
            add_data = {
                siblings: $("#add-sub-standard #sibling_count").val(),
                parent_id: $("#add-sub-standard #standard_parent_id").val(),
                standard_title: $("#add-sub-standard #standard_title").val(),
                standard_url: $("#add-sub-standard #standard_url").val()
            }
            std = "sub_standards";
        } else if ($("#add-standard-notation").is(":visible")) {
            add_data = {
                siblings: $("#add-standard-notation #sibling_count").val(),
                parent_id: $("#add-standard-notation #standard_parent_id").val(),
                standard_notation: $("#add-standard-notation #standard_notation").val(),
                description: $("#add-standard-notation #description").val(),
                comment: $("#add-standard-notation #comment").val(),
                url: $("#add-standard-notation #notation_url").val()
            }
            std = "standard_notation";
        } else if ($("#add-core-standard").is(":visible")) {
            add_data = {
                standard_name: $("#add-core-standard #standard_name").val(),
                standard_url: $("#add-core-standard #standard_url").val()
            }
            std = "core_standards";
        }
        was_add_standard(add_data, std);
    });
    
    // move standard up
    $.fn.moveUp = function(){
      /*
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
        was_move_position(parent);
      */
      
      prev = $(this).parent().parent().prev();
      current = $(this).parent().parent();
      parent = $(this).parent().parent().parent().parent();
      current.insertBefore(prev);
      var licount = jQuery(this).closest('.was_sub_standards_wrapper').find('ul').first().children('li').length;
      jQuery(this).closest('.was_sub_standards_wrapper').find('ul').first().children('li').each(function(i, li) {  
        //jQuery(li).find('input:first').val(i + 1);
        jQuery(li).find('input.std-pos').val(i + 1);
        if(licount > 0){
          if(jQuery(li).is( ':first-child' )){ 
            jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' First');
            jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); 
          }else if(jQuery(li).is( ':last-child' )){ 
            jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i+' Last');
            jQuery(li).find('.std-down').first().addClass("hidden-block").hide();
          }else{
            jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i);
            jQuery(li).find('.std-down').first().removeClass("hidden-block").show();
          }
        }else{
          jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' single');
          jQuery(li).find('.std-down').first().addClass("hidden-block").hide();
        }
      });
      was_move_position(parent);
    }
    
    // move standard down
    $.fn.moveDown = function() {
      /*
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
        
        was_move_position(parent);
        */
        
        next = $(this).parent().parent().next();
        current = $(this).parent().parent();
        parent = $(this).parent().parent().parent().parent();
        $(this).parent().parent().insertAfter(next);
        var licount = jQuery(this).closest('.was_sub_standards_wrapper').find('ul').first().children('li').length;
        jQuery(this).closest('.was_sub_standards_wrapper').find('ul').first().children('li').each(function(i, li) {  
          //jQuery(li).find('input:first').val(i + 1);
          jQuery(li).find('input.std-pos').val(i + 1);
          if(licount > 0){
            if(jQuery(li).is( ':first-child' )){ 
              jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' First');
              jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); 
            }else if(jQuery(li).is( ':last-child' )){ 
              jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i+' Last');
              jQuery(li).find('.std-down').first().addClass("hidden-block").hide();
            }else{
              jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i);
              jQuery(li).find('.std-down').first().removeClass("hidden-block").show();
            }
          }else{
            jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' single');
            jQuery(li).find('.std-down').first().addClass("hidden-block").hide();
          }
        });
        was_move_position(parent);
    }
    
    /** Move Loader Background **/
    if ($('.loader').length>0){
        var loader = $('.loader');
        $('#wpcontent').append(loader);
    }
});

// display core standard details on edit
function was_display_standard_details(id) {
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
                        jQuery("#editStandardModal #comment").val(details.comment.replace(/\\/g,''));
                        jQuery("#editStandardModal #notation_url").val(details.url);
                        block_name = "edit-standard-notation";
                        break;
                }   
            }
            jQuery("#"+block_name).show();
        });
}

/** Update Standard **/
function was_update_standard(details, type) {
    data =  {
        action: "update_standard",
        details: details
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        response = JSON.parse(response);
        var message;
        if (response.success===false) {
            message = "Updating standard failed."
        } else {
            message = "Standard successfully updated.";
        }
        jQuery('.standards-notice-success').empty().append("<p>"+message+"</p>");
        jQuery('.standards-notice-success').show();
        setTimeout(function(){
            jQuery('.standards-notice-success').hide();
        },5000);
        
        switch (type) {
            case "core_standards":
                jQuery('.core-standard a[data-target="#' + type + '-' + details['id'] + '"]').text("");
                jQuery('.core-standard a[data-target="#' + type + '-' + details['id'] + '"]').text(response.standard.standard_name);
                break;
            case "sub_standards":
                jQuery('.was_sbstndard  a[data-target*="#' + type + '-' + details['id'] + '"]').text("");
                jQuery('.was_sbstndard  a[data-target*="#' + type + '-' + details['id'] + '"]').text(response.standard.standard_title);
                break;
            case "standard_notation":
                console.log(type + " - " + details['id'] + " - " + response.standard.standard_notation);
                jQuery('.was_standard_notation[data-target*="#' + type + '-' + details['id'] + '"] .was_stndrd_prefix').html("");
                jQuery('.was_standard_notation[data-target*="#' + type + '-' + details['id'] + '"] .was_stndrd_prefix').html(response.standard.standard_notation);
                jQuery('.was_standard_notation[data-target*="#' + type + '-' + details['id'] + '"] .was_stndrd_desc').text("");
                jQuery('.was_standard_notation[data-target*="#' + type + '-' + details['id'] + '"] .was_stndrd_desc').text(response.standard.description);
                break;
        }
    });
}

/** Add Standard **/
function was_add_standard(details, type) {
    data =  {
        action: "add_standard",
        details: details,
        type: type
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        console.log(response);
        var message;
        response = JSON.parse(response);
        if (response.success===false) {
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
        
        switch (type) {
            case "core_standards":
                coreStandard = was_getCoreStandardDisplay(details, response.id);
                jQuery('ul.was-standard-list').append(coreStandard);
                break;
            case "sub_standards":
                childCount = details['siblings'];
                subStandard = was_getSubStandardDisplay(details, response.id, childCount);
                jQuery('#' + details['parent_id'] + ' ul li.was_sbstndard:last-child .std-down').removeClass("hidden-block").show();
                jQuery('#' + details['parent_id'] + ' > ul').append(subStandard);
                
                jQuery('#' + details['parent_id']).find('ul').first().children('li').each(function(i, li) {              
                  jQuery(li).find('input:first').val(i + 1);
                  if(jQuery(li).is( ':first-child' )){ 
                    jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' - First');
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i+' - First'); 
                  }else if(jQuery(li).is( ':last-child' )){ 
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i+' - Last'); 
                    jQuery(li).find('.std-down').first().addClass("hidden-block").hide(); console.log(i+' - Last'); 
                  }else{
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show();
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i); 
                  }
                });
                was_move_position(jQuery('#'+details['parent_id']));
                break;
            case "standard_notation":
                childCount = details['siblings'];
                standardNotation = was_getStandardNotationDisplay(details, response.id, childCount);
                
                
                jQuery('#' + details['parent_id'] + ' > ul').append(standardNotation);
                
                jQuery('#' + details['parent_id']).find('ul').first().children('li').each(function(i, li) {           
                  jQuery(li).find('input:first').val(i + 1);
                  if(jQuery(li).is( ':first-child' )){ 
                    jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' - First');
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i+' - First'); 
                  }else if(jQuery(li).is( ':last-child' )){ 
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i+' - Last'); 
                    jQuery(li).find('.std-down').first().addClass("hidden-block").hide(); console.log(i+' - Last'); 
                  }else{
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show();
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i); 
                  }
                });

                var cnt = jQuery('#' + details['parent_id']).find('ul').children('li').length;
                if(cnt > 0){
                  if(cnt == 1){
                    jQuery('#' + details['parent_id']).find('ul').children('li').find('.std-up').hide();
                    jQuery('#' + details['parent_id']).find('ul').children('li').find('.std-down').hide();
                  }
                  jQuery('input[data-value="'+details['parent_id']+'"]').siblings('.was_stndrd_prefix').removeClass('nochild');
                  jQuery('#' + details['parent_id']).addClass(['collapse','show']).show();
                }else{
                  jQuery('input[data-value="'+details['parent_id']+'"]').siblings('.was_stndrd_prefix').addClass('nochild');
                }
                
                /*
                console.log(details['parent_id']);
                var grabbed_title = jQuery('input[data-value="'+details['parent_id']+'"]').siblings('.was_stndrd_prefix').text();
                var anchor_html = "<a class='was_stndrd_prefix' data-toggle='collapse' data-target='#"+details['parent_id']+"'>"+grabbed_title+"</a>";
                jQuery('input[data-value="'+details['parent_id']+'"]').siblings('.was_stndrd_prefix').replaceWith(anchor_html);
                console.log(grabbed_title);
                */
                
                
                /*
                if (jQuery('#' + details['parent_id'] + '-1').is(":visible")){
                    jQuery('#' + details['parent_id'] + '-1 ul li.was_standard_notation:last-child .std-down').removeClass("hidden-block").show();
                    jQuery('#' + details['parent_id'] + '-1 ul').append(standardNotation);
                } else {
                    if (jQuery('#' + details['parent_id']).is(":visible")){
                        jQuery('#' + details['parent_id'] + ' ul li.was_standard_notation:last-child .std-down').removeClass("hidden-block").show();
                        jQuery('#' + details['parent_id'] + ' ul').append(standardNotation);
                    } else {
                        jQuery('input[data-value="' + details['parent_id'] + '"').closest('li').append('<div id="' + details['parent_id'] + '"><ul>' + standardNotation + '</ul></div>');
                        if (jQuery('input[data-value="' + details['parent_id'] + '"').next("a").hasClass('nochild'))
                            jQuery('input[data-value="' + details['parent_id'] + '"').next("a").removeClass('nochild');
                    }
                }
                */
                break;
        }
    });
}

function was_getCoreStandardDisplayCollapse(standard, stdid) {
    var corestd = "core_standards-" + stdid;
    var html = '<li class="core-standard">';
    html += '<a href="' + WPURLS.admin_url + "admin.php?page=wp-academic-standards&std=core_standards-" + stdid + '" data-toggle="collapse" data-id="' + stdid + '" data-target="#core_standards-' + stdid + '">' + standard['standard_name'].replace(/\\/g,'') + '</a>';
    html += ' <span class="std-edit std-icon"><a data-target="#editStandardModal" class="std-edit-icon" data-value="' + corestd + '" data-stdid="' + stdid + '" stdtyp="cst"><i class="far fa-edit"></i></a></span><span class="std-del std-icon"><a class="std-del-icon" data-stdid="' + stdid + '" data-value="' + corestd + '" stdtyp="cst"><i class="far fa-trash-alt"></i></a></span>';
    html += '</li>';
    return html;
}

function was_getCoreStandardDisplay(standard, stdid) {
    var corestd = "core_standards-" + stdid;
    var html = '<li class="core-standard">';
    html += '<a href="' + WPURLS.admin_url + "admin.php?page=wp-academic-standards&std=core_standards-" + stdid + '" data-toggle="collapse" data-id="' + stdid + '" data-target="#core_standards-' + stdid + '">' + standard['standard_name'].replace(/\\/g,'') + '</a>';
    html += ' <span class="std-edit std-icon"><a data-target="#editStandardModal" class="std-edit-icon" data-value="' + corestd + '" data-stdid="' + stdid + '" stdtyp="cst"><i class="far fa-edit"></i></a></span><span class="std-del std-icon"><a class="std-del-icon" data-stdid="' + stdid + '" data-value="' + corestd + '" stdtyp="cst"><i class="far fa-trash-alt"></i></a></span>';
    html += '</li>';
    return html;
}

function was_getSubStandardDisplay(standard, stdid, lastIndex) {
    var substd = "sub_standards-" + stdid;
    var html = '<li class="was_sbstndard">';
    lastIndex++;
    html += '<input type="hidden" name="pos[]" class="std-pos" data-value="' + substd + '" data-count="' + lastIndex + '" value="' + lastIndex + '">';
    html += '<a class="was_stndrd_prefix nochild" data-toggle="collapse" data-target="#' + substd + ',#' + substd + '-1">' + standard['standard_title'].replace(/\\/g,'') + '</a>';
    //html += '<span class="was_stndrd_prefix"><strong>' + standard['standard_title'].replace(/\\/g,'') + '</strong></span>';
    html += ' <span class="std-up std-icon"><a href="#"><i class="fas fa-arrow-up"></i></a></span><span class="std-down std-icon hidden-block"><a href="#"><i class="fas fa-arrow-down"></i></a></span> <span class="std-edit"><a class="std-edit-icon" data-target="#editStandardModal" data-value="' + substd + '" data-stdid="' + stdid + '"><i class="far fa-edit"></i></a></span> <span class="std-add"><a data-target="#addStandardModal" class="std-add-icon" data-parent="' + substd + '"><i class="fas fa-plus"></i></a></span><span class="std-del std-icon"><a class="std-del-icon" data-stdid="' + stdid + '" data-value="' + substd + '" stdtyp="sbs"><i class="far fa-trash-alt"></i></a></span>';
    html += ' <div class="was_sub_standards_wrapper"><div id="'+substd+'" class="collapse show"><ul></ul></div></div>';
    html += '</li>';
    return html;
}

function was_getStandardNotationDisplay(standard,stdid, lastIndex) {
    var substd = "standard_notation-" + stdid;
    var html = '<li class="was_standard_notation">';
    lastIndex++;
    html += '<input type="hidden" name="pos[]" class="std-pos" data-value="' + substd + '" data-count="' + lastIndex + '" value="' + lastIndex + '">';
    html += "<a class='was_stndrd_prefix nochild' data-toggle='collapse' data-target='#standard_notation-"+stdid+"'>"+standard['standard_notation'].replace(/\\/g,'')+"</a>"
    //html += '<span class="was_stndrd_prefix"><strong>' + standard['standard_notation'].replace(/\\/g,'') + '</strong></span>';
    html += '<div class="was_stndrd_desc">';
    html += standard['description'].replace(/\\/g,'');
    html += '</div>';
    html += ' <span class="std-up std-icon"><a href="#"><i class="fas fa-arrow-up"></i></a></span><span class="std-down std-icon hidden-block"><a href="#"><i class="fas fa-arrow-down"></i></a></span> <span class="std-edit"><a class="std-edit-icon" data-target="#editStandardModal" data-value="' + substd + '" data-stdid="' + stdid + '"><i class="far fa-edit"></i></a></span> <span class="std-add"><a data-target="#addStandardModal" class="std-add-icon" data-parent="' + substd + '"><i class="fas fa-plus"></i></a></span><span class="std-del std-icon"><a class="std-del-icon" data-stdid="' + stdid + '" data-value="' + substd + '" stdtyp="stn"><i class="far fa-trash-alt"></i></a></span>';
    html += ' <div class="was_sub_standards_wrapper"><div id="'+substd+'" class="collapse show"><ul></ul></div></div>';
    html += '</li>';
    return html;
}

function was_delete_standard(id,typ,std_value,obj) {
  
  console.log('parent:'+jQuery(obj).parent().parent().parent().parent().attr('id'));
        jQuery('.was_preloader_wrapper').show();
        if(typ == 'sbs'){
          data = {action: "delete_sub_standard",standard_id: id, standard_value: std_value}
        }else if(typ == 'cst'){
          data = {action: "delete_core_standard",standard_id: id, standard_value: std_value}
        }else{
          data = {action: "delete_standard",standard_id: id, standard_value: std_value}
        }
        
        jQuery.ajax({
      		type:'POST',
      		url: ajaxurl,
      		data: data,
      		success:function(response){
            response = JSON.parse(response);
            var message; var notice_class;
            console.log('Children: '+ response['children']);
            switch(response['textstatus']) {
              case 'failed':
                message = "Deleting standard failed.";
                notice_class = 'notice-error';
                break;
              case 'has_children':
                message = "Unable to delete: target standard has children."
                notice_class = 'notice-warning';
                break;
              default:
                message = "Standard successfully deleted.";
                notice_class = 'notice-success';
                
                var parent_ul = jQuery(obj).closest('ul');
                var licount = parent_ul.children('li').length;
                console.log('licount: '+licount);
                if(licount > 1){
                  jQuery(obj).closest('.was_sub_standards_wrapper').siblings('.was_stndrd_prefix').removeClass('nochild');
                  jQuery(obj).closest('li').remove();
                  
                }else{
                  //parent_ul.find('.was_sub_standards_wrapper').remove();
                  jQuery(obj).closest('.was_sub_standards_wrapper').siblings('.was_stndrd_prefix').addClass('nochild');
                  parent_ul.empty();
                  
                }
                
                var licount = parent_ul.children('li').length;
                console.log('licount: '+licount);
                parent_ul.children('li').each(function(i, li) {              
                  //jQuery(li).find('input:first').val(i + 1);
                  jQuery(li).find('.std-pos').val(i + 1);
                  if(jQuery(li).is( ':first-child' )){
                    jQuery(li).find('.std-up').first().addClass("hidden-block").hide(); console.log(i+' - First');
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i+' - First'); 
                  }else if(jQuery(li).is( ':last-child' )){ 
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show(); console.log(i+' - Last'); 
                    jQuery(li).find('.std-down').first().addClass("hidden-block").hide(); console.log(i+' - Last'); 
                  }else{
                    jQuery(li).find('.std-up').first().removeClass("hidden-block").show();
                    jQuery(li).find('.std-down').first().removeClass("hidden-block").show(); console.log(i); 
                  }
                });
                
                var cnt = parent_ul.children('li').length;
                console.log('AFTER DELETE: 11111 '+cnt);
                  if(cnt == 1){
                    console.log('AFTER DELETE: 22222');
                    parent_ul.find('.std-up').hide();
                    parent_ul.find('.std-down').hide();
                  }


                

                
            }

            jQuery('.standards-notice-success').removeClass(['notice_error','notice-warning','notice-success']).addClass(notice_class);
            jQuery('.standards-notice-success').empty().append("<p>"+message+"</p>");
            jQuery('.standards-notice-success').show();
            
            setTimeout(function(){ jQuery('.was_preloader_wrapper').hide(); },1000);
            setTimeout(function(){ jQuery('.standards-notice-success').hide(); },5000);
      		}
      	});    
        
}


function was_display_standards(callback) {
    data =  {
        action: "load_admin_standards"
    }
    
    jQuery.post(
        ajaxurl,
        data
    ).done(function( response ){
        jQuery("#admin-standard-children-list").html("");
        jQuery("#admin-standard-children-list").html(response);
        if (callback && typeof(callback) === "function") {
          callback();
      	}
    });
}

function was_move_position(parent) {
    id = parent.attr("id");
    parent.find("ul").first().children("li").each(function(){
        std_id = jQuery(this).find('.std-pos').attr('data-value');
        pos = jQuery(this).find('.std-pos').val();
        console.log("POSITION UPDATE: "+std_id+"-"+pos);
        was_update_position(std_id,pos);
    });
}

function was_update_position(standard_id,pos) {
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
function was_getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}

// Get URL Remote Extension
function was_getRemoteExtension(url) {
    var extension = url.match(/\.([^\./\?]+)($|\?)/)[1]
    return extension
}

//Import Standards
function was_importWASStandards(frm,btn) {
    if (jQuery(frm).find(':checkbox:checked').length==0){
        return(false);
    }
    
    if (jQuery(frm).find(':checkbox:checked').length){
        var ext = was_getRemoteExtension(jQuery('#oer_standard_other_url').val())
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
function was_ShowLoader(form) {
	setTimeout(function() {
		var Top = document.documentElement.scrollTop || document.body.scrollTop;
		jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
		jQuery('.loader').show();
	} ,1000);
	return true;
}