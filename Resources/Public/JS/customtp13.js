// ********************************************************************************
// Prüfe auf noch nicht gespeicherte Form-Elemente und warne den Benutzer entsprechend
// *** Quellcode von ChatGPT ****

var formChanged = false;

function markFormChanged() {
    formChanged = true;
}

window.onbeforeunload = function() {
    if (formChanged) {
        return "Warnung: Sie haben das Formular geändert, aber nicht gespeichert. Wenn Sie diese Seite verlassen, gehen Ihre Änderungen verloren.";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("teilnehmerform");
	if(form) {
	    var formElements = form.elements;
	    for(var i = 0; i < formElements.length; i++) {
	        formElements[i].addEventListener("change", markFormChanged);
	    }
		
		if (document.getElementById("savebutton1")) {
			var submitButton1 = document.getElementById("savebutton1");
		    submitButton1.addEventListener("click", function() {
				$("#overlay").hide();
		        window.onbeforeunload = null;			
		    });	
		}
		if (document.getElementById("weiterbutton")) {
			var weiterbutton = document.getElementById("weiterbutton");
		    weiterbutton.addEventListener("click", function() {
				$("#overlay").hide();
		        window.onbeforeunload = null;			
		    });	
		}
		if (document.getElementById("zurueckbutton")) {
			var zurueckbutton = document.getElementById("zurueckbutton");
		    zurueckbutton.addEventListener("click", function() {
				$("#overlay").hide();
		        window.onbeforeunload = null;			
		    });	
		}		
	}	
});

// ********************************************************************************
// Prevent Double Submits by showing Overlay with rotating circle after submit-click

document.querySelectorAll('form').forEach(form => {
	form.addEventListener('submit', (e) => {
		// Prevent if already submitting
		if (form.classList.contains('is-submitting')) {
			e.preventDefault();
		}
		
		if(form.attributes.id) {
			var formid = form.attributes.id.value;
			if(formid != 'exportformdata' && formid != 'exportfilterform' && !document.getElementById('newabschlusszurueckbutton')) {
				$("#overlay").show();
			}
		}
	});
});

if (document.getElementById("abschlusshinzubutton")) {
	var abschlusshinzubutton = document.getElementById("abschlusshinzubutton");
    abschlusshinzubutton.addEventListener("click", function() {
		$("#overlay").show();
    });	
}

// *********************************************************************************

		
$(document).ready(function() {
			
	$('.datumtextfield').each(function(){
	    $(this).datepicker({
	        showButtonPanel : true,
	        firstDay: 1,
	        dateFormat : "dd.mm.yy"        
	    });
	});	

	$('.datumtextfieldberatung').each(function(){
	    $(this).datepicker({
	        showButtonPanel : true,
	        firstDay: 1,
	        dateFormat : "yy-mm-dd"        
	    });
	});	
		
	$('#datepicker1').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"        
    });
    $('#datepicker2').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"
    });
    $('#datepicker3').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"
    }); 
    $('#datepicker4').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"
    });

    $('#datepickerfiltervon').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"
    });

    $('#datepickerfilterbis').datepicker({
        showButtonPanel : true,
        firstDay: 1,
        dateFormat : "dd.mm.yy"
    });
	
	$("a[href='#top']").click(function(){
		$("html, body").animate({ scrollTop: 0 }, "fast");
  		return false;
	})
	
    $( "#resizablecol1" ).resizable({
      maxWidth: 450,
      minWidth: 200
    });
	$( "#resizablecol2" ).resizable({
      maxWidth: 450,
	  minWidth: 200
    });
	$( "#resizablecol3" ).resizable({
      maxWidth: 450,
      minWidth: 200
    });

	$('#exportfilterselect').change(function() {
		$("#overlay").show();
	  $("#exportfilterform").submit(); 
	});
	$('#exportfilterselectstaat').change(function() {
		$("#overlay").show();
	  $("#exportfilterform").submit(); 
	});
	
		
	
	// ############ Loading Overlay u.a. bei Submit-Click #############
	
	$("input[name='tx_iqtp13db_iqtp13dbwebapp[file]']").change(function() { 
    	this.form.submit(); 
    	$("#overlay").show();
    });

	$("#linkdeletefile").click(function() {
    	$("#overlay").show();
    });
    
    $("#linkloadoverlay-new").click(function() {
    	$("#overlay").show();
    });
    $("#linkloadoverlay-edit").click(function() {
    	$("#overlay").show();
    });
    $("#linkloadoverlay-back").click(function() {
    	if(formChanged != true) $("#overlay").show();
    });
    $("#linkloadoverlay-savedb").click(function() {
    	$("#overlay").show();
    });
		
    
    $("body").prepend('<div id="overlay" class="ui-widget-overlay" style="z-index: 10001; display: none;"><div class="overlay-inner"><img width="200" height="200" src="/typo3conf/ext/iqtp13db/Resources/Public/Icons/giphy.gif"/></div></div>');
  
	$("#selectbstelle").change(function() { 
    	this.form.submit();
    	$("#overlay").show();
    });

	$("#selectbundesland").change(function() { 
    	this.form.submit();
    	$("#overlay").show();
    });
    
    $("ul.typo3-messages").click(function() {
    	$("ul.typo3-messages").hide();
    });
    
    $(".errorframe").click(function() {
    	$(".errorframe").hide();
    });
});


$(function ($) {
    $.datepicker.setDefaults($.datepicker.regional["de"]);
});

function form_submit()
{
     // add hidden field to form
      $('#teilnehmerform').append('<input type="hidden" name="tx_iqtp13db_iqtp13dbadmin[selectboxsubmit]" value="1" />');
      $("#teilnehmerform").submit(); 
}


function showNiqstatus(uid) {
    $('#dialog-niqstatus' + uid).toggle();

	dialog = $("#dialog-niqstatus" + uid).dialog({
      autoOpen: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        'Ok': function() {
          $( this ).dialog( "close" );
		  return false;
        }
      }
	});	
	dialog.dialog( "open" ); 
}

function showDetail(num) {
     /* $("a#mlink").toggle();	 */
     $('.smallertextfield2').toggle();
     $('.tp13_formdivwebappmultiradio2').toggleClass("flex");
     $('#mlink2').toggle();
     $('#mlink').toggle();
}
 
function btncancel_Click() {
		dialog = $("#dialog-confirm").dialog({
	      autoOpen: false,
	      resizable: false,
	      height: "auto",
	      width: 400,
	      modal: true,
	      buttons: {
	        'Ja / Yes': function() {
			  document.getElementById("teilnehmerform").submit();
	        },
	        'Nein / No': function() {
	          $( this ).dialog( "close" );
	        }
	      }
    });
	
	dialog.dialog( "open" );
	return false;
}

function showFolgekontakt(uid) {
    $('table#fkt' + uid).toggle();
}

