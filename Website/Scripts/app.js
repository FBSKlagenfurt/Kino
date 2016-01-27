
function init(){

	$.ajax({
		type:"get",
		dataType: "json",
		url:"funktion.php",			
		data: {				
			KiNa:  $("#KiNa")[0].value
		},
		success: function(data){								
			befuelleKinosaal(data);
		}
	});	

	$( "#KiNa" ).change(function() {		
		$.ajax({
			type:"get",
			dataType: "json",
			url:"funktion.php",			
			data: {				
				KiNa:  $("#KiNa")[0].value
			},
			success: function(data){				
				console.dir(data);		
				befuelleKinosaal(data);
			}
		})	  	
	});

	$( "#FiNa" ).change(function() {		
		$.ajax({
			type:"get",
			dataType: "json",
			url:"funktion.php",			
			data: {				
				FiNa:  $("#FiNa")[0].value
			},
			success: function(data){				
				console.dir(data);		
				befuelleFilmdauer(data);
			}
		})	  	
	});
}

function befuelleKinosaal(data){	
	$('#Saalname').empty();	
	var option = "";

	for(var i = 0; i<data.length; i++){		
		option = "<option value="+data[i].id+">"+data[i].Saalname+"</option>"
		$('#Saalname').append(option);	
	}
}

function befuelleFilmdauer(data){	
	$('#Dauer').empty();	
	var option = "";

	for(var i = 0; i<data.length; i++){		
		option = "<option value="+data[i].id+">"+data[i].Dauer+"</option>"
		$('#Dauer').append(option);	
	}
}