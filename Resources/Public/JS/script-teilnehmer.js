( function($) {
    $(document).ready(function() {

    	togglecheckwohnsitz();
		togglechecksprachniveau();
		togglechecksprachegespraech();
    	togglecheckantrag();
		   
		$('#checkwohnsitzDeutschland0').change(function() {
			togglecheckwohnsitz();
		});    	
		$('#checkwohnsitzDeutschland1').change(function() {
			togglecheckwohnsitz();
		});    	

		$('#checkzertifikatdeutsch').change(function() {
			togglechecksprachniveau();
		});
		
		$('#checkberatungsgespraechDeutsch').change(function() {
			togglechecksprachegespraech();
		}); 

		$('#checkboxfruehererAntrag').change(function() {
			togglecheckantrag();
		});    	
		
		

   });
    
    
    function togglecheckwohnsitz() {
    	if($('#checkwohnsitzDeutschland0').is(":checked")) {
    		$('#labelinputwohnsitzJaBundesland').fadeOut();
			$('#inputwohnsitzJaBundesland').fadeOut();
			$('#labelwohnsitzNeinIn').fadeIn();
			$('#inputwohnsitzNeinIn').fadeIn();
			$('#inputwohnsitzJaBundesland').val('');  
			return;
		}
		if($('#checkwohnsitzDeutschland1').is(":checked")) {
			 $('#labelinputwohnsitzJaBundesland').fadeIn();
			 $('#inputwohnsitzJaBundesland').fadeIn();
			 $('#labelwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').fadeOut();
			 $('#inputwohnsitzNeinIn').val('');
			return;
		}
	}
    
    function togglechecksprachniveau() {
		if($('#checkzertifikatdeutsch').is(":checked")) {
			   $('#inputzertsprachniveau').fadeIn();
			   $('#labelzertsprachniveau').fadeIn();
			   return;
		}
		   
		$('#inputzertsprachniveau').val('');
		
		$('#inputzertsprachniveau').fadeOut();
		$('#labelzertsprachniveau').fadeOut();
	}

    function togglechecksprachegespraech() {
		if($('#checkberatungsgespraechDeutsch').is(":checked")) {
			   $('#inputberatungsgespraechSprache').fadeOut();
			   $('#labelberatungsgespraechSprache').fadeOut();
			   return;
		}
		   
		$('#inputberatungsgespraechSprache').val('');
		
		$('#inputberatungsgespraechSprache').fadeIn();
		$('#labelberatungsgespraechSprache').fadeIn();
	}
    
    

	    
    function togglecheckantrag() {
		if($('#checkboxfruehererAntrag').is(":checked")) {
			   $('#inputfruehererAntragReferenzberuf').fadeIn();
			   $('#inputfruehererAntragInstitution').fadeIn();
			   $('#labelfruehererAntragReferenzberuf').fadeIn();
			   $('#labelfruehererAntragInstitution').fadeIn();
			   return;
		}
		   
		$('#inputfruehererAntragReferenzberuf').val('');
		$('#inputfruehererAntragInstitution').val('');
		
		$('#inputfruehererAntragReferenzberuf').fadeOut();
		$('#inputfruehererAntragInstitution').fadeOut();
		$('#labelfruehererAntragReferenzberuf').fadeOut();
		$('#labelfruehererAntragInstitution').fadeOut();
	}


    
} ) ( jQuery );
