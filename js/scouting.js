/**
 * Created by Caleb Milligan on 2/23/2016.
 */
$(document).ready(function(){
    var submit_team = document.getElementById("submit_team");
    var submit_match = document.getElementById("submit_match");
    var match_number = document.getElementById("match_number");
});

function getScores(){
    var team_number = document.getElementById("team_number");
    if(!team_number.value.trim()){
        return;
    }
    var request = new XMLHttpRequest();
    request.open("GET", "scoutdata.php?action=getinfo&team=" + team_number.value.trim(), true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            console.log("WOOOO");
            var response = request.responseText;
            console.log(response);
            var matches = JSON.parse(response);
            $("average_score").innerHTML = "<span>Average score: </span>" + matches["average"];
            $("reliability").innerHTML = "<span>Reliability: </span>" + matches["variance"];
            $("team_name").innerHTML = "<span>Team Name: </span>" + matches["team_name"];
            $("robot_description").innerHTML = "<h4>Robot description: </h4>" + matches["robot_description"];
            $("robot_image").attr("src", matches["image"]);
            var list = "";
            matches["auto_behavior"].forEach(function (entry) {
                list += "<li value=\"" + entry["match_number"] + "\">" + entry["autonomous_behavior"] + "</li>";
            });
            document.getElementById("autonomous_behavior").innerHTML = list;
        }
    };
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "scoutdata.php?action=getmatches&team=" + team_number.value.trim(), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4 && match_request.status == 200) {
            console.log("WOOOOHOO");
            $("match_data").innerHTML = match_request.responseText;
        }
    }
}
