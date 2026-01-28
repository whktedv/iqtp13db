/**
 * TYPO3 Extension - List Checkboxes mit AJAX
 */
(function() {
    'use strict';

    // DOM-Elemente
    const toggleCheckboxes = document.getElementById('toggleCheckboxes');
    const groupConsultationControls = document.getElementById('groupConsultationControls');
    const addToGroupConsultationBtn = document.getElementById('addToGroupConsultation');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkboxColumns = document.querySelectorAll('.checkbox-column');
    const ajaxConfig = document.getElementById('ajaxConfig');
	const groupConsultSelect = document.getElementById('gruppenberatungselect');

    // AJAX URLs
    const toggleUrl = ajaxConfig?.dataset.toggleUrl;
    const updateUrl = ajaxConfig?.dataset.updateUrl;
    const submitUrl = ajaxConfig?.dataset.submitUrl;
    
    // Globale Auswahl-Variable (über alle Seiten hinweg)
    let globalSelectedIds = new Set();
	
	let selectedGroupConsult = 0;
    
    // Initial ausgewählte IDs aus Session laden
    function initializeSelectedIds() {
        try {
            const selectedIdsJson = ajaxConfig?.dataset.selectedIds;
            if (selectedIdsJson != 'null') {
                const selectedIds = JSON.parse(selectedIdsJson);
                globalSelectedIds = new Set(selectedIds.map(String));
            }
        } catch (error) {
            console.error('Fehler beim Laden der gespeicherten Auswahl:', error);
            globalSelectedIds = new Set();
        }
    }
    
    // Checkboxen basierend auf globalSelectedIds aktualisieren
    function syncCheckboxesWithGlobalSelection() {
        itemCheckboxes.forEach(checkbox => {
            const itemId = checkbox.value;
            checkbox.checked = globalSelectedIds.has(itemId);
        });
        updateSelectedCount();
    }

    /**
     * Checkboxen ein-/ausblenden
     */
    function toggleCheckboxVisibility(show) {
        checkboxColumns.forEach(column => {
            column.style.display = show ? 'table-cell' : 'none';
        });

        groupConsultationControls.style.display = show ? 'flex' : 'none';

        if (!show) {
            // Alle Checkboxen deaktivieren
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
            updateSelectedCount();
        }
    }

    /**
     * Anzahl ausgewählter Items aktualisieren
     */
    function updateSelectedCount() {
        const count = globalSelectedIds.size;
        selectedCountSpan.textContent = `${count} ausgewählt`;
        
        // Button deaktivieren wenn nichts ausgewählt
        if (addToGroupConsultationBtn) {
            addToGroupConsultationBtn.disabled = count === 0;
        }
        
        // "Alle auswählen" Checkbox aktualisieren
        updateSelectAllCheckbox();
    }
    
    /**
     * "Alle auswählen" Checkbox-Status aktualisieren
     */
    function updateSelectAllCheckbox() {
        if (!selectAllCheckbox) return;
        
        const currentPageIds = Array.from(itemCheckboxes).map(cb => cb.value);
        const allCurrentChecked = currentPageIds.every(id => globalSelectedIds.has(id));
        const noneCurrentChecked = currentPageIds.every(id => !globalSelectedIds.has(id));
        
        selectAllCheckbox.checked = allCurrentChecked && currentPageIds.length > 0;
        selectAllCheckbox.indeterminate = !allCurrentChecked && !noneCurrentChecked;
    }

    /**
     * Alle Checkboxen auswählen/abwählen
     */
    function toggleSelectAll() {
        const isChecked = selectAllCheckbox.checked;
        
        itemCheckboxes.forEach(checkbox => {
            const itemId = checkbox.value;
            checkbox.checked = isChecked;
            
            if (isChecked) {
                globalSelectedIds.add(itemId);
            } else {
                globalSelectedIds.delete(itemId);
            }
        });
        
        updateSelectedCount();
        saveSelectionToServer();
    }

    /**
     * Ausgewählte Items sammeln
     */
    function getSelectedIds() {
        return Array.from(globalSelectedIds);
    }
    
    /**
     * Auswahl zum Server senden und in Session speichern
     */
    async function saveSelectionToServer() {
        if (!updateUrl) return;
        
        try {
            await sendAjaxRequest(updateUrl, {
                selectedIds: getSelectedIds()
            });
        } catch (error) {
            console.error('Fehler beim Speichern der Auswahl:', error);
        }
    }

    /**
     * AJAX-Request senden
     */
    async function sendAjaxRequest(url, data) {
        try {
            const formData = new URLSearchParams();
            
            for (const key in data) {
                if (Array.isArray(data[key])) {
                    data[key].forEach((value, index) => {
                        formData.append(`${key}[${index}]`, value);
                    });
                } else {
                    formData.append(key, data[key]);
                }
            }

            const response = await fetch(url, {
                method: 'POST',
				credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                const text = await response.text();
                console.error('Keine JSON-Response erhalten. Response:', text);
                throw new Error('Invalid JSON response');
            }
        } catch (error) {
            console.error('AJAX-Fehler:', error);
            throw error;
        }
    }

    /**
     * Benachrichtigung anzeigen
     */
    function showNotification(message, type = 'success') {
        // Einfache Benachrichtigung - kann durch TYPO3 Notification API ersetzt werden
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Event Listeners
     */
    
    // Toggle Checkboxen
    if (toggleCheckboxes) {
        toggleCheckboxes.addEventListener('change', async function() {
            const show = this.checked;
            toggleCheckboxVisibility(show);
            
            // Optional: AJAX-Call zum Server
            if (toggleUrl) {
                try {
                    await sendAjaxRequest(toggleUrl, { show: show ? 1 : 0 });
                    
                    // Wenn deaktiviert, globale Auswahl zurücksetzen
                    if (!show) {
                        globalSelectedIds.clear();
                        syncCheckboxesWithGlobalSelection();
                    }
                } catch (error) {
                    console.error('Toggle-Fehler:', error);
                }
            }
        });
    }

    // Alle auswählen
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', toggleSelectAll);
    }

    // Einzelne Checkboxen
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemId = this.value;
            
            if (this.checked) {
                globalSelectedIds.add(itemId);
            } else {
                globalSelectedIds.delete(itemId);
            }
            
            updateSelectedCount();
            saveSelectionToServer();
        });
    });

	if(groupConsultSelect) {
		groupConsultSelect.addEventListener('change', function() {
			selectedGroupConsult = this.value;
		});
	}
	
    // Zu Gruppenberatung hinzufügen
    if (addToGroupConsultationBtn) {
        addToGroupConsultationBtn.addEventListener('click', async function() {
            const selectedIds = getSelectedIds();
            
            if (selectedIds.length === 0) {
                showNotification('Bitte wählen Sie mindestens einen Datensatz aus.', 'warning');
                return;
            }

            // Button während der Verarbeitung deaktivieren
            this.disabled = true;
            this.textContent = 'Verarbeite...';

            try {
                const result = await sendAjaxRequest(submitUrl, {
                    selectedIds: selectedIds,
					selectedGroupConsult: selectedGroupConsult
                });

                if (result.success) {
                    showNotification(result.message, 'success');
                    
                    // Auswahl zurücksetzen
                    globalSelectedIds.clear();
                    syncCheckboxesWithGlobalSelection();
                } else {
                    showNotification(result.message || 'Ein Fehler ist aufgetreten.', 'danger');
                }
            } catch (error) {
                showNotification('Fehler beim Verarbeiten der Anfrage.', 'danger');
            } finally {
                this.disabled = false;
                this.innerHTML = '<span class="icon">✓</span> Auswahl zu Gruppenberatung';
            }
        });
    }

    // Initialisierung
    initializeSelectedIds();
    syncCheckboxesWithGlobalSelection();

})();
            
       