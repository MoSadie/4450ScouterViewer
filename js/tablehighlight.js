/**
 * Created by Caleb Milligan on 3/18/2016.
 */
$(document).ready(function () {
    $(".selectable tbody").delegate('td', 'click', toggleRowEvent);
    document.addEventListener("mousedown", function (event) {
        if (!event.target || event.target.tagName.toLowerCase() != 'td') {
            last_highlight = undefined;
        }
    });
    var monitorShift = function (event) {
        shift_pressed = event.shiftKey;
    };
    document.addEventListener("keydown", monitorShift);
    document.addEventListener("keyup", monitorShift);
});
var last_highlight = undefined;
var shift_pressed = false;
var highlighted = [];
var pages = [];
var current_page = 0;
var page_size = 30;

function paginate() {
    var i = 0;
    var page = [];
    current_page = 0;
    pages = [];
    var full_page = false;
    $(".selectable tbody tr").each(function () {
        page.push(this);
        full_page = false;
        if (i++ >= page_size) {
            pages.push(page);
            page = [];
            i = 0;
            full_page = true;
        }
    });
    if(!full_page){
        pages.push(page);
    }
    $(".selectable tbody").innerHTML = "";
}

function firstPage() {
    current_page = 0;
    displayPage();
}

function prevPage() {
    if (current_page > 0) {
        current_page--;
        displayPage();
    }
}

function nextPage() {
    if (current_page < (pages.length - 1)) {
        current_page++;
        displayPage();
    }
}

function lastPage() {
    current_page = pages.length - 1;
    displayPage();
}

function displayPage() {
    var str = "";
    pages[current_page].forEach(function (value) {
        str += value.outerHTML;
    });
    $(".selectable tbody").each(function () {
        this.innerHTML = str;
    });
    $(".paginator_selector").each(function () {
        this.value = (current_page + 1) + "/" + (pages.length);
    });
}

function inputPage(who) {
    var input = who.value;
    console.log(input);
    if(!input){
        return;
    }
    input = input.split("/")[0];
    current_page = input - 1;
    current_page = Math.min(Math.max(0, current_page), pages.length - 1);
    displayPage();
}

function reloadHighlights() {
    var tmp = highlighted;
    highlighted = [];
    tmp.forEach(function (id) {
        console.log("CURRENT ID IS " + id);
        $(".selectable tbody tr").each(function () {
            var tmp_id = this.cells.item(0).textContent;
            console.log("WE'RE CHECKING " + tmp_id);
            if (tmp_id == id) {
                toggleRow(this);
                return false;
            }
        });
    });
}

function toggleRow(row) {
    row.classList.toggle("selected");
    var id = row.cells.item(0).textContent;
    if (row.classList.contains("selected")) {
        highlighted.push(id);
    }
    else {
        var i = highlighted.indexOf(id);
        if (i > -1) {
            highlighted.splice(i, 1);
        }
    }
}

function toggleRowEvent(event) {
    var row = getNearestTableAncestor(event.target, 'tr');
    if (row) {
        if (last_highlight && shift_pressed) {
            var start = last_highlight.rowIndex;
            var end = row.rowIndex;
            var i = 0;
            if (start < end) {
                for (i = start; i < end; i++) {
                    toggleRow(row.parentNode.childNodes.item(i));
                }
            }
            else {
                for (i = end - 1; i < start - 1; i++) {
                    toggleRow(row.parentNode.childNodes.item(i));
                }
            }
            deselect();
        }
        else {
            toggleRow(row);
        }
        last_highlight = row;
    }
}

function deselect() {
    if (window.getSelection) {
        var selection = window.getSelection();
        if (selection.empty) {
            selection.empty();
        }
        else if (selection.removeAllRanges) {
            selection.removeAllRanges();
        }
    }
    else if (document.selection) {
        document.selection.empty();
    }
}

function getNearestTableAncestor(node, type) {
    while (node) {
        node = node.parentNode;
        if (node.tagName.toLowerCase() == type) {
            return node;
        }
    }
    return undefined;
}