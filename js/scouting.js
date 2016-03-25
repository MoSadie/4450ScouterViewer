/**
 * Created by Caleb Milligan on 2/23/2016.
 */
function getScores() {
    var team_number = document.getElementById("team_number");
    if (!team_number.value.trim()) {
        return;
    }
    var request = new XMLHttpRequest();
    request.open("GET", "scoutdata.php?action=getinfo&team=" + team_number.value.trim(), true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            if (request.status = 200) {
                var response = request.responseText;
                var matches = JSON.parse(response);
                document.getElementById("average_score").innerHTML = matches["average"];
                document.getElementById("reliability").innerHTML = matches["variance"];
                document.getElementById("team_name").innerHTML = matches["team_name"];
                document.getElementById("robot_description").innerHTML = matches["robot_description"];
                document.getElementById("auto_notes").innerHTML = matches["auto_notes"];
                document.getElementById("drive_base_notes").innerHTML = matches["drive_base_notes"];
                document.getElementById("pickup_notes").innerHTML = matches["pickup_notes"];
                document.getElementById("shooting_notes").innerHTML = matches["shooting_notes"];
                document.getElementById("defense_notes").innerHTML = matches["defense_notes"];
                document.getElementById("robot_image").setAttribute("src", matches["image"]);
                document.getElementById("graph_link").setAttribute("href", "graph/?team=" + matches["team_number"]);
            }
            else if (match_request.status = 500) {
                window.location.replace("errpage.php");
            }
        }
    };
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "scoutdata.php?action=getmatches&team=" + team_number.value.trim(), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4) {
            if (match_request.status == 200) {
                var match_element = document.getElementById("match_data");
                match_element.innerHTML = match_request.responseText;
                var pager = Pager.getPager(".selectable");
                if (!pager) {
                    pager = new Pager(".selectable").setPageSize(18);
                }
                pager.paginate().displayPage();
            }
            else if (match_request.status = 500) {
                window.location.replace("errpage.php");
            }
        }
    }
}

function verifyUpload() {
    if (document.getElementById("file_selector").files.length > 0) {
        document.getElementById("upload_button").removeAttribute("disabled");
    }
    else {
        document.getElementById("upload_button").setAttribute("disabled", "disabled");
    }
}