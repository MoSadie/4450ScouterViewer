/**
 * Created by Caleb Milligan on 3/18/2016.
 */
$(document).ready(function () {
    $(".selectable tbody").delegate('td', 'click', toggleRowEvent);
});

var highlighted = [];

function reloadHighlights() {
    var tmp = highlighted;
    highlighted = [];
    tmp.forEach(function (row) {
        var match_number = row.cells.item(0).textContent;
        var team_number = row.cells.item(1).textContent;
        console.log(match_number);
        console.log(team_number);
        $(".selectable tbody tr").each(function(){
            if(this.cells.item(0).textContent == match_number
                && this.cells.item(1).textContent == team_number){
                toggleRow(this);
            }
        });
    });
}

function toggleRow(row) {
    row.classList.toggle("selected");
    if (row.classList.contains("selected")) {
        highlighted.push(row);
    }
    else {
        var i = highlighted.indexOf(row);
        if (i > -1) {
            highlighted.splice(i, 1);
        }
    }
}

function toggleRowEvent(event) {
    var row = getNearestTableAncestor(event.target, 'tr');
    if (row) {
        toggleRow(row);
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