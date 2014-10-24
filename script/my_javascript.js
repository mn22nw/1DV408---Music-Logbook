//http://css-tricks.com/snippets/javascript/saving-contenteditable-content-changes-as-json-with-ajax/

document.addEventListener('keydown', function (event) {
  var esc = event.which == 27,
      nl = event.which == 13,
      el = event.target,
      input = el.nodeName != 'INPUT' && el.nodeName != 'TEXTAREA',
      data = {};

  if (input) {
    if (esc) {
      // restore state
      document.execCommand('undo', false, null);  
      el.blur();
    } else if (nl) {
      
     
      // save
       var textarea = document.querySelector("#notes"); // element, text
       
		$.ajax({
			type: 'post',                    
			url: "/MusicLogbook/?action=saveNotes",            
			data:{"texcontent" : textarea.innerHTML},
			dataType:'text',                
			success: function(rs)
				{
				    console.log("den borde lagt till!" + rs);	
				  
				   
				    textarea.innerHTML = rs;
				     textarea.innerHTML +="<br />";
				     var cursorPosition = $('#notes').prop("selectionStart");
				              
				 },
			error: function(result) {
		           	console.log("Error saving to the database!");
		     	}
		 });  		
				


      log(JSON.stringify(data));

      el.blur();
      event.preventDefault();
    }
  }
}, true);

function log(s) {
  document.getElementById('debug').innerHTML = 'value changed to: ' + s;
}