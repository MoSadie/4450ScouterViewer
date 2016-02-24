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
        var response = request.responseText;
        console.log(response);
        var matches = JSON.parse(response);
        document.getElementById("average_score").innerHTML = "<span>Average score: </span>" + matches["average"];
        document.getElementById("reliability").innerHTML = "<span>Reliability: </span>" + matches["variance"];
        document.getElementById("team_name").innerHTML = "<span>Team Name: </span>" + matches["team_name"];
        document.getElementById("robot_description").innerHTML = "<h4>Robot description: </h4>" + matches["robot_description"];
        var list = "";
        matches["auto_behavior"].forEach(function(entry){
            for(var i = 0; i < 20; i++)
            list += "<li value=\"" + entry["match_number"] + "\">" + entry["autonomous_behavior"] + "</li>";
        });
        document.getElementById("autonomous_behavior").innerHTML = list;
    };
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "scoutdata.php?action=getmatches&team=" + team_number.value.trim(), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        document.getElementById("match_data").innerHTML = match_request.responseText;
    }
}
