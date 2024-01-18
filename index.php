
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php error_log(" \r\n", 3, 'data/layer7-logs'); ?>
        <title id="titled">CONCUBEXIU | Graph</title>
        <link href="/assets/css/border-gradient.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/assets/js/addons/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/addons/jquery-ui.js"></script>
        <script src="/assets/js/addons/highcharts.js"></script>
        <script src="/assets/js/addons/dark-unica.js"></script>
        <link rel="stylesheet" href="/assets/css/style.css" />
        <script src="/assets/js/addons/sha256.js"></script>

        


    </head>
    <body>
        <script src="assets/js/graph.js"></script>
        <script>
            function copyText(text, label) {
                const el = document.createElement("textarea");
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand("copy");
                document.body.removeChild(el);
            }
        </script>
        <center>
            <div class="a1">
                <font id="ConCu" color="#E0E0E3">
                    <a id="ConCu" style="text-decoration: none; font-weight: 700; font-size: 36px;" class="linear-wipe">Layer 7 | Graph</a><br />
                    <a target="_blank" style="text-decoration: none; font-weight: 700; font-size: 18px;" class="linear-wipe" rel="noopener noreferrer" href="https://t.me/ongnoicuamay">CONCUBEXIU</a><br />
                    <br />
                    <div id="niggers" style="display: none; color: #6b8be4; text-decoration: none; font-weight: 700; font-size: 90px;"></div>
                </font>
            </div>
            <div class="box">
                <script src="assets/js/addons/dark-unica.js"></script>
                <div id="container"></div>
            </div>
            <div class="a2">
                <div class="button-holder">
                    <a class="discover-this-product-button w-inline-block" onclick="copyText(window.location.href)"><div class="discover-gradient">COPY URL</div></a>
                    <div class="button-gradient plugins"></div>
                </div>
            </div>
            <div id="statsForm" class="statsForm">
                <div id="totalRPS" style="color: #fff;">Overall Requests: 0</div>
                <div id="maxRPS" style="color: #fff;">Peak Requests: 0</div>
                <div id="averageRPS" style="color: #fff;">Average Requests: 0</div>
            </div>
            <p></p>
            <div class="button-holder">
                <a class="discover-this-product-button w-inline-block" href="board.html"><div class="discover-gradient">Leaderboard</div></a>
                <div class="button-gradient plugins"></div>
            </div>
        </center>
    </body>
</html>
