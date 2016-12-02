$(document).ready(function(){
// 	document.getElementById('memberS').addEventListener("change",overrideSelect);
	$("#google_btn").hover(function(){
    	$("#google_btn").attr('src',"img/btn_google_signin_light_web.png");
    }, function(){
    	$("#google_btn").attr('src',"img/btn_google_signin_dark_web.png");
	});

	$("#tuckshopLink").click(function() {
		$("#tuckshop_alert").show();
	});
});

function confirmDel(index) {
	var table = document.getElementById("tableOrg");
	var pos = $(index).closest('tr').index();
	
	var confirmation = confirm("Are you sure you really want to delete the Organization named '" + $(table.rows.item(pos + 1).cells[0]).text() + "'? Deleting an organization deletes all the data associated with it. This move cannot be reversed.");
	
	if(confirmation == true){
		var validation = prompt("Please confirm your decision by entering the name of your Organization. ");
		
		if(validation == $(table.rows.item(pos + 1).cells[0]).text()){
			return true;
		}
		event.preventDefault();
		return false;
	} else {
		event.preventDefault();
		return false;
	}
}

function confirmRoleChange(index) {
	var table = document.getElementById("tableEmployee");
	var user_id = $(index).parent().find('input[name="user_id"]').val();
	if(window.location.hostname == "localhost")
		var website_url = window.location.hostname + ":8000";
	else
		var website_url = window.location.hostname;

	$.ajax({
		url: "/employees/update/" + user_id,
		type: 'POST',
		crossDomain : true,
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
        data: {
        	"role" : $(index).val()
        }, success: function(result) {
        	console.log(result);
		}, error: function(XMLHttpRequest, textStatus, errorThrown) {
			if (XMLHttpRequest.readyState == 4) { // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
				console.log("state 4");
				console.log(XMLHttpRequest.status);
			} else if (XMLHttpRequest.readyState == 0) { // Network error (i.e. connection refused, access denied due to CORS, etc.)
				console.log("Offline");
			} else { // something weird is happening
				console.log("state weird");
			}
        }
	});
}

function confirmDelEmp(index) {
	var table = document.getElementById("tableEmployee");
	var pos = $(index).closest('tr').index();

	var confirmation = confirm("Are you sure you really want to delete this employee named '" + $(table.rows.item(pos + 1).cells[0]).text() + "'? Deleting this employee's details will delete all the data associated with it. This move cannot be reversed.");
	if(confirmation == true){
		/*var validation = prompt("Please confirm your decision by entering the name of the employee. ");
		
		if(validation == $(table.rows.item(pos + 1).cells[0]).text()){
			return true;
		}
		event.preventDefault();
		return false;*/
		return true;
	} else {
		event.preventDefault();
		return false;
	}
}

function addTableRow(){ // adds new row & 5 columns to the table
	var table = document.getElementById("newTimeTable");

	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount - 1);

	var colCount = table.rows[0].cells.length;

	for(var i = 0; i < colCount; i++) {
		var newcell	= row.insertCell(i);// insert cell
		newcell.innerHTML = table.rows[0].cells[i].innerHTML;//create new row
	}

	var optionSize = $(table.rows.item(rowCount - 2).cells[0]).find('select').children('option').size();
	for (var j = 0; j < rowCount - 1 ; j++){// to track table rows
		for (var i = 1; i < optionSize ; i++){// to track options
			// compares if any of the above dropdowns have chosen value, if so, then hide
			if($(table.rows.item(rowCount - 2).cells[0]).find('select').children('option')[i].text == $(table.rows.item(j).cells[0]).find('select').children(':selected').text()){
				$(table.rows.item(rowCount - 1).cells[0]).find('select').children('option')[i].style.display="none";
			}
		}
	}

	if(optionSize - 1 == rowCount)
		document.getElementById('addbtn').disabled = true;
}

function overrideSelect(indexX){ // gets the value from the dropdown & hides that option in other dropdowns
	var x = $(indexX).closest('tr').index();
	
	var table = document.getElementById("newTimeTable");
	var tableSize = $("#newTimeTable > tbody > tr").size();
	var index = indexX.selectedIndex;

	for (var i = 0; i < tableSize - 1 ; i++){
		if(i != x){
			$(table.rows.item(i).cells[0]).find('select').children('option')[index].style.display="none";
		}
	}
}

function deleteTableRow(index){ // deletes the row that is selected by user
	try {
		var table = document.getElementById("newTimeTable");
		var rowCount = table.rows.length;
		var optionSize = $(table.rows.item(rowCount - 2).cells[0]).find('select').children('option').size();
		var pos = $(index).closest('tr').index();
		if(optionSize > rowCount - 1)
			document.getElementById('addbtn').disabled = false;
			


		for (var j = 0; j < rowCount - 1 ; j++){// to track table rows
			for (var i = 1; i < optionSize ; i++){// to track options
			// compares if any of the above dropdowns have chosen value, if so, then show
				if($(table.rows.item(j).cells[0]).find('select').children('option')[i].text == $(table.rows.item(pos).cells[0]).find('select').children(':selected').text()){
					$(table.rows.item(j).cells[0]).find('select').children('option')[i].style.display="block";
				}
			}
		}

		if(rowCount > 1){
			table.deleteRow($(index).closest('tr').index());// fetch index of that row of the table
		}else{
			alert("No more rows to delete");
		}
		
	}catch(e) {
		alert("No more rows to delete");
	}
}

function addIPTableRow(){ // adds new row & 3 columns to the table
	var table = document.getElementById("newIPTable");

	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount - 1);

	var colCount = table.rows[0].cells.length;

	for(var i = 0; i < colCount; i++) {
		var newcell	= row.insertCell(i);// insert cell
		newcell.innerHTML = table.rows[0].cells[i].innerHTML;//create new row
	}
}

function removeIPTableRow(index){ // deletes the row that is selected by user
	try {
		var table = document.getElementById("newIPTable");
		var rowCount = table.rows.length;

		if(rowCount > 1){
			table.deleteRow($(index).closest('tr').index());// fetch index of that row of the table
		}else{
			alert("No more rows to delete");
		}
		
	}catch(e) {
		alert("No more rows to delete");
	}
}