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
    request.open("GET", "scoutdata.php?action=getmatches&team=" + team_number.value.trim(), true);
    request.send();
    request.onreadystatechange = function () {
        var response = request.responseText;
        console.log(response);
        var matches = JSON.parse(response);
        document.getElementById("average_score").innerHTML = "<span>Average score: </span>" + matches["average"];
        document.getElementById("reliability").innerHTML = "<span>Reliability: </span>" + matches["variance"];
    }
}
