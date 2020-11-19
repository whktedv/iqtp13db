
function showDetail(num) {
     /* $("a#mlink").toggle();	 */
     $('.smallertextfield2').toggle();
}

function btncancel_Click() {
    var strconfirm = confirm("Anmeldung abbrechen / Cancel registration?");
    if (strconfirm == true) {
        return true;
    } else {
    	return false;
    }
}