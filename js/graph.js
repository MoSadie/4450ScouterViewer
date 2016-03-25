/**
 * Created by Caleb Milligan on 3/25/2016.
 */
$(document).ready(function () {
    var nodes = document.getElementsByClassName("graph-container");
    for (var i = 0; i < nodes.length; i++) {
        var node = nodes.item(i);
        var id = node.getAttribute("id");
        new Graph(id);
    }
});

function doGraph() {
    var team_number = document.getElementById("team_number");
    if (!team_number.value.trim()) {
        return;
    }
    var match_request = new XMLHttpRequest();
    match_request.open("GET", "../scoutdata.php?action=getrawmatches&team=" + team_number.value.trim(), true);
    match_request.send();
    match_request.onreadystatechange = function () {
        if (match_request.readyState == 4) {
            if(match_request.status = 200) {
                match_request.onreadystatechange = function () {
                };
                for (var key in Graph.graphs) {
                    Graph.graphs[key].setData([], undefined, [], undefined, []);
                }
                if (match_request.status == 200) {
                    var matches = JSON.parse(match_request.responseText);
                    var scores = [];
                    var labels = [];
                    var endgame_data = [];
                    var speed_data = {
                        portcullis: [],
                        chival: [],
                        moat: [],
                        ramparts: [],
                        drawbridge: [],
                        sally: [],
                        rock: [],
                        rough: [],
                        low: []
                    };
                    var cross_data = {
                        portcullis: [],
                        chival: [],
                        moat: [],
                        ramparts: [],
                        drawbridge: [],
                        sally: [],
                        rock: [],
                        rough: [],
                        low: []
                    };
                    var goal_data = {
                        high: [],
                        low: []
                    };
                    matches.forEach(function (match) {
                        labels.push(match["match_number"]);
                        speed_data["portcullis"].push(~~match["portcullis_speed"] + 1);
                        cross_data["portcullis"].push(~~match["portcullis_crosses"]);
                        speed_data["chival"].push(~~match["chival_speed"] + 1);
                        cross_data["chival"].push(~~match["chival_crosses"]);
                        speed_data["moat"].push(~~match["moat_speed"] + 1);
                        cross_data["moat"].push(~~match["moat_crosses"]);
                        speed_data["ramparts"].push(~~match["ramparts_speed"] + 1);
                        cross_data["ramparts"].push(~~match["ramparts_crosses"]);
                        speed_data["drawbridge"].push(~~match["drawbridge_speed"] + 1);
                        cross_data["drawbridge"].push(~~match["drawbridge_crosses"]);
                        speed_data["sally"].push(~~match["sally_speed"] + 1);
                        cross_data["sally"].push(~~match["sally_crosses"]);
                        speed_data["rock"].push(~~match["rock_speed"] + 1);
                        cross_data["rock"].push(~~match["rock_crosses"]);
                        speed_data["rough"].push(~~match["rough_speed"] + 1);
                        cross_data["rough"].push(~~match["rough_crosses"]);
                        speed_data["low"].push(~~match["portcullis_speed"] + 1);
                        cross_data["low"].push(~~match["portcullis_crosses"]);
                        goal_data["high"].push(~~match["high_goals"]);
                        goal_data["low"].push(~~match["low_goals"]);
                        endgame_data.push(~~match["endgame"] + 1);
                    });
                    for (var i = 0; i < labels.length; i++) {
                        var score = 0;
                        score += goal_data.high[i] * 5;
                        score += goal_data.low[i] * 2;
                        if (goal_data.high[i] + goal_data.low[i] >= 8) {
                            score += 25;
                        }
                        for (key in cross_data) {
                            var crosses = Math.min(Math.max(Math.min(speed_data[key][i], 1), cross_data[key][i]), 3);
                            if (crosses >= 3) {
                                score += 20;
                            }
                            score += crosses * 5;
                        }
                        switch (endgame_data[i]) {
                            case 1:
                                score += 5;
                                break;
                            case 2:
                                score += 15;
                                break;
                        }
                        scores[i] = score;
                    }
                    Graph.graphs["graph-portcullis"].setData(labels, "Crosses", cross_data["portcullis"], "Speed", speed_data["portcullis"]);
                    Graph.graphs["graph-chival"].setData(labels, "Crosses", cross_data["chival"], "Speed", speed_data["chival"]);
                    Graph.graphs["graph-moat"].setData(labels, "Crosses", cross_data["moat"], "Speed", speed_data["moat"]);
                    Graph.graphs["graph-ramparts"].setData(labels, "Crosses", cross_data["ramparts"], "Speed", speed_data["ramparts"]);
                    Graph.graphs["graph-drawbridge"].setData(labels, "Crosses", cross_data["drawbridge"], "Speed", speed_data["drawbridge"]);
                    Graph.graphs["graph-sally"].setData(labels, "Crosses", cross_data["sally"], "Speed", speed_data["sally"]);
                    Graph.graphs["graph-rock"].setData(labels, "Crosses", cross_data["rock"], "Speed", speed_data["rock"]);
                    Graph.graphs["graph-rough"].setData(labels, "Crosses", cross_data["rough"], "Speed", speed_data["rough"]);
                    Graph.graphs["graph-low"].setData(labels, "Crosses", cross_data["low"], "Speed", speed_data["low"]);
                    Graph.graphs["graph-goals"].setData(labels, "Low Goals", goal_data["low"], "High Goals", goal_data["high"]);
                    Graph.graphs["graph-endgame"].setData(labels, "Activity", endgame_data);
                    Graph.graphs["graph-points"].setData(labels, "Points", scores);
                }
            }
            else if (match_request.status = 500) {
                window.location.replace("../errpage.php");
            }
        }
    };
}


function Graph(canvas_id) {
    this.id = canvas_id;
    this.canvas_element = document.getElementById(canvas_id);
    this.canvas = this.canvas_element.getContext("2d");
    this.chart = new Chart(this.canvas);
    Graph.graphs[canvas_id] = this;
}
Graph.graphs = {};
Graph.prototype.id = undefined;
Graph.prototype.canvas_element = undefined;
Graph.prototype.canvas = undefined;
Graph.prototype.chart = undefined;
Graph.prototype.line = undefined;
Graph.prototype.setData = function (labels, line_1_label, line_1_data, line_2_label, line_2_data) {
    this.data.labels = labels;
    this.data.datasets[0].label = line_1_label;
    this.data.datasets[0].data = line_1_data;
    this.data.datasets[1].label = line_2_label;
    this.data.datasets[1].data = line_2_data;
    if (!this.line) {
        this.line = this.chart.Line(this.data);
    }
    else {
        this.line.initialize(this.data);
    }
};

Graph.prototype.data = {
    labels: [],
    datasets: [
        {
            label: undefined,
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: []
        },
        {
            label: undefined,
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: []
        }
    ]
};