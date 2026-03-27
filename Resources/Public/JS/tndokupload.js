
// **********  für Editexternmenu.html  ********** 

const dropzone1 = document.getElementById('dropzone1');
const dropzone2 = document.getElementById('dropzone2');
const dropzone3 = document.getElementById('dropzone3');
const dropzone4 = document.getElementById('dropzone4');
const fileInput1 = document.getElementById('fileInput1');
const fileInput2 = document.getElementById('fileInput2');	
const fileInput3 = document.getElementById('fileInput3');
const fileInput4 = document.getElementById('fileInput4');

$( "#opensysfileupload1" ).on( "click", function() {
  $("#fileInput1").click();
});
$( "#opensysfileupload2" ).on( "click", function() {
  $("#fileInput2").click();
});
$( "#opensysfileupload3" ).on( "click", function() {
  $("#fileInput3").click();
});
$( "#opensysfileupload4" ).on( "click", function() {
  $("#fileInput4").click();
});
$( "#dropzoneimg" ).on( "click", function() {
  $("#fileInput").click();
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

dropzone2.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone2.classList.add('dragging');
});	
dropzone2.addEventListener('dragleave', () => {
    dropzone2.classList.remove('dragging');	    
});	
dropzone2.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone2.classList.remove('dragging');
    fileInput2.files = e.dataTransfer.files;	    
    $("#overlay").show();	    
    document.getElementById('fileuploadform2').submit();
});

dropzone3.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone3.classList.add('dragging');
});	
dropzone3.addEventListener('dragleave', () => {
    dropzone3.classList.remove('dragging');	    
});	
dropzone3.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone3.classList.remove('dragging');
    fileInput3.files = e.dataTransfer.files;	    
    $("#overlay").show();
    document.getElementById('fileuploadform3').submit();
});

dropzone4.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone4.classList.add('dragging');
});	
dropzone4.addEventListener('dragleave', () => {
    dropzone4.classList.remove('dragging');	    
});	
dropzone4.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone4.classList.remove('dragging');
    fileInput4.files = e.dataTransfer.files;	    
    $("#overlay").show();	    
    document.getElementById('fileuploadform4').submit();
});

fileInput1.addEventListener('change', () => {			    
	document.getElementById('fileuploadform1').submit();
});
fileInput2.addEventListener('change', () => {			
	document.getElementById('fileuploadform2').submit();
});
fileInput3.addEventListener('change', () => {
	document.getElementById('fileuploadform3').submit();
});
fileInput4.addEventListener('change', () => {
	document.getElementById('fileuploadform4').submit();
});

