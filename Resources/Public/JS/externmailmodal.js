// JavaScript für Modal-Funktionalität

function openEmailModal(itemId) {
    const modal = document.getElementById('mail4externmodal' + itemId);
    if (modal) {
        modal.showModal();		
		asyncupdateteilnehmereditlink(itemId);
    }
}

function closeEmailModal(itemId) {
    const modal = document.getElementById('mail4externmodal' + itemId);
    if (modal) {
        modal.close();
		location.reload();
    }
}

// Modal schließen bei Klick auf Backdrop
document.addEventListener('click', function(event) {
    if (event.target.tagName === 'DIALOG') {
        const modal = event.target;
        const rect = modal.getBoundingClientRect();
        const isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
                           rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
        if (!isInDialog) {
            modal.close();
        }
    }
});

// ESC-Taste zum Schließen des Modals
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModals = document.querySelectorAll('dialog[open]');
        openModals.forEach(modal => {
            modal.close();
        });
    }
});

function linkKopieren(uid, persdat) {	
	var linklink = '';
	if(persdat == 1) {
		linklink = document.getElementById("editlinkforRS" + uid);
	} else {
		linklink = document.getElementById("editlinkforRSohnePersDat" + uid);
	}
  	
	navigator.clipboard.writeText(linklink.href)  	
    .then(() => {
	  document.getElementById("mail4externfeedback" + uid).style.display = "block"; 
      document.getElementById("mail4externfeedback" + uid).innerHTML = "Link kopiert!";
    })
    .catch(() => {
	  document.getElementById("mail4externfeedback" + uid).style.display = "block"; 
      document.getElementById("mail4externfeedback" + uid).innerHTML = "Kopieren fehlgeschlagen.";
    });
}

function asyncupdateteilnehmereditlink(uid) {
	var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?eID=tneditlinksave', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {			
        if (xhr.readyState == 4 && xhr.status == 200) {
			var jsonresponse = JSON.parse(xhr.responseText);
            document.getElementById('mail4externfeedback' + uid).innerHTML = jsonresponse.message;			
        }
		if (xhr.readyState == 4 && xhr.status == 500) {
			document.getElementById('mail4externfeedback' + uid).innerHTML = "<span style='color: red; font-weight: bold;'>Error " + xhr.status + " - Zeitstempel konnte nicht gespeichert werden. Sollte dieser Fehler erneut erscheinen, bitte Support kontaktieren.</span>";
		}
    };

    xhr.send('tnuid=' + encodeURIComponent(uid));
}

/* 
*--------------------------------------------------------------------
* ----------------------- für Fileonly Links: ----------------------- 
* -------------------------------------------------------------------
*/
function openFileLinkModal(tnuid, itemId) {
    const modal = document.getElementById('mail4filemodal' + itemId);
    if (modal) {
        modal.showModal();
		asyncupdateteilnehmereditlink(tnuid);
    }
}
function closeFileLinkModal(itemId) {
    const modal = document.getElementById('mail4filemodal' + itemId);
    if (modal) {
        modal.close();
		location.reload();
    }
}
function filelinkKopieren(uid) {
  const linklink = document.getElementById("downloadlinkforFile" + uid);
  navigator.clipboard.writeText(linklink.href)  	
    .then(() => {
	  document.getElementById("mail4externfeedback" + uid).style.display = "block"; 
      document.getElementById("mail4externfeedback" + uid).innerHTML = "Link kopiert!";
    })
    .catch(() => {
	  document.getElementById("mail4externfeedback" + uid).style.display = "block"; 
      document.getElementById("mail4externfeedback" + uid).innerHTML = "Kopieren fehlgeschlagen.";
    });
}
