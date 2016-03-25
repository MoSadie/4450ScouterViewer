/**
 * Created by Caleb Milligan on 2/23/2016.
 */

$(document).ready(function () {
    canvas_element = document.getElementById("performance-table");
    width = canvas_element.clientWidth;
    height = width / 2;
    chart_canvas = canvas_element.getContext("2d");
    Chart.defaults.global.maintainAspectRatio = false;
    Chart.defaults.global.responsive = false;
});

function refreshCanvas(){
    canvas_element.setAttribute("style", "width:" + width + ";height :" + height + "px");
    canvas_element.setAttribute("height", "" + height);
    canvas_element.setAttribute("width", "" + width);
}

var canvas_element;
var chart_canvas;
var chart;
var width;
var height;

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

                var low_points = [];
                var high_points = [];
                var labels = [];
                for (var i = 0; i < match_element.childNodes.length; i++) {
                    var row = match_element.childNodes.item(i);
                    var match_num = row.childNodes.item(1).textContent;
                    var low_goals = row.childNodes.item(27).textContent;
                    var high_goals = row.childNodes.item(28).textContent;
                    labels[i] = match_num;
                    low_points[i] = low_goals;
                    high_points[i] = high_goals;
                }

                var data = {
                    labels: labels,
                    datasets: [
                        {
                            label: "Low Goals",
                            fillColor: "rgba(220,220,220,0.2)",
                            strokeColor: "rgba(220,220,220,1)",
                            pointColor: "rgba(220,220,220,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: low_points
                        },
                        {
                            label: "High Goals",
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: high_points
                        }
                    ]
                };
                chart = new Chart(chart_canvas).Line(data);
                refreshCanvas();
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