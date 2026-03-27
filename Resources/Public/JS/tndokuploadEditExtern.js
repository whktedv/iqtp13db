
// **********  für Editexternmenu.html  ********** 

const dropzone1 = document.getElementById('dropzone1');
const fileInput1 = document.getElementById('fileInput1');

function asyncdescriptionblur(elem) {
	let num = elem.getAttribute("data-num");									
	asyncupdatedokdescription(elem, 1, document.getElementById('dokid' + num));
}

// Event-Listener für alle Input-Felder (auch dynamisch hinzugefügte)
document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
        event.preventDefault(); // Verhindert das Standard-Verhalten
        
        // Welches Input-Feld hat das Event ausgelöst?
        const triggeredInput = event.target;
		asyncdescriptionblur(triggeredInput);
    }
});

$( "#opensysfileupload1" ).on( "click", function() {
  $("#fileInput1").click();
});

$( "#dropzoneimg" ).on( "click", function() {
  $("#fileInput").click();
});
dropzone1.addEventListener('click', (e) => {
	$("#fileInput1").click();    
});

dropzone1.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone1.classList.add('dragging');
});	
dropzone1.addEventListener('dragleave', () => {
    dropzone1.classList.remove('dragging');	    
});	
dropzone1.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone1.classList.remove('dragging');
	
	fileInput1.files = e.dataTransfer.files;	    
    $("#overlay").show();
    document.getElementById('fileuploadform1').submit();	
});

fileInput1.addEventListener('change', () => {	
    $("#overlay").show();		
	document.getElementById('fileuploadform1').submit();
});

$( "#response" ).on( "click", function() {
  document.getElementById('response').innerHTML = '';
});
