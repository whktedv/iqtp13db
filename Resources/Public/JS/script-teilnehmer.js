( function($) {
    $(document).ready(function() {
    	
    	toggle_anmeld_checkwohnsitz();
		
		toggleschonberaten();
    	togglecheckwohnsitz();		
    	togglecheckdeutschkenntnisse();
    	
		toggleeinwanerkennendestelle();
		toggleeinwperson();
		toggleselectreferenzberuf();
		
		toggleabschluss(0);
		toggleabschluss(1);
		toggleabschluss(2);
		toggleabschluss(3);
				
		$('#chooselang').click(function() {
			togglelangmenu();
		}); 
		
		$('#anmeld-checkwohnsitzDeutschland1').change(function() {
			toggle_anmeld_checkwohnsitz();
		}); 
		$('#anmeld-checkwohnsitzDeutschland2').change(function() {
			toggle_anmeld_checkwohnsitz();
		});
		
		$('#selectreferenzberuf').change(function() {
			toggleselectreferenzberuf();
		});
		
		$('#opteinwAnerkstelle1').change(function() {
			toggleeinwanerkennendestelle();
		});
		$('#opteinwAnerkstelle2').change(function() {
			toggleeinwanerkennendestelle();
		});
		
		$('#opteinwPerson1').change(function() {
			toggleeinwperson();
		});
		$('#opteinwPerson2').change(function() {
			toggleeinwperson();
		});
		
	
    	$('#schonberaten1').change(function() {
			toggleschonberaten();
		});    	
		$('#schonberaten2').change(function() {
			toggleschonberaten();
		});  
    	
		$('#checkwohnsitzDeutschland0').change(function() {
			togglecheckwohnsitz();
		});    	
		$('#checkwohnsitzDeutschland1').change(function() {
			togglecheckwohnsitz();
		}); 
		$('#checkwohnsitzDeutschland2').change(function() {
			togglecheckwohnsitz();
		}); 
		
		$('#checkdeutschkenntnisse0').change(function() {
			togglecheckdeutschkenntnisse();
		});
		$('#checkdeutschkenntnisse1').change(function() {
			togglecheckdeutschkenntnisse();
		});    	
		$('#checkdeutschkenntnisse2').change(function() {
			togglecheckdeutschkenntnisse();
		}); 
		
    	// Seite 3
    	toggleleistungsbezugjanein();
    	
		$('#optleistungsbezugjanein1').change(function() {
			toggleleistungsbezugjanein();
		});    	
		$('#optleistungsbezugjanein2').change(function() {
			toggleleistungsbezugjanein();
		}); 
						
		// **********+ für Backend *********		
		for (i = 0; i < 17; i++) {
			$('#aufenthaltsstatusradio'+i).change(function() {
				toggleaufenthaltsstatusradio();
			});    			
    	} 
		toggleaufenthaltsstatusradio();
		$('#toggleallaufenthaltsstatus').click(function() {
			toggleaufenthaltsstatusradioall();
		}); 
		
		
		for (i = 0; i < 8; i++) {
			$('#erwerbsstatusradio'+i).change(function() {
				toggleerwerbsstatusradio();
			});    			
    	} 
		toggleerwerbsstatusradio();
		$('#toggleallerwerbsstatus').click(function() {
			toggleerwerbsstatusradioall();
		}); 
		
		
		for (i = 0; i < 13; i++) {
			$('#beratungsstelleradio'+i).change(function() {
				toggleberatungsstelleradio();
			});    			
    	} 
		toggleberatungsstelleradio();
		$('#toggleallberatungsstelle').click(function() {
			toggleberatungsstelleradioall();
		}); 
		
		// Erstberatung
		toggleberatungface2face();		
		$('#chkberatungsartface2face').click(function() {
			toggleberatungface2face();
		});
		
		toggleantragstellungvorher();
		$('#selectantragstellungvorher').change(function() {
			toggleantragstellungvorher();
		});
		
		toggleantragstellung();
		$('#selectantragstellungerfolgt').change(function() {
			toggleantragstellung();
		});
		
		$('#toggleabschluss0').click(function() {
			toggleabschluss(0);
		});
		$('#toggleabschluss1').click(function() {
			toggleabschluss(1);
		});
		$('#toggleabschluss2').click(function() {
			toggleabschluss(2);
		});
		$('#toggleabschluss3').click(function() {
			toggleabschluss(3);
		});  
			

   });
    
	function toggle_anmeld_checkwohnsitz() {
    	if($('#anmeld-checkwohnsitzDeutschland1').is(":checked")) {
			$('#divanmeldplz').fadeIn();
			$('#anmeldplz').attr("required", true);
			return;
		} else {
			 $('#divanmeldplz').fadeOut();
			 $('#anmeldplz').attr("required", false);
			 $('#anmeldplz').val('');
			return;
		}
	}
	
    function toggleschonberaten() {
    	if($('#schonberaten1').is(":checked")) {
			$('#divschonberatenvon').fadeIn(); 
			return;
		} else if($('#schonberaten2').is(":checked")) {
			 $('#divschonberatenvon').fadeOut();
			 $('#txtschonberatenvon').val('');
			return;
		} else {
			$('#divschonberatenvon').fadeOut();
		}
	}
    
    
    function togglecheckwohnsitz() {
    	if($('#checkwohnsitzDeutschland2').is(":checked")) {
			$('#labelwohnsitzNeinIn').fadeIn();
			$('#inputwohnsitzNeinIn').fadeIn();
			$('#labeleinreisejahr').fadeOut();
			$('#inputeinreisejahr').fadeOut();
			$('#inputeinreisejahr').val('');
			return;
		} else if($('#checkwohnsitzDeutschland1').is(":checked")) {
			 $('#labelwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').val('');
			 $('#labeleinreisejahr').fadeIn();
			 $('#inputeinreisejahr').fadeIn();
			return;
		} else {
			 $('#labelwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').val('');
			 $('#labeleinreisejahr').fadeOut();
			 $('#inputeinreisejahr').fadeOut();
			 $('#inputeinreisejahr').val('');
		}
	}
    
    function togglecheckdeutschkenntnisse() {
    	if($('#checkdeutschkenntnisse1').is(":checked")) {
			$('#labelzertsprachniveau').fadeOut();
			$('#selectzertsprachniveau').fadeOut();
			$('#selectzertsprachniveau').val('1');
			return;
		} else if($('#checkdeutschkenntnisse2').is(":checked")) {
			$('#labelzertsprachniveau').fadeIn();
			$('#selectzertsprachniveau').fadeIn();
			return;
		} else {
			$('#labelzertsprachniveau').fadeOut();
			$('#selectzertsprachniveau').fadeOut();
			$('#selectzertsprachniveau').val('1');
		}	
    }
    
    function toggleleistungsbezugjanein() {
    	if($('#optleistungsbezugjanein1').is(":checked")) {
			$('#divleistungsbezug').fadeIn();
			$('#datenAA').fadeIn();
			return;
		} else if($('#optleistungsbezugjanein2').is(":checked")) {
			 $('#divleistungsbezug').fadeOut();
			 $('#datenAA').fadeOut();			 
			return;
		} else {
			$('#divleistungsbezug').fadeOut();
			$('#datenAA').fadeOut();
		}
	}   
    
// für BACKEND

	function toggleselectreferenzberuf() {
		if($('#selectreferenzberuf').val() == '-4') {
			$('#divsonstigerberuf').fadeOut();
			$('#txtsonstigerberuf').val('');
			$('#divnregberuf').fadeIn();
			return;
		} else if($('#selectreferenzberuf').val() == '-3') {
			$('#divnregberuf').fadeOut();
			$('#txtnregberuf').val('');
			$('#divsonstigerberuf').fadeIn();
			return;
		} else {
			$('#divsonstigerberuf').fadeOut();
			$('#divnregberuf').fadeOut();
			$('#txtsonstigerberuf').val('');
			$('#txtnregberuf').val('');			
		}
	}


	function toggleeinwanerkennendestelle() {		
		if($('#opteinwAnerkstelle1').is(":checked")) {
			$('#einwanerkenndatum').fadeIn();
			$('#einwanerkennmedium').fadeIn();
			$('#einwanerkennname').fadeIn();
			$('#einwanerkennkontakt').fadeIn();
		} else {
			$('#einwanerkenndatum').fadeOut();
			$('#einwanerkennmedium').fadeOut();
			$('#einwanerkennname').fadeOut();
			$('#einwanerkennkontakt').fadeOut();
			$('#txteinwanerkenndatum').val('');
			$('#chkeinwanerkennmedium1').prop("checked", false);
			$('#chkeinwanerkennmedium2').prop("checked", false);
			$('#chkeinwanerkennmedium3').prop("checked", false);
			$('#chkeinwanerkennmedium4').prop("checked", false);
			$('#chkeinwanerkennmedium5').prop("checked", false);
			$('#txteinwanerkennname').val('');
			$('#txteinwanerkennkontakt').val('');			
		}
		return;
	}
	
	function toggleeinwperson() {		
		if($('#opteinwPerson1').is(":checked")) {
			$('#einwpersondatum').fadeIn();
			$('#einwpersonmedium').fadeIn();
			$('#einwpersonname').fadeIn();
			$('#einwpersonkontakt').fadeIn();
		} else {
			$('#einwpersondatum').fadeOut();
			$('#einwpersonmedium').fadeOut();
			$('#einwpersonname').fadeOut();
			$('#einwpersonkontakt').fadeOut();
			$('#txteinwpersondatum').val('');
			$('#chkeinwpersonmedium1').prop("checked", false);
			$('#chkeinwpersonmedium2').prop("checked", false);
			$('#chkeinwpersonmedium3').prop("checked", false);
			$('#chkeinwpersonmedium4').prop("checked", false);
			$('#chkeinwpersonmedium5').prop("checked", false);
			$('#txteinwpersonname').val('');
			$('#txteinwpersonkontakt').val('');			
		}
		return;
	}



    // Aufenthaltsstatus
    function toggleaufenthaltsstatusradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#aufenthaltsstatusradio'+i).is(":checked")) {
        		$('#divaufenthaltsstatusradio' + i).show();    			
    		} else {
    			$('#divaufenthaltsstatusradio' + i).toggle();	
    		}    		
    	} 
	}
    function toggleaufenthaltsstatusradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#divaufenthaltsstatusradio' + i).toggle();
       		if($('#aufenthaltsstatusradio'+i).is(":checked")) {
        		$('#divaufenthaltsstatusradio' + i).show();    			
    		}
    	} 
	}
    
    // Erwerbsstatus
    function toggleerwerbsstatusradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#erwerbsstatusradio'+i).is(":checked")) {
        		$('#diverwerbsstatusradio' + i).show();    			
    		} else {
    			$('#diverwerbsstatusradio' + i).toggle();	
    		}    		
    	} 
	}
    function toggleerwerbsstatusradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#diverwerbsstatusradio' + i).toggle();
       		if($('#erwerbsstatusradio'+i).is(":checked")) {
        		$('#diverwerbsstatusradio' + i).show();    			
    		}
    	} 
	}
    
    // Beratungsstelle
    function toggleberatungsstelleradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#beratungsstelleradio'+i).is(":checked")) {
        		$('#divberatungsstelleradio' + i).show();    			
    		} else {
    			$('#divberatungsstelleradio' + i).toggle();	
    		}    		
    	} 
	}
    function toggleberatungsstelleradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#divberatungsstelleradio' + i).toggle();
       		if($('#beratungsstelleradio' + i).is(":checked")) {
        		$('#divberatungsstelleradio' + i).show();    			
    		}
    	} 
	}
    			
    // Erstberatung
    function toggleberatungface2face() {
    	if($('#chkberatungsartface2face').is(":checked")) {
    		$('#textareaberatungsartfreitext').show(); 
			$('#textfieldberatungsort').show(); 
		} else {
			$('#textareaberatungsartfreitext').toggle();
			$('#textfieldberatungsort').toggle();
		}
	} 
	
	// Abschluesse
    function toggleabschluss(i) {
		$('#abschlussdaten' + i).fadeToggle();	    
    }
    
    // Anerkennung vorher?
    function toggleantragstellungvorher() {    	
    	if($('#selectantragstellungvorher').val() == 1) {
			$("#divantragstellunggwpvorher").show(); 
			$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellungzabvorher]']").val(0);			
    		$("#divantragstellungzabvorher").hide(); 
		} else if($('#selectantragstellungvorher').val() == 2) {
    		$("#divantragstellungzabvorher").show();
    		$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellunggwpvorher]']").val(0);			
    		$("#divantragstellunggwpvorher").hide(); 
		} else if($('#selectantragstellungvorher').val() == 3) {
			$("#divantragstellunggwpvorher").show(); 
    		$("#divantragstellungzabvorher").show();
		} else {
			$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellungzabvorher]']").val(0);
			$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellunggwpvorher]']").val(0);
    		$("#divantragstellunggwpvorher").hide(); 
    		$("#divantragstellungzabvorher").hide(); 
		}
	} 
    
    // Anerkennungsverfahren
    function toggleantragstellung() {
    	if($('#selectantragstellungerfolgt').val() == 0) {
    		$("#divantragstellunggwpdatum").hide(); 
    		$("#divantragstellunggwpergebnis").hide(); 
    		$("#divantragstellungzabdatum").hide(); 
    		$("#divantragstellungzabergebnis").hide(); 
		}
    	if($('#selectantragstellungerfolgt').val() == 1) {
    		$("#divantragstellunggwpdatum").show(); 
			$("#divantragstellunggwpergebnis").show(); 
			$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellungzabdatum]']").val('');
			$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellungzabergebnis]']").val(0);			
			$("#divantragstellungzabdatum").hide(); 
    		$("#divantragstellungzabergebnis").hide(); 
		} 
    	if($('#selectantragstellungerfolgt').val() == 2) {
    		$("#divantragstellungzabdatum").show();
    		$("#divantragstellungzabergebnis").show();
    		$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellunggwpdatum]']").val('');
    		$("[name='tx_iqtp13db_iqtp13dbadmin[abschluss][antragstellunggwpergebnis]']").val(0);			
    		$("#divantragstellunggwpdatum").hide(); 
    		$("#divantragstellunggwpergebnis").hide(); 
		}
    	if($('#selectantragstellungerfolgt').val() == 3) {
    		$("#divantragstellunggwpdatum").show(); 
			$("#divantragstellunggwpergebnis").show(); 
			$("#divantragstellungzabdatum").show();
    		$("#divantragstellungzabergebnis").show();
		}
	} 
	
	function togglelangmenu() {
		$('#language_menu').fadeToggle();
	}
    
} ) ( jQuery );
