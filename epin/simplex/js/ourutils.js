function OurgoBack() {
	if (window.history.length <= 1)
	 window.close();
	else
	 window.history.go(-1);
}

function reloadIt() { 
  opener.location = 'edit_gallery.php?action=list'; 
} 

function closeThis(){ 
  window.close() 
} 

function doIt() { 
  openIt(); 
  setTimeout('closeThis();',1000); 
}
