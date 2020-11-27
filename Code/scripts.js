/******************************************************************************
 * Projekt: Hotel: rezervace a správa ubytování                               *
 * Předmět: IIS - Informační systémy - FIT VUT v Brně                         *
 * Rok:     2020/2021                                                         *
 * Tým:     xsekul01, varianta 2                                              *
 * Autoři:                                                                    *
 *          Jakub Sekula   (xsekul01) - xsekul01@stud.fit.vutbr.cz            *
 *          Lukáš Perina   (xperin11) - xperin11@stud.fit.vutbr.cz            *
 *			Martin Fekete  (xfeket00) - xfeket00@stud.fit.vutbr.cz            *
 ******************************************************************************/

function delRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    var table_id = r.parentNode.parentNode.parentNode.parentNode.id;
    table = document.getElementById(table_id);
    id = parseInt(table.rows[i].cells[0].innerHTML);

    if(id == 1){
        alert('You cant remove admin');
    } else{
        if(confirm('Do you really want to remove this item?')){
            if(table_id == "gallery" || table_id == "title"){
                    var removePath = table.rows[i].cells[1].children[0].textContent;
                    $.post("remove.php?id=" + id + "&table=" + table_id + "&path="+removePath, '').done(function (response) {
                        console.log(response);
                    });
                table.deleteRow(i);
            } else{
                if (table_id == "rooms" || table_id == "roomstype" ){
                    id = parseInt(table.rows[i].cells[5].children[0].getAttribute("data-hid"));
                    rtype = parseInt(table.rows[i].cells[5].children[0].getAttribute("data-rtype"));
                    $.post("remove.php?hid=" + id + "&rtype=" + rtype + "&table=" + table_id, '').done(function (response) {
                        console.log(response);
                        if(response.includes("Error")){
                            alert("Ongoing reservation in this room type.");
                        } else{
                            table.deleteRow(i);
                        }
                    });
                } else{
                    if (table_id == "hotel_table" || table_id == "reservations_table"){
                        $.post("remove.php?id=" + id + "&table=" + table_id, '').done(function (response) {
                            console.log(response);
                        });
                        table.deleteRow(i);
                    } else{
                        // removing user is actually updating his pass to none
                        $.post("update.php?id=" + id + "&table=" + table_id + "&userRemove=1", '').done(function (response) {
                            console.log(response);
                        });
                        table.deleteRow(i);
                    }
                }
            }
        } else{

        }
    }
} 

