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

    $('#add_alt_tz').click(function(e) {
        //document.getElementById('added_alt_zones').innerHTML = '<div class="zone"><strong>'+ 'abc' +'</strong><a href="#" class="remove-zone">&times;</a></div>';
        var div  = document.createElement("div");
        var text = document.createTextNode($('#allowedTimezones').val());
        var link = document.createElement("a");
        var close = document.createTextNode('×');// -> '&times;'
        link.appendChild(close);
        link.className = "remove-zone";
        link.href = "#";
        link.onclick = function(e) {
            this.closest('div').remove();
            e.preventDefault();
        };
        //link.addClass("remove-zone");
        //link.innerHTML = '<a href="#" class="remove-zone" onclick="removeDivAltTimezone(this)">&times;</a>';
        
        div.appendChild(text);
        div.appendChild(link);
        div.setAttribute("name","alttime[]");
        div.className = "zone";
        document.getElementById('added_alt_zones').appendChild(div);

        e.preventDefault();
    });

    $('#addIP').click(function(e) {
        $("#iprow").clone().appendTo("#iplist");
    });

});

function removeIP(index) {
    $(index).closest('#iprow').remove();
    event.preventDefault();
}