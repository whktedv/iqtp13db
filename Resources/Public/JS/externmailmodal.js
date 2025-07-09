// JavaScript für Modal-Funktionalität

function openEmailModal(itemId) {
    const modal = document.getElementById('mail4externmodal' + itemId);
    if (modal) {
        modal.showModal();
        // Focus auf das Textarea setzen
        const textarea = document.getElementById('emailBody_' + itemId);
        if (textarea) {
            textarea.focus();
        }
    }
}

function closeEmailModal(itemId) {
    const modal = document.getElementById('mail4externmodal' + itemId);
    if (modal) {
        modal.close();
        // Formular zurücksetzen
        const form = document.getElementById('emailForm_' + itemId);
        if (form) {
            form.reset();
        }
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