

function showDetail(num) {
     /* $("a#mlink").toggle();	 */
     $('.smallertextfield2').toggle();
     $('.tp13_formdivwebappmultiradio2').toggle();
}

function btncancel_Click() {
    var strconfirm = confirm("Anmeldung abbrechen / Cancel registration?");
    if (strconfirm == true) {
        return true;
    } else {
    	return false;
    }
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

jQuery(function ($) {
    $.datepicker.setDefaults($.datepicker.regional["de"]);
});