function addRow(id) {
    window.scrollTo(0, 0); 
    var popup = document.getElementById("addRow_"+id);
    console.log(popup);
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}
function updateStatus(r, id) {
    window.scrollTo(0, 0); 
    var i = r.parentNode.parentNode.rowIndex;
    var table_id = r.parentNode.parentNode.parentNode.parentNode.id;
    table = document.getElementById("reservations_table");
    rid = parseInt(table.rows[i].cells[0].innerHTML);

    var input = document.getElementById(id + "_rid");
    input.value = rid;
    var popup = document.getElementById("addRow_" + id);
    console.log(popup);
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}
function addCheck(r,id) {
    window.scrollTo(0, 0); 
    var i = r.parentNode.parentNode.rowIndex;
    var table_id = r.parentNode.parentNode.parentNode.parentNode.id; 
    table = document.getElementById(table_id);
    rid = parseInt(table.rows[i].cells[0].innerHTML);
    
    var inp = document.getElementById(id + "_form");
    var input = document.getElementById(id + "_rid");
    input.value = rid;
    var popup = document.getElementById("addRow_" + id);
    console.log(popup);
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}
function changeState(r, id, principal) {
    window.scrollTo(0, 0); 
    if(principal == ''){
        document.getElementById("nonprincipal").style.display = "block";
        document.getElementById("principal").style.display = "none";
    } else{
        document.getElementById("nonprincipal").style.display = "none";
        document.getElementById("principal").style.display = "block";
    }
    var i = r.parentNode.parentNode.rowIndex;
    var table_id = r.parentNode.parentNode.parentNode.parentNode.id;
    table = document.getElementById(table_id);
    rid = parseInt(table.rows[i].cells[0].innerHTML);
    var input = document.getElementById(id + "_rid");
    input.value = rid;
    var popup = document.getElementById("addRow_" + id);
    console.log(popup);
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}
function addRooms(id,status) {
    window.scrollTo(0, 0); 
    var popup = document.getElementById("addRow_" + id);
    var roomsdiv = document.getElementById("rooms");
    var input = document.createElement("input");
    var input_rbeds = document.createElement("input");
    var input_bedprice = document.createElement("input");
    var input_name = document.createElement("input");
    var input_descr = document.createElement("input");
    var input_principal = document.createElement("input");
    var input_select = document.createElement("input");
    var placehold = document.createElement("input");
    if (status == 1){
        var roomtypename = document.getElementById("rtypename").value;
        var descr = document.getElementById("rdescr").value;
        var rbeds = parseInt(document.getElementById("rbeds").value);
        var bedprice = parseInt(document.getElementById("bedprice").value);
        var principal = parseInt(document.getElementById("principal").value);
        var numofrooms = parseInt(document.getElementById("numofrooms").value);
        var selvals = $('#equip').val();

        placehold.readOnly = true;
        
        input.name = "rnum[]";
        input.type = "hidden";
        if (numofrooms < 1)
        numofrooms = 1;
        if (numofrooms > 254)
        numofrooms = 254;
        input.value = numofrooms;
        input.required = true;  
        
        input_rbeds.type = "hidden";
        input_rbeds.name = "rbeds[]";
        input_rbeds.min = 1;
        input_rbeds.max = 8;
        input_rbeds.value = rbeds;
        if (rbeds < 1){
            input_rbeds.value = 1;
        }
        if (rbeds > 8) {
            input_rbeds.value = 8;
        }
        placehold.placeholder = numofrooms + " rooms with " + input_rbeds.value + " beds priced at " + bedprice;
        placehold.style.fontSize = "20px";
        placehold.style.textAlign = "center";

        input_bedprice.type = "hidden";
        input_bedprice.name = "bedprice[]";
        input_bedprice.value = bedprice;
        
        
        input_name.type = "hidden";
        input_name.name = "rname[]";
        input_name.value = roomtypename;

        input_descr.type = "hidden";
        input_descr.name = "descr[]";
        input_descr.value = descr; 

        input_principal.type = "hidden";
        input_principal.name = "principal[]";
        input_principal.value = principal; 
        
        input_select.type = "hidden";
        input_select.name = "equip[]";
        input_select.value = selvals;

    } 

    if (popup.style.display === "block") {
        if(status == 1){
            roomsdiv.appendChild(input);
            roomsdiv.appendChild(input_rbeds);
            roomsdiv.appendChild(input_bedprice);
            roomsdiv.appendChild(input_name);
            roomsdiv.appendChild(input_descr);
            roomsdiv.appendChild(input_principal);
            roomsdiv.appendChild(input_select);
            roomsdiv.appendChild(placehold);
        } 
        popup.style.display = "none";
    } else {
        document.getElementById("rtypename").value = '';
        document.getElementById("rdescr").value = '';
        document.getElementById("rbeds").value = '';
        document.getElementById("bedprice").value = '';
        document.getElementById("principal").value = '';
        document.getElementById("equip").value = '';
        popup.style.display = "block";
    }
}

function fullwindowpopup(){
    window.open("newpopup.html","bfs","fullscreen,scrollbars")
}

function showHotelName( thise ){
    thise.className = "konelinerheader2";
}

function hideHotelName( thise ){
    thise.className = "konelinerheader";
}

function brow(){
    var list = ["Hong Kong", "Bangkok", "London", "Macau","Singapore","Paris","Dubai","New York City","Kuala Lumpur", "Istanbul","Shenzen","Mumbai"];
    var i = 0;
    setInterval(function(){document.getElementById("ksearch").placeholder = list[i]; i<list.length-1 ? i++ : i=0;}, 3000);
}

function multiselect(){
    $('select[multiple] option').mousedown(function (e) {
        e.preventDefault();
        //var originalScrollTop = $(this).parent().scrollTop();
        $(this).prop('selected', $(this).prop('selected') ? false : true);
        var self = this;
        $(this).parent().focus();
        //setTimeout(function () {
        //    $(self).parent().scrollTop(originalScrollTop);
       // }, 0);
    
        return false;
    });
}

function confSubmit() {
    if (document.getElementsByName('rnum[]').length == 0) {
        alert('Please enter at least one room');
        return false;
    }
}