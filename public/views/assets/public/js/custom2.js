$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "../csv/country_dialcode_gmt.txt",
        dataType: "text",
        success: function(data) {
            var datas = data.split("\n");
            for(var i = 0; i < datas.length; i++){
                var newData = datas[i].split(" ");
                var cntry = '';
                for(var j = 0; j < newData.length; j++){
                    if(!(newData[j][0] >='0' && newData[j][0] <= '9') && !newData[j].includes("GMT") && !newData[j].includes("\t") && !newData[j].includes(" ")){
                        cntry += '-' + newData[j];
                    }
                }
                console.log(cntry);
            }
            /*$("#defaultTimezones").autocomplete({
                source: cntryName
            });

            $("#allowedTimezones").autocomplete({
                source: cntryName
            });*/

            //console.log(cntryCode.indexOf("IN"));
        }
     });
});