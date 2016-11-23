var cntryDetailsFinal = [];
$(document).ready(function() {
    document.getElementById("timeidle").min = 1;
    $.ajax({
        type: "GET",
        url: "../csv/country_dialcode_gmt.txt",
        dataType: "text",
        success: function(data) {
            var datas = data.split("\n");
            var cntryDetails = [];
            for(var i = 0; i < datas.length; i++){
                var newData = datas[i].split('\t');
                var cntry = '';
                cntryDetails[i] = newData[0] + " (" + newData[2] + ')';
                cntryDetailsFinal[i] = cntryDetails[i];
            }
            $("#defaultTimezones").autocomplete({
                source: cntryDetails
            });

            $("#allowedTimezones").autocomplete({
                source: cntryDetails
            });
        }
    });

    $("#defaultTimezones").focusout(function(){ //when user enters data -> triggers when textbox loses focus
        console.log("calling changes");
        $.ajax({
            type: "GET",
            url: "../csv/country_dialcode_gmt.txt",
            dataType: "text",
            success: function(data) {
                var datas = data.split("\n");
                var cntryDetails = [];
                for(var i = 0; i < datas.length; i++){
                    var newData = datas[i].split('\t');
                    var cntry = '';
                    cntryDetails[i] = newData[0] + " (" + newData[2] + ')';
                }
                $("#defaultTimezones").autocomplete({
                    source: cntryDetails
                });
                var index = cntryDetails.indexOf($("#defaultTimezones").val());
                if(index == -1) {
                    document.getElementById("defaultTimezones").value = '';
                    alert("Please choose timezone from the list suggested below");
                } else {
                    var x = cntryDetails.length;
                    for(var j = index; j < x - 1; j++){
                        cntryDetails[j] = cntryDetails[j + 1];
                    }
                    $("#allowedTimezones").autocomplete({
                        source: cntryDetails
                    });
                }
            }
        });
    });

    $('#add_alt_tz').click(function(e) { // for adding new alternate time
        //document.getElementById('added_alt_zones').innerHTML = '<div class="zone"><strong>'+ 'abc' +'</strong><a href="#" class="remove-zone">&times;</a></div>';
        if(document.getElementById("allowedTimezones").value.length > 0){// if textbox is not null
            var div = document.createElement("div");
            var inpHidden = document.createElement("input");
            var text = document.createTextNode($('#allowedTimezones').val());// get value from 'allowedTimezones' textbox, & assign to TextNode
            var link = document.createElement("a");
            var close = document.createTextNode('×');// -> '&times;'
            link.appendChild(close);// add close button to link
            link.className = "remove-zone";
            link.href = "#";
            link.onclick = function(e) { // remove option on button click
                this.closest('div').remove();
                $.ajax({
                    type: "GET",
                    url: "../csv/country_dialcode_gmt.txt",
                    dataType: "text",
                    success: function(data) {
                        var datas = data.split("\n");
                        var cntryDetails = [];
                        for(var i = 0; i < datas.length; i++){
                            var newData = datas[i].split('\t');
                            var cntry = '';
                            cntryDetails[i] = newData[0] + " (" + newData[2] + ')';
                            /*console.log(newData[0]);
                            console.log(newData[2]);*/
                        }
                        $("#defaultTimezones").autocomplete({
                            source: cntryDetails
                        });
                        var index = cntryDetails.indexOf($("#defaultTimezones").val());

                        var x = cntryDetails.length;
                        
                        for(var j = index; j < x - 1; j++){
                            cntryDetails[j] = cntryDetails[j + 1];
                        }
                        cntryDetails.pop();// delete last element of array

                        var altValues = document.getElementsByClassName("zone");
                        var omit = altValues.length;

                        for(var k = 0; k < omit; k++){
                            var x = cntryDetails.length;// new array size
                            var index = cntryDetails.indexOf(altValues[k].getElementsByTagName("input")[0].value);
                            for(var j = index; j < x - 1; j++){
                                cntryDetails[j] = cntryDetails[j + 1];
                            }  
                            cntryDetails.pop();// delete last element of array  
                        }

                        $("#allowedTimezones").autocomplete({
                            source: cntryDetails
                        });
                    }
                });
                e.preventDefault();// prevent page reload, or movement of page
            };
            
            inpHidden.value = $('#allowedTimezones').val();
            inpHidden.setAttribute("name","alttime[]");// assign name (array) to alt_time_zone
            inpHidden.setAttribute("type","hidden");
            div.appendChild(inpHidden);
            div.appendChild(text);
            div.appendChild(link);
            div.className = "zone";// css class assigned
            document.getElementById('added_alt_zones').appendChild(div);

            document.getElementById("allowedTimezones").value = '';

            $.ajax({
                type: "GET",
                url: "../csv/country_dialcode_gmt.txt",
                dataType: "text",
                success: function(data) {
                    var datas = data.split("\n");
                    var cntryDetails = [];
                    for(var i = 0; i < datas.length; i++){
                        var newData = datas[i].split('\t');
                        var cntry = '';
                        cntryDetails[i] = newData[0] + " (" + newData[2] + ')';
                    }
                    $("#defaultTimezones").autocomplete({
                        source: cntryDetails
                    });
                    var index = cntryDetails.indexOf($("#defaultTimezones").val());

                    var x = cntryDetails.length;
                    
                    for(var j = index; j < x - 1; j++){
                        cntryDetails[j] = cntryDetails[j + 1];
                    }
                    cntryDetails.pop();// delete last element of array

                    var altValues = document.getElementsByClassName("zone");
                    var omit = altValues.length;

                    for(var k = 0; k < omit; k++){
                        var x = cntryDetails.length;// new array size
                        var index = cntryDetails.indexOf(altValues[k].getElementsByTagName("input")[0].value);
                        for(var j = index; j < x - 1; j++){
                            cntryDetails[j] = cntryDetails[j + 1];
                        }  
                        cntryDetails.pop();// delete last element of array  
                    }
                    

                    $("#allowedTimezones").autocomplete({
                        source: cntryDetails
                    });
                }
            });

            e.preventDefault();// prevent page reload/movement on Add button click
        } else {
            document.getElementById("allowedTimezones").value = '';
            alert("Please select a timezone before adding");
            e.preventDefault();// prevent page reload/movement on Add button click
        }
    });

    $('#addIP').click(function(e) {// for adding new IP address
        $("#iprow").clone().appendTo("#iplist");// clone/duplicate the child 'iprow' under the div 'iplist'
    });

});

