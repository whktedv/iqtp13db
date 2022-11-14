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
	  $("#exportfilterform").submit(); 
	});
	
	$('#datepickerfiltervon').change(function() {
	  $("#exportfilterform").submit(); 
	});
	
	$('#datepickerfilterbis').change(function() {
	  $("#exportfilterform").submit(); 
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
 
function btncancel_Click(link) {
	dialog = $("#dialog-confirm").dialog({
	      autoOpen: false,
	      resizable: false,
	      height: "auto",
	      width: 400,
	      modal: true,
	      buttons: {
	        'Ja / Yes': function() {
	          $( this ).dialog( "close" );
	          if(link != '') {
	        	  window.location.href = link;
	          } else {
	        	  $( "form" ).submit();  
	          }
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

