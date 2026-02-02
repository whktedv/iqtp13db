/**
 * TYPO3 Extension - List Checkboxes mit AJAX
 */
(function() {
    'use strict';

	// DOM-Elemente
    let toggleCheckboxes, groupConsultationControls, addToGroupConsultationBtn;
    let selectedCountSpan, selectAllCheckbox, itemCheckboxes, checkboxColumns;
    let groupConsultSelect, listContainer, ajaxConfig;

    // AJAX URLs
    let toggleUrl, updateUrl, submitUrl, reloadUrl;
    
    // Globale Auswahl-Variable
    let globalSelectedIds = new Set();

		let selectedGroupConsult = 0;
    let auswahlmodus = 0; // Auswahlmodus
	
	/**
     * DOM-Elemente initialisieren
     */
    function initializeDomElements() {
        toggleCheckboxes = document.getElementById('toggleCheckboxes');
        groupConsultationControls = document.getElementById('groupConsultationControls');
        addToGroupConsultationBtn = document.getElementById('addToGroupConsultation');
        selectedCountSpan = document.getElementById('selectedCount');
        selectAllCheckbox = document.getElementById('selectAll');
        itemCheckboxes = document.querySelectorAll('.item-checkbox');
        checkboxColumns = document.querySelectorAll('.checkbox-column');
		groupConsultSelect = document.getElementById('gruppenberatungselect');
        listContainer = document.getElementById('listContainer');
        ajaxConfig = document.getElementById('ajaxConfig');

        // URLs
        toggleUrl = ajaxConfig?.dataset.toggleUrl;
        updateUrl = ajaxConfig?.dataset.updateUrl;
        submitUrl = ajaxConfig?.dataset.submitUrl;
        reloadUrl = ajaxConfig?.dataset.reloadUrl;
    }
	
	/**
     * Plugin-Content per AJAX neu laden
     */
    async function reloadPluginContent() {
        if (!reloadUrl) {
            console.warn('Keine Reload-URL verfügbar, lade Seite komplett neu');
            window.location.reload();
            return;
        }

        try {
            // Loading-Indikator anzeigen
            if (listContainer) {
                listContainer.style.opacity = '0.5';
                listContainer.style.pointerEvents = 'none';
                listContainer.style.transition = 'opacity 0.3s';
            }

            const response = await fetch(reloadUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const html = await response.text();
            
            // HTML parsen und nur den Plugin-Content extrahieren
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('listContainer');
            
            if (newContent && listContainer) {
                // Fade-out Animation
                listContainer.style.opacity = '0';
                
                setTimeout(() => {
                    // Content ersetzen
                    const parent = listContainer.parentNode;
                    parent.replaceChild(newContent, listContainer);
                    
                    // Neu initialisieren
                    initializeDomElements();
                    initializeSelectedIds();
                    bindEventListeners();
                    
                    // Fade-in Animation
                    newContent.style.opacity = '0';
                    newContent.style.transition = 'opacity 0.3s';
                    setTimeout(() => {
                        newContent.style.opacity = '1';
                    }, 50);
                }, 300);
                
            } else {
                console.warn('listContainer nicht gefunden, lade Seite komplett neu');
                window.location.reload();
            }

        } catch (error) {
            console.error('Fehler beim Neuladen des Contents:', error);
            
            // Fallback: Komplette Seite neu laden
            window.location.reload();
        }
    }
	
	
	
    // Initial ausgewählte IDs und Status Auswahlmodus aus Session laden
    function initializeSelectedIds() {
        try {
            const selectedIdsJson = ajaxConfig?.dataset.selectedIds;
			const togglecheckboxesActiveJson = ajaxConfig?.dataset.auswahlmodus;
            if (selectedIdsJson != 'null') {
                const selectedIds = JSON.parse(selectedIdsJson);
                globalSelectedIds = new Set(selectedIds.map(String));
            }			
			if (togglecheckboxesActiveJson != 'null') {
				auswahlmodus = togglecheckboxesActiveJson;				
			}			
        } catch (error) {
            console.error('Fehler beim Laden der gespeicherten Auswahl:', error);
            globalSelectedIds = new Set();
        }
    }
    
    // Checkboxen basierend auf globalSelectedIds aktualisieren
    function syncCheckboxesWithGlobalSelection() {
		itemCheckboxes = document.querySelectorAll('.item-checkbox');
        itemCheckboxes.forEach(checkbox => {
            const itemId = checkbox.value;
            checkbox.checked = globalSelectedIds.has(itemId);
        });
        updateSelectedCount();
		
		const isSelectionMode = auswahlmodus == 0 ? false : true; 		
		toggleCheckboxes.checked = isSelectionMode;
		toggleCheckboxVisibility(isSelectionMode);
    }

    /**
     * Checkboxen ein-/ausblenden
     */
    function toggleCheckboxVisibility(show) {
		checkboxColumns = document.querySelectorAll('.checkbox-column');
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
			if(groupConsultSelect.value != -1 && count > 0) {
				addToGroupConsultationBtn.disabled = false;
			} else {
				addToGroupConsultationBtn.disabled = true;
			}
        }
        
        // "Alle auswählen" Checkbox aktualisieren
        updateSelectAllCheckbox();
    }
    
    /**
     * "Alle auswählen" Checkbox-Status aktualisieren
     */
    function updateSelectAllCheckbox() {
        if (!selectAllCheckbox) return;
		
		itemCheckboxes = document.querySelectorAll('.item-checkbox');		
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
        notification.className = 'alert alert-' + type;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
		notification.addEventListener('click', function() {
			notification.remove();
		});
				
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 8000);
    }
	
	/**
	     * Event Listeners binden
	     */
    function bindEventListeners() {
	    // Toggle Checkboxen
	    if (toggleCheckboxes) {
	        toggleCheckboxes.addEventListener('change', async function() {
	            const show = this.checked;
	            toggleCheckboxVisibility(show);
				
				auswahlmodus = this.checked;
	
	            // Optional: AJAX-Call zum Server
	            if (toggleUrl) {
	                try {				
	                    await sendAjaxRequest(toggleUrl, { 
							show: show ? 1 : 0,
							auswahlmodus: auswahlmodus ? 1 : 0
						});
	                    
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
				updateSelectedCount();
			});
		}
		
	    // Zu Gruppenberatung hinzufügen
	    if (addToGroupConsultationBtn) {
	        addToGroupConsultationBtn.addEventListener('click', async function() {
				
				const bestätigung = confirm("Beratungsdaten der ausgewählten Nutzer werden durch die der Gruppenberatung ersetzt. Fortfahren?");
				  
				if (bestätigung) {
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
							
							// VARIANTE 2: Content per AJAX neu laden
	                        if (result.reload) {
	                            await reloadPluginContent();
	                        }
		                } else {
		                    showNotification(result.message || 'Ein Fehler ist aufgetreten.', 'danger');
		                }
		            } catch (error) {
		                showNotification('Fehler beim Verarbeiten der Anfrage.', 'danger');
		            } finally {
		                this.disabled = false;
		                this.innerHTML = '<span class="icon">✓</span> Auswahl zu Gruppenberatung';
		            }
				} else {
					showNotification('Zuweisung zu Gruppenberatung abgebrochen.', 'danger');
				}
	        });
	    }
	}
	
	/**
     * Initialisierung
     */
    function init() {
        initializeDomElements();
        initializeSelectedIds();
        syncCheckboxesWithGlobalSelection();
        bindEventListeners();
    }

    // Start
    init();

})();
            
       