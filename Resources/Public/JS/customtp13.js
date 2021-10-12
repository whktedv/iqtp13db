

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

$(document).ready(function() {
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
    
});

$(function ($) {
    $.datepicker.setDefaults($.datepicker.regional["de"]);
});
