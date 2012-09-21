<?php
if(isset($_GET["project"])) {
    $project = $_GET["project"];
} else {
    $project = "No project";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Run statistics for <?php print $project ?></title>
        <link rel="stylesheet" href="demo.css" media="screen">
        <link rel="stylesheet" href="demo-print.css" media="print">
        <style media="screen">
            #holder {
                height: 600px;
                /*margin: -200px 0 0 -200px;*/
                width: 600px;
            }
        </style>
        <script src="jquery.js"></script>
        <script src="raphael.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                var project = "<?php print $project ?>";
                function colorize(id) {
                    var color = 0.55 + id / (2*4);;
                    return "hsb(" + color + ", .75, .8)"
                }
                
                var r = Raphael("holder");

                r.customAttributes.segment = function (x, y, r, a1, a2, id) {
                    var flag = (a2 - a1) > 180;
                    a1 = (a1 % 360) * Math.PI / 180;
                    a2 = (a2 % 360) * Math.PI / 180;
                    return {
                        path: [["M", x, y], ["l", r * Math.cos(a1), r * Math.sin(a1)], ["A", r, r, 0, +flag, 1, x + r * Math.cos(a2), y + r * Math.sin(a2)], ["z"]],
                        fill: colorize(id)
                    };
                };

                function animate(ms) {
                    var start = 0,
                        val;
                    for (i = 0; i < ii; i++) {
                        val = 360 / total * data[i];
                        paths[i].animate({segment: [300, 300, 150, start, start += val, i]}, ms || 1500, "bounce");
                        paths[i].angle = start - val / 2;
                    }
                }
                var ii = 4;
                var data = Array(),
                    paths = r.set(),
                    total,
                    start,
                    bg = r.circle(300, 300, 0).attr({stroke: "#fff", "stroke-width": 4});

                total = 0;
                for (var i = 0; i < ii; i++) {
                    data[i] = 10;
                    total += data[i];
                }
                start = 0;
                for (i = 0; i < ii; i++) {
                    var val = 360 / total * data[i];
                    paths.push(r.path().attr({segment: [300, 300, 1, start, start + val, i], stroke: "#fff"}));
                    start += val;
                }
                bg.animate({r: 151}, 1000, "bounce");
                animate(1000);
                var t = r.text(300, 100, "Run Statistics for " + project).attr({font: '100 20px "Helvetica Neue", Helvetica, "Arial Unicode MS", Arial, sans-serif', fill: "#fff"});
                
                var labelBox = Array();
                for(i = 0; i < ii; i++) {
                    var labelName = "";
                    switch(i) {
                        case 0:
                            labelName = "Finished";
                            break;
                        case 1:
                            labelName = "Stopped";
                            break;
                        case 2:
                            labelName = "Running";
                            break;
                        case 3:
                            labelName = "Failed";
                            break;
                    }
                    var txt = r.text(500,100 + i*30, labelName).attr({"text-anchor": "start", font: '100 20px "Helvetica Neue", Helvetica, "Arial Unicode MS", Arial, sans-serif', fill: "#fff"});
                    labelBox[i] = r.circle(480,100 + i * 30,10).attr({fill: colorize(i), stroke: "#fff"});
                }
                
                function updateData() {
                    $.getJSON("results.php?project=<?php print $_GET["project"]; ?>",  function(inData) {
                        console.log(inData);
                        data[0] = parseInt(inData.finished);
                        data[1] = parseInt(inData.stopped);
                        data[2] = parseInt(inData.running);
                        data[3] = parseInt(inData.failed);
                        total = 0;
                        for(i in data) {
                            if(isNaN(data[i])) {
                                data[i] = 0;
                            }
                            total += data[i];
                        }
                        animate();
                    });
                }
                window.setInterval(updateData, 3000);
                updateData();
            };
        </script>
    </head>
    <body>
        <div id="holder"></div>
        <p id="copy"><a href="http://computationalphysics.net/">Computational Physics</a> Run Statistics WordPress plugin using <a href="http://raphaeljs.com/">Raphaël</a> JavaScript Vector Library</p>
    </body>
</html>