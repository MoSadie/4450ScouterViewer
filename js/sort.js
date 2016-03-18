/**
 * Created by Caleb Milligan on 3/9/2016.
 */
$(document).ready(function () {
    executeSort();
});
var sorting = {};
var debouncedExecute = debounce(executeSort, 1500);
function toggleSort(name) {
    if (sorting.hasOwnProperty(name)) {
        delete sorting[name];
    }
    else {
        sorting[name] = getOrderDir(name);
    }
    debouncedExecute();
}

function getOrderDir(name) {
    switch (name.toLowerCase()) {
        case "scouter_name":
        case "no_show":
        case "dead_on_field":
        case "defended":
        case "portcullis_speed":
        case "chival_speed":
        case "moat_speed":
        case "ramparts_speed":
        case "drawbridge_speed":
        case "sally_speed":
        case "rock_speed":
        case "rough_speed":
        case "low_speed":
            return "ASC";
        default:
            return "DESC";
    }
}

function executeSort() {
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "../sort.php?order=" + JSON.stringify(sorting), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4) {
            if (match_request.status == 200) {
                document.getElementById("match_data").innerHTML = match_request.responseText;
                reloadHighlights();
            }
            else if(match_request.status == 500){
                window.location.replace("../errpage.php");
            }
        }
    };
}

function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) {
                func.apply(context, args);
            }
        };
        var call_now = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (call_now) {
            func.apply(context, args);
        }
    };
}