/**
 * Created by Caleb Milligan on 3/18/2016.
 */
$(document).ready(function () {
    $(".selectable tbody").delegate('td', 'click', toggleRowEvent);
    document.addEventListener("mousedown", function (event) {
        if(!event.target || event.target.tagName.toLowerCase() != 'td'){
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