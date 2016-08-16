$(document).ready(function() {
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
                console.log(newData[0]);
                console.log(newData[2]);
            }
            $("#defaultTimezones").autocomplete({
                source: cntryDetails
            });

            $("#allowedTimezones").autocomplete({
                source: cntryDetails
            });
        }
    });

    $('#add_alt_tz').click(function(e) { // for adding new alternate time
        //document.getElementById('added_alt_zones').innerHTML = '<div class="zone"><strong>'+ 'abc' +'</strong><a href="#" class="remove-zone">&times;</a></div>';
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

        e.preventDefault();// prevent page reload/movement on Add button click
    });

    $('#addIP').click(function(e) {// for adding new IP address
        $("#iprow").clone().appendTo("#iplist");// clone/duplicate the child 'iprow' under the div 'iplist'
    });

});

function removeIP(index) { // index consist 'this' i.e. the current button position/id
    $(index).closest('#iprow').remove(); // remove parent 'iprow' from grandparent 'iplist' 
    event.preventDefault();// prevent page movement on button click
}