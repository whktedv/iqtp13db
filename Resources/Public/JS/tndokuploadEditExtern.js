
// **********  für Editexternmenu.html  ********** 

const dropzone1 = document.getElementById('dropzone1');
const fileInput1 = document.getElementById('fileInput1');

$( "#opensysfileupload1" ).on( "click", function() {
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
	document.getElementById('fileuploadform1').submit();
});

