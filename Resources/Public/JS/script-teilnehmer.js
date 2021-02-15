( function($) {
    $(document).ready(function() {
    	
    	// Seite 2
    	toggleschonberaten();
    	togglecheckwohnsitz();
    	
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
		
    	// Seite 3
    	toggleleistungsbezugjanein();
    	toggleeinwdatenaa();
    	togglefruehererantrag();
    	togglefruehererantragupload();
		
		$('#optleistungsbezugjanein1').change(function() {
			toggleleistungsbezugjanein();
		});    	
		$('#optleistungsbezugjanein2').change(function() {
			toggleleistungsbezugjanein();
		}); 
		
		$('#opteinwilligungdatenanAA1').change(function() {
			toggleeinwdatenaa();
		});    	
		$('#opteinwilligungdatenanAA2').change(function() {
			toggleeinwdatenaa();
		});
				
		$('#optfruehererAntrag1').change(function() {
			togglefruehererantrag();
		});    	
		$('#optfruehererAntrag2').change(function() {
			togglefruehererantrag();
		}); 
		
		$('#optbescheidfruehererAnerkennungsantrag1').change(function() {
			togglefruehererantragupload();
		});    	
		$('#optbescheidfruehererAnerkennungsantrag2').change(function() {
			togglefruehererantragupload();
		}); 
		
		// für Backend		
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
		
		// Folgekontakt
		toggleantraggestellt();
		
		$('#optantraggestellt1').change(function() {
			toggleantraggestellt();
		});    	
		$('#optantraggestellt2').change(function() {
			toggleantraggestellt();
		}); 

   });
    
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
    	if($('#checkwohnsitzDeutschland0').is(":checked")) {
			$('#labelwohnsitzNeinIn').fadeIn();
			$('#inputwohnsitzNeinIn').fadeIn();
			return;
		} else if($('#checkwohnsitzDeutschland1').is(":checked")) {
			 $('#labelwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').val('');
			return;
		} else {
			 $('#labelwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').val('');
		}
	}
    
    function toggleleistungsbezugjanein() {
    	if($('#optleistungsbezugjanein1').is(":checked")) {
			$('#divleistungsbezug').fadeIn();
			$('#einwdatenanAA').fadeIn();			
			return;
		} else if($('#optleistungsbezugjanein2').is(":checked")) {
			 $('#divleistungsbezug').fadeOut();
			 $('#einwdatenanAA').fadeOut();
			 $('#opteinwilligungdatenanAA2').prop("checked", true);
			 toggleeinwdatenaa();
			return;
		} else {
			$('#divleistungsbezug').fadeOut();
			$('#einwdatenanAA').fadeOut();
		}
	}
    
    function toggleeinwdatenaa() {
    	if($('#opteinwilligungdatenanAA1').is(":checked")) {
			$('#datenAA').fadeIn();
			return;
		} else if($('#opteinwilligungdatenanAA2').is(":checked")) {
			 $('#datenAA').fadeOut();
			return;
		} else {
			$('#datenAA').fadeOut();
		}
	}
    
    function togglefruehererantrag() {
    	if($('#optfruehererAntrag1').is(":checked")) {
			$('#fruehererantragdaten1').fadeIn();
			$('#fruehererantragdaten2').fadeIn();
			$('#fruehererantragdaten3').fadeIn();
			return;
		} else if($('#optfruehererAntrag2').is(":checked")) {
			 $('#fruehererantragdaten1').fadeOut();
			 $('#fruehererantragdaten2').fadeOut();
			 $('#fruehererantragdaten3').fadeOut();
			return;
		} else {
			$('#fruehererantragdaten1').fadeOut();
			$('#fruehererantragdaten2').fadeOut();
			$('#fruehererantragdaten3').fadeOut();
		}
	}
    
    function togglefruehererantragupload() {
    	if($('#optbescheidfruehererAnerkennungsantrag1').is(":checked")) {
			$('#textdokupload').fadeIn();
			return;
		} else if($('#optbescheidfruehererAnerkennungsantrag2').is(":checked")) {
			 $('#textdokupload').fadeOut();
			return;
		} else {
			$('#textdokupload').fadeOut();
		}
	}
    
// für BACKEND
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
    
    // Folgekontakt
    function toggleantraggestellt() {
    	if($('#optantraggestellt1').is(":checked")) {
			$('#divzabgleichwertigkeit').fadeIn(); 
			return;
		} else if($('#optantraggestellt2').is(":checked")) {
			 $('#divzabgleichwertigkeit').fadeOut();
			return;
		} else {
			$('#divzabgleichwertigkeit').fadeOut();
		}
	}
    
    $("input[name='tx_iqtp13db_iqtp13dbwebapp[file]']").change(function() { 
    	this.form.submit(); 
    	$("#overlay").show();
    });
    
    $("body").prepend('<div id="overlay" class="ui-widget-overlay" style="z-index: 1001; display: none;"><div style="width:500px; margin:300px auto;"><img width="300" height="300" src="/typo3conf/ext/iqtp13db/Resources/Public/Icons/giphy.gif"/></div></div>');
    
    
} ) ( jQuery );
