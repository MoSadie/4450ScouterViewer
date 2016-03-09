/**
 * Created by Caleb Milligan on 3/9/2016.
 */
var sorting = [];
function toggleSort(name){
    var index = sorting.indexOf(name);
    if(index > -1){
        sorting.splice(index, 1);
    }
    else{
        sorting.push(name);
    }
    console.log(sorting);
    executeSort();
}

function executeSort(){
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "../sort.php?order=" + JSON.stringify(sorting), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4 && match_request.status == 200) {
            document.getElementById("match_data").innerHTML = match_request.responseText;
        }
    }
}