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
    function toggleaufenthaltsstatusradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#aufenthaltsstatusradio'+i).is(":checked")) {
        		$('#divaufenthaltsstatusradio' + i).fadeIn();    			
    		} else {
    			$('#divaufenthaltsstatusradio' + i).fadeOut();	
    		}    		
    	} 
	}
    function toggleaufenthaltsstatusradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#divaufenthaltsstatusradio' + i).fadeIn();    			
    	} 
	}
    
    function toggleerwerbsstatusradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#erwerbsstatusradio'+i).is(":checked")) {
        		$('#diverwerbsstatusradio' + i).fadeIn();    			
    		} else {
    			$('#diverwerbsstatusradio' + i).fadeOut();	
    		}    		
    	} 
	}
    function toggleerwerbsstatusradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#diverwerbsstatusradio' + i).fadeIn();    			
    	} 
	}
    
    function toggleberatungsstelleradio() {
    	for (var i = 0; i < 17; i++) {
    		if($('#beratungsstelleradio'+i).is(":checked")) {
        		$('#divberatungsstelleradio' + i).fadeIn();    			
    		} else {
    			$('#divberatungsstelleradio' + i).fadeOut();	
    		}    		
    	} 
	}
    function toggleberatungsstelleradioall() {
    	for (i = 0; i < 17; i++) {
       		$('#divberatungsstelleradio' + i).fadeIn();    			
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
    
} ) ( jQuery );
