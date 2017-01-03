
var cclass = jQuery('#sort .sort_by').val();
if (cclass) {
    jQuery('.' + cclass).removeClass('fa-sort').addClass('fa-sort-amount-' + jQuery('#sort .sort_dimen').val());
}
jQuery('.sort').click(function () {
    if (jQuery(this).children('span').hasClass('fa-sort-amount-asc')) {
        jQuery(this).children('span').addClass('fa-sort-amount-desc').removeClass('fa-sort-amount-asc');
        jQuery('#sort .sort_dimen').val('desc');
    } else {
        jQuery(this).children('span').addClass('fa-sort-amount-asc').removeClass('fa-sort-amount-desc').removeClass('fa-sort');
        jQuery('#sort .sort_dimen').val('asc');
    }
    jQuery('#sort .sort_by').val(jQuery(this).data('sort'));
    jQuery('.sort').not(this).children('span').removeClass('fa-sort-amount-asc fa-sort-amount-desc').addClass('fa-sort');
    jQuery('#sort').submit();
});


//listeing remove cat
jQuery('.remove-cat button').click(function () {
    var cat_id = jQuery(this).data('cat');
    var main_id = jQuery(this).data('main');
    if (cat_id) {
        var that = this;
        jQuery.ajax({
            url: linkRemoveCat,
            type: "GET",
            dataType: 'json',
            data: {cat_id: cat_id, main_id: main_id}
        }).done(function (data) {
            jQuery(that).parent().remove();
        })
                .fail(function () {
                    alert("error");
                });
    }
})
jQuery(document).ready(function ($) {
    if (!window['linkAutocompleteCat']) {
        linkAutocompleteCat = "";
        linkAutocompleteGrammar = "";
    }
    /**********************
     * ** edit dialog *****
     *********************/
    jQuery('#edit, .click2edit').click(function () {
        jQuery('.click2edit').summernote({focus: true, styleWithSpan: false});
    }
    )
    jQuery('#save').click(function () {
        var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
        jQuery("#dialog_content").val(aHTML);
        jQuery('.click2edit').destroy();
    });

    jQuery('#edit_voc, .click2editvoc').click(function () {
        jQuery('.click2editvoc').summernote({focus: true, styleWithSpan: false});
    }
    )
    jQuery('#save_voc').click(function () {
        var aHTML = $('.click2editvoc').code(); //save HTML If you need(aHTML: array).
        jQuery("#voc_content").val(aHTML);
        jQuery('.click2editvoc').destroy();
    });
    /*************************
     * *** add cat **********
     ***********************/
    if (linkAutocompleteCat) {
        jQuery("#add_cat").autocomplete({
            source: linkAutocompleteCat,
            select: function (event, ui) {
                event.preventDefault();
                var dl_id = jQuery(event.target).data('id');
                addCat(ui.item.key, dl_id, ui.item.value)
            },
        });
    }
    function addCat(cat_id, dl_id, cat_title) {

        if (cat_id) {
            var that = this;
            jQuery.ajax({
                url: linkAddCat,
                type: "GET",
                dataType: 'json',
                data: {cat_id: cat_id, dl_id: dl_id}
            }).done(function (data) {
                jQuery('#cat_container').append('<span class="alert alert-warning remove-cat" style="display: inline-block;">'
                        + '<button aria-hidden="true" data-cat="' + cat_id + '" data-main="' + dl_id + '" class="close" type="button">×</button>'
                        + '<a class="cat-link" href="http://localhost/laravel/api/index.php/admin/listening/cat/' + cat_id + '">' + cat_title + '</a>'
                        + '</span>');
            })
                    .fail(function () {
                        alert("error");
                    });
        }
    }

    /**
     * listening grammar
     */
    //auto complete
    if(linkAutocompleteGrammar){
    jQuery("#add_grammar").autocomplete({
        source: linkAutocompleteGrammar,
        select: function (event, ui) {
            event.preventDefault();
            jQuery(this).val(ui.item.value);
            jQuery("#add_grammar_id").val(ui.item.key);
        },
    });
}
    //add grammar
    jQuery("#add_grammar_button").click(function () {
        var dl_id = jQuery(this).data('id');
        addGrammar(jQuery("#add_grammar_id").val(), dl_id, jQuery("#add_grammar_sentence").val(), jQuery("#add_grammar").val());
        //reset
        jQuery("#add_grammar_sentence").val('');
        jQuery("#add_grammar").val('');
        jQuery("#add_grammar_id").val('');

    });

    function addGrammar(gr_id, dl_id, ex, grammar_title) {

        if (gr_id) {
            var that = this;
            jQuery.ajax({
                url: linkAddGrammar,
                type: "GET",
                dataType: 'json',
                data: {dl_id: dl_id, ex: ex, gr_id: gr_id}
            }).done(function (data) {
                jQuery('#grammar_container').append('<span class="alert alert-warning remove-grammar" style="display: inline-block;">'
                        + ' <button aria-hidden="true" data-gr="' + gr_id + '" data-main="' + dl_id + '" class="close" type="button">×</button>'
                        + '<a class="cat-link" href="http://localhost/laravel/api/index.php/admin/grammar/lesson/' + gr_id + '">' + grammar_title + '</a> <br>'
                        + '<span>' + ex + '</span>'
                        + '</span>');

            })
                    .fail(function () {
                        alert("error");
                    });
        }
    }

    //listeing remove cat
    jQuery('.remove-grammar button').click(function () {
        var gr_id = jQuery(this).data('gr');
        var main_id = jQuery(this).data('main');
        if (gr_id) {
            var that = this;
            jQuery.ajax({
                url: linkRemoveGrammar,
                type: "GET",
                dataType: 'json',
                data: {gr_id: gr_id, main_id: main_id}
            }).done(function (data) {
                jQuery(that).parent().remove();
            })
                    .fail(function () {
                        alert("error");
                    });
        }
    })


    /**
     * idiom
     */
    jQuery("#idiom_word").change(function () {
        var id_id = jQuery(this).data('id');
        var title = jQuery(this).val();
        jQuery.ajax({
            url: linkIdiomWord,
            type: "GET",
            dataType: 'json',
            data: {id_id: id_id, word: title}
        }).done(function (data) {
            alert('done');

        }).fail(function () {
            alert("error");
        });
    })

    jQuery('.dim').click(function () {
        jQuery('#save_voc').trigger("click");
        jQuery('#save').trigger("click");
    });
});

// Generate SelectImage

function ResetValue(id) {
	document.getElementById(id).value = "";
}
function PreviewImage(obj,id) {
	var src = document.getElementById(id).value;
	document.getElementById(obj).setAttribute("data-original-title", "<img src ='"+src+"'/>"); 
	if(src == "") 
	{
		document.getElementById(obj).setAttribute("data-original-title", "Select image"); 
	}
	$("#"+obj).tooltip({
	    animated: 'fade',
	    placement: 'bottom',
	    html: true
	});
}
var urlobj;

function BrowseServer(obj, url)
{
	urlobj = obj;
	OpenServerBrowser(
	url,
	screen.width * 0.7,
	screen.height * 0.7 ) ;
}

function OpenServerBrowser( url, width, height )
{
	var iLeft = (screen.width - width) / 2 ;
	var iTop = (screen.height - height) / 2 ;
	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;
	var oWindow = window.open( url, "BrowseWindow", sOptions ) ;
}

function SetUrl( url, width, height, alt )
{
	document.getElementById(urlobj).value = url ;
	oWindow = null;
}
//End Generate SelectImage