function removeIP(index) { // index consist 'this' i.e. the current button position/id
    if(document.getElementsByClassName("ipr").length > 1)
        $(index).closest('#iprow').remove(); // remove parent 'iprow' from grandparent 'iplist'
    event.preventDefault();// prevent page movement on button click
}

function validate(e) { /* Validates Create Organization Page */
    var flag = 0, cmpFlag = 3;

    if(document.getElementById("orgName").value !== '')
        flag++;

    if(document.getElementById("timeidle").value !== '' && !isNaN(document.getElementById("timeidle").value)) { // not empty & not string
        flag++;
    } else {
        document.getElementById("timeidle").value = 1;
    }

    if($("#defaultTimezones").val() != ''){
        for(var i = 0; i < cntryDetailsFinal.length; i++){ // checks if default time zone is empty or not
            if(cntryDetailsFinal[i] == $("#defaultTimezones").val())
                flag++;
        }
    }

    var altValues = document.getElementsByClassName("zone");
    cmpFlag += altValues.length;

    for(var i = 0; i < altValues.length; i++){
        for(var j = 0; j < cntryDetailsFinal.length; j++){
            if(cntryDetailsFinal[j] == altValues[i].getElementsByTagName("input")[0].value){
                flag++;
                break; // breaks from inner for loop
            }
        }
    }

    console.log(document.getElementsByClassName("ipr").length);
    var ipClass = document.getElementsByClassName("ipr");
    cmpFlag += ipClass.length;

    for(var i = 0;i < ipClass.length; i++) {
        var ipaddr = ipClass[i].getElementsByTagName("input");
        var select = ipClass[i].getElementsByTagName("select");
        
        if(select[0].selectedIndex > 0 && ipaddr[0].value !== '') {
            var ipAddrSplit = ipaddr[0].value.split('.');// split a.b.c.d to 4 segments, if it is IP
            var cnt = 0;
            for(var j = 0; j < ipAddrSplit.length; j++) {
                if(!isNaN(ipAddrSplit[j]) && ipAddrSplit[j].length <= 3){ // each slot is number & each has only 3 digit
                    cnt++;
                }
            }
            if(cnt == 4)
                flag++;
        }
    }
    
    if(flag < cmpFlag){
        console.log("not submitted");
        alert("Seems like you have not filled the form properly !!");
        event.preventDefault();
        return false;
    } else {
        console.log("submit");
        return true;
    }
}

function validateIssue(e) {
   var flag = 0;

    if(document.getElementById("queryName").value !== '' && isNaN(document.getElementById("queryName").value))
        flag++;

    if(document.getElementById("queryEmail").value !== '' && isNaN(document.getElementById("queryEmail").value)) { // not empty & is string
        flag++;
    }

    if(document.getElementById("issueOption").value !== "" || document.getElementById("issueOption").selectedIndex !== 0)
        flag++;
    
    if((document.getElementById("issueOption").value === "others" || document.getElementById("issueOption").selectedIndex == 3) && document.getElementById("issueTextArea").value === "") {
        alert("Please define your issue in the Text Area as you have chosen 'Others' as the issue.");
        event.preventDefault();
    }

    if (flag >= 3){
        //document.getElementById("joinOrgQuery").submit();
        return true;
    } else {
        alert("Seems like the form is incomplete !!");
        event.preventDefault();
        return false;
    }
}