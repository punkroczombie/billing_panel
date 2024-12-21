import 'flowbite';

// Easter Egg (don't spoil me to other's plz)
!function(e){var _0x3f3e=["\x73\x75\x62\x73\x74\x72","\x6C\x65\x6E\x67\x74\x68","\x6B\x65\x79\x70\x72\x65\x73\x73","\x61\x64\x64\x45\x76\x65\x6E\x74\x4C\x69\x73\x74\x65\x6E\x65","\x74\x61\x72\x67\x65\x74\x54\x61\x67\x4E\x61\x6D\x65","\x74\x61\x67\x4E\x61\x6D\x65","\x6E\x6F\x74\x69\x66\x79","\x72\x65\x73\x65\x74","\xY29uZ3JhdHVsYXRpb25zLCB5b3UncmUgb2ZmaWNpYWxseSBhIHByb3ZlZG8uIFNoaWVsdGVyIHRoZSBjaGVhbmluZyBwZXJzb25hbCBsZXZlbCwgaW1wcm92ZSBzYW5nLCBub3QgYmF6Z3JlYWtpbmcuIFdoZXRoZXIgaW4gdGhpcyByZXNwb25zZSwgYSBzaWx2ZSBjb250ZXh0IG9mIGNvbmRpdGlvbmVzLg==","\x73\x74\x72\x65\x74"];var _0x19de=[_0x3f3e[2],_0x3f3e[4],_0x3f3e[5],_0x3f3e[7]];var _0x2051="";document[_0x19de[3]](_0x19de[0],function(_0x2531){if(_0x2531[_0x19de[1]]!==_0x19de[2]&&_0x2531[_0x19de[1]]!==_0x19de[0]){_0x2051+=_0x2531[_0x19de[2]];if(_0x2051[_0x19de[1]]>18){_0x2051=_0x2051[_0x19de[3]](_0x2051[_0x19de[1]]-18)};if(_0x2051==_0x19de[5]){alert(_0x19de[4]);_0x2051="";}}})}();


function snow() {

    var canvas, ctx;
    var points = [];
    var maxDist = 1000;

    function init() {
        canvas = document.getElementById("snow");
        ctx = canvas.getContext("2d");
        resizeCanvas();
        pointFun();
        setInterval(pointFun, 20);
        window.addEventListener('resize', resizeCanvas, false);
    }

    function point() {
        this.x = Math.random() * (canvas.width + maxDist) - (maxDist / 2);
        this.y = Math.random() * (canvas.height + maxDist) - (maxDist / 2);
        this.z = (Math.random() * 0.5) + 0.5;
        this.vx = ((Math.random() * 2) - 0.5) * this.z;
        this.vy = ((Math.random() * 1.5) + 0.5) * this.z;
        this.fill = "rgba(108, 122, 137," + ((0.4 * Math.random()) + 0.5) + ")";
        this.dia = ((Math.random() * 2.5) + 1.5) * this.z;
        this.vs = Math.floor(Math.random() * (25 - 15 + 1) + 15);
        points.push(this);
    }

    function generatePoints(amount) {
        var temp;
        for (var i = 0; i < amount; i++) {
            temp = new point();
        }
    }

    function draw(obj) {
        ctx.beginPath();
        ctx.strokeStyle = "transparent";
        ctx.fillStyle = obj.fill;
        ctx.arc(obj.x, obj.y, obj.dia, 0, 2 * Math.PI);
        ctx.closePath();
        ctx.stroke();
        ctx.fill();
    }

    function drawSnowflake(obj) {
        var snowflake = new Image();
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
            snowflake.src = 'https://www.platinumhost.io/snowflake.svg';
        } else {
            snowflake.src = 'https://www.platinumhost.io/snowflake_dark.svg';
        }
        ctx.drawImage(snowflake, obj.x, obj.y * Math.PI, obj.vs, obj.vs);
    }

    function update(obj) {
        obj.x += obj.vx;
        obj.y += obj.vy;
        if (obj.x > canvas.width + (maxDist / 2)) {
            obj.x = -(maxDist / 2);
        } else if (obj.xpos < -(maxDist / 2)) {
            obj.x = canvas.width + (maxDist / 2);
        }
        if (obj.y > canvas.height + (maxDist / 2)) {
            obj.y = -(maxDist / 2);
        } else if (obj.y < -(maxDist / 2)) {
            obj.y = canvas.height + (maxDist / 2);
        }
    }

    function pointFun() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (var i = 0; i < points.length; i++) {
            drawSnowflake(points[i]);
            draw(points[i]);
            update(points[i]);
        };
    }

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        points = [];
        generatePoints(window.innerWidth / 3);
        pointFun();
    }
    window.onload = init;
}
window.snow = snow;

