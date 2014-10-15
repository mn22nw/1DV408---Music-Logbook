 $(document).ready(function(){
 	
		$('input[name=mainInstrument]').change(function(){  //TODO-fixa strängberoende
		     $('form').submit();
		
		});
		
		/*
		 $(function() {
		    var title = $('.folderTitle');
		    console.log(title);
		    var fontSize = parseInt(title.css('font-size'));
		    
		    do {
		        fontSize--;
		        title.css('font-size', fontSize.toString() + 'px');
		    } while (title.width() >= 90);
		});
		 */
});

