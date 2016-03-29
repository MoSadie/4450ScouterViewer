/**
 * Created by Caleb Milligan on 3/28/2016.
 */

function initDoge(image_url) {
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    Doge.image_url = image_url;
    Doge.overlay = document.createElement("div");
    Doge.overlay.setAttribute("style", "position: fixed; z-index: 100; left: 0; right: 0; top: 0; bottom: 0; pointer-events: none;");
    document.body.appendChild(Doge.overlay);
    setInterval(function () {
        if (Doge.overlay.childNodes.length > 4) {
            Doge.overlay.removeChild(Doge.overlay.childNodes.item(0));
        }
        var x = randInt(w - 200);
        var y = randInt(h - 400);
        new Doge(x, y);
    }, 3000);
}


function Doge(x, y) {
    this.elem = document.createElement("div");
    this.styles.position = "absolute";
    this.styles.top = y + "px";
    this.styles.left = x + "px";
    this.styles["z-index"] = "100";
    this.styles.opacity = 1.0;
    var doge_image = document.createElement("img");
    var doge_text = document.createElement("p");
    doge_image.setAttribute("style", "opacity: 0.3");
    var color = Doge.colors[randInt(Doge.colors.length)];
    doge_text.setAttribute("style", "color: " + color + " !important; opacity: 0.6 !important; text-shadow: -1px -1px 0 " +
        "#000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000 !important; text-align: center !important; \"Comic Sans\"," +
        " \"Comic Sans MS\", \"Chalkboard\", \"ChalkboardSE-Regular\", \"Marker Felt\", \"Purisa\", \"URW Chancery L\"," +
        " cursive !important; font-size: 20pt !important;");

    var such = [
        "wow",
        "more than just doge",
        "very " + randomElement(Doge.doge),
        "much " + randomElement(Doge.doge),
        "so " + randomElement(Doge.doge),
        "such " + randomElement(Doge.doge),
        "many " + randomElement(Doge.doge)
    ];

    doge_text.innerHTML = randomElement(such);
    doge_image.setAttribute("src", Doge.image_url);
    this.elem.appendChild(doge_image);
    this.elem.appendChild(doge_text);
    this.applyStyles();
    Doge.overlay.appendChild(this.elem);
}

function randomElement(array){
    return array[randInt(array.length)];
}

Doge.doge = [
    "dog-e",
    "robot",
    "safety",
    "first",
    "data",
    "dance",
    "spirit",
    "stem",
    "automation",
    "coopertition",
    "scouting",
    "education",
    "teamwork",
    "tribble",
    "kamen",
    "inspire",
    "enterprise",
    "wow"
];

Doge.colors = [
    "#0066FF", "#FF3399", "#33CC33", "#FFFF99", "#FFFF75",
    "#8533FF", "#33D6FF", "#FF5CFF", "#19D1A3", "#FF4719",
    "#197519", "#6699FF", "#4747D1", "#D1D1E0", "#FF5050",
    "#FFFFF0", "#CC99FF", "#66E0C2", "#FF4DFF", "#00CCFF"
];

Doge.overlay = undefined;

Doge.prototype.elem = undefined;


Doge.prototype.applyStyles = function () {
    var parsed = "";
    for (var key in this.styles) {
        parsed += key + ": " + this.styles[key] + " !important;";
    }
    this.elem.setAttribute("style", parsed);
};

Doge.prototype.styles = [];

function randInt(limit) {
    return Math.floor(Math.random() * limit);
}