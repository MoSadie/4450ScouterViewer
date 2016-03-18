/**
 * Created by Caleb Milligan on 3/18/2016.
 */
$(document).ready(function(){
    $(".selectable tbody").delegate('td', 'click', toggleRow);
});

function toggleRow(event) {
    var row = getNearestTableAncestor(event.target, 'tr');
    if (row) {
        row.classList.toggle("selected");
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