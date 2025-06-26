// **********  für Dokumente4Anmeldung.html  ********** 
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');

$( "#opensysfileupload" ).on( "click", function() {
  $("#fileInput").click();
});
	
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragging');
});	
dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragging');	    
});	
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragging');
    fileInput.files = e.dataTransfer.files;	    
    $("#overlay").show();	    
    document.getElementById('fileuploadform').submit();
});

fileInput.addEventListener('change', () => {			
	document.getElementById('fileuploadform').submit();
});



function openAllTableLinks() {
    // Alle Links in Tabellenzellen finden, die target="_blank" haben
    const tableLinks = document.querySelectorAll('table.doks td a[target="_blank"]');
    
    const statusElement = document.getElementById('status');
    
    // Bestätigung vom Benutzer einholen
    const confirmation = confirm(`Sollen alle ${tableLinks.length} Dokumente in neuen Tabs geöffnet werden?\n\nHinweis: Ihr Browser könnte Popup-Blocker aktiviert haben.`);
    
    if (!confirmation) {
        statusElement.textContent = 'Vorgang abgebrochen.';
        statusElement.style.color = '#ffc107';
        return;
    }
    
    let openedCount = 0;
    let failedCount = 0;
    
    // Links mit kleiner Verzögerung öffnen, um Browser-Limits zu vermeiden
    tableLinks.forEach((link, index) => {
        setTimeout(() => {
            try {
                // Link in neuem Tab öffnen
                const newWindow = window.open(link.href, '_blank');
                
                // Prüfen, ob das Fenster erfolgreich geöffnet wurde
                if (newWindow) {
                    openedCount++;
                    console.log(`Dokument ${index + 1} geöffnet: ${link.href}`);
                } else {
                    failedCount++;
                    console.warn(`Dokument ${index + 1} konnte nicht geöffnet werden (Popup-Blocker?): ${link.href}`);
                }
                
                // Status aktualisieren nach dem letzten Link
                if (index === tableLinks.length - 1) {
                    setTimeout(() => {
                        updateFinalStatus(openedCount, failedCount, tableLinks.length);
                    }, 500);
                }
                
            } catch (error) {
                failedCount++;
                console.error(`Fehler beim Öffnen von Dokument ${index + 1}:`, error);
                
                if (index === tableLinks.length - 1) {
                    setTimeout(() => {
                        updateFinalStatus(openedCount, failedCount, tableLinks.length);
                    }, 100);
                }
            }
        }, index * 100); // 100ms Verzögerung zwischen den Links
    });
    
    // Sofortiges Feedback
    statusElement.textContent = `Öffne ${tableLinks.length} Links...`;
    statusElement.style.color = '#007bff';
}

function updateFinalStatus(opened, failed, total) {
    const statusElement = document.getElementById('status');
    
    if (failed === 0) {
        statusElement.textContent = `✅ Alle ${opened} Dokumente erfolgreich geöffnet!`;
        statusElement.style.color = '#28a745';
    } else if (opened > 0) {
        statusElement.textContent = `⚠️ ${opened} Dokumente geöffnet, ${failed} fehlgeschlagen (möglicherweise durch Popup-Blocker)`;
        statusElement.style.color = '#ffc107';
    } else {
        statusElement.textContent = `❌ Keine Dokumente konnten geöffnet werden. Bitte Popup-Blocker prüfen.`;
        statusElement.style.color = '#dc3545';
    }
}

// Optional: Keyboard-Shortcut (Ctrl+Shift+O)
document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.shiftKey && event.key === 'O') {
        event.preventDefault();
        openAllTableLinks();
    }
});

// Beim Laden der Seite die Anzahl der Dokumente anzeigen
window.addEventListener('load', function() {
    const tableLinks = document.querySelectorAll('table.doks td a[target="_blank"]');
    console.log(`${tableLinks.length} Links mit target="_blank" in Tabellenzellen gefunden.`);
});
