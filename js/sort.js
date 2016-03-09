/**
 * Created by Caleb Milligan on 3/9/2016.
 */
$(document).ready(function () {
    executeSort();
});
var sorting = [];
var debouncedExecute = debounce(executeSort, 1500);
function toggleSort(name) {
    var index = sorting.indexOf(name);
    if (index > -1) {
        sorting.splice(index, 1);
    }
    else {
        sorting.push(name);
    }
    debouncedExecute();
}

function executeSort() {
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "../sort.php?order=" + JSON.stringify(sorting), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4 && match_request.status == 200) {
            document.getElementById("match_data").innerHTML = match_request.responseText;
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