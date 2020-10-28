function delRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    var table_id = r.parentNode.parentNode.parentNode.parentNode.id;
    table = document.getElementById(table_id);
    id = parseInt(table.rows[i].cells[0].innerHTML);

    if(id == 1){
        alert('You cant odjebat admin');
    } else{
        if(confirm('Do you really want to remove this item?')){
            if(table_id == "gallery" || table_id == "title"){
                var rowid = table_id == "gallery" ? 4 : 3;
                if (table.rows[i].cells[rowid].children[0].checked){
                    var removePath = table.rows[i].cells[3].children[0].textContent;
                    $.post("remove.php?id=" + id + "&table=" + table_id + "&path="+removePath, '').done(function (response) {
                        console.log(response);
                    });
                }
                table.deleteRow(i);
            } else{
                if (table_id == "rooms" || table_id == "roomstype" ){
                    id = parseInt(table.rows[i].cells[4].children[0].getAttribute("data-hid"));
                    rtype = parseInt(table.rows[i].cells[4].children[0].getAttribute("data-rtype"));
                    $.post("remove.php?hid=" + id + "&rtype=" + rtype + "&table=" + table_id, '').done(function (response) {
                        console.log(response);
                        if(response.includes("Error")){
                            alert("Ongoing reservation in this room type.");
                        } else{
                            table.deleteRow(i);
                        }
                    });
                } else{
                    $.post("remove.php?id=" + id + "&table=" + table_id, '').done(function (response) {
                        console.log(response);
                    });
                    table.deleteRow(i);
                }
            }
        } else{

        }
    }
} 

function addRow(id) {
    var popup = document.getElementById("addRow_"+id);
    console.log(popup);
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}
function addRooms(id,status) {
    var popup = document.getElementById("addRow_" + id);
    var roomsdiv = document.getElementById("rooms");
    var input = document.createElement("input");
    var input_rbeds = document.createElement("input");
    var input_bedprice = document.createElement("input");
    var input_name = document.createElement("input");
    var input_descr = document.createElement("input");
    if (status == 1){
        var roomtypename = document.getElementById("rtypename").value;
        var descr = document.getElementById("rdescr").value;
        var rbeds = parseInt(document.getElementById("rbeds").value)
        var bedprice = parseInt(document.getElementById("bedprice").value)
    
        input.type = "number";
        input.name = "rnum[]";
        input.min = 1;
        input.max = 254;
        input.required = true;  
        input.placeholder = "Number of " + roomtypename + " rooms with " + rbeds + " beds priced at " + bedprice;
    
        input_rbeds.type = "hidden";
        input_rbeds.name = "rbeds[]";
        input_rbeds.value = rbeds;

        input_bedprice.type = "hidden";
        input_bedprice.name = "bedprice[]";
        input_bedprice.value = bedprice;
        
        
        input_name.type = "hidden";
        input_name.name = "rname[]";
        input_name.value = roomtypename;

        input_descr.type = "hidden";
        input_descr.name = "descr[]";
        input_descr.value = descr; 
    } 

    if (popup.style.display === "block") {
        if(status == 1){
            roomsdiv.appendChild(input);
            roomsdiv.appendChild(input_rbeds);
            roomsdiv.appendChild(input_bedprice);
            roomsdiv.appendChild(input_name);
            roomsdiv.appendChild(input_descr);
        } 
        popup.style.display = "none";
    } else {
        document.getElementById("rtypename").value = '';
        document.getElementById("rdescr").value = '';
        document.getElementById("rbeds").value = '';
        document.getElementById("bedprice").value = '';
        popup.style.display = "block";
    }
}

function fullwindowpopup(){
    window.open("newpopup.html","bfs","fullscreen,scrollbars")
 }