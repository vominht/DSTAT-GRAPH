$(function() {
    var mychart;
    $(document).ready(function() {
        initiateChart("container");
        parseFile();

    });
    var totalRPS = 0;
    var maxRPS = 0;
    var averageRPS = 0;
    var totalRequests = 0;
    var currentAverageRPS = 0;

    function formatNumberWithComma(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function parseFile() {
        $.ajax({
                url: "/data/layer7.php",
                dataType: "json",
                method: "get",
                cache: false,
                timeout: 1000,
                headers: {},
            })
            .done(function(data) {
                var rps = parseFloat(data);
                var rpsLine = mychart.series[0];
                var shift = rpsLine.data.length > 40;
                var currTime = Date.now();
                totalRPS += rps;
                maxRPS = Math.max(maxRPS, rps);

                currentAverageRPS = averageRPS;
                totalRequests++;

                averageRPS = totalRequests > 0 ? Math.round(totalRPS / totalRequests) : 0;
                var averageRPSLine = mychart.series[1];
                averageRPSLine.addPoint([currTime, averageRPS], true, shift);



                rpsLine.addPoint([currTime, rps], true, shift);
                $("#totalRPS").text("Overall Requests: " + formatNumberWithComma(totalRPS));
                $("#maxRPS").text("Peak Requests: " + formatNumberWithComma(maxRPS));
                $("#averageRPS").text("Average Requests: " + formatNumberWithComma(averageRPS));


                setTimeout(parseFile, 900);
            })
            .fail(function() {
                console.error("API Request Failed");
                var rpsLine = mychart.series[0];
                var currTime = Date.now();
                rpsLine.addPoint([currTime, 0], true, rpsLine.data.length > 40);
                setTimeout(parseFile, 1000);
            });
    }

    function updateThreshold() {



        mychart.series[0].update({
            threshold: currentAverageRPS,
            zones: [
                {
                    value: currentAverageRPS,
                    color: '#ff6968',
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0, "rgba(255, 105, 104, 0)"],
                            [1, "rgba(255, 105, 104, 0.3)"],
                        ],
                    },
                    threshold: Infinity,
                },
                {
                    color: '#00ff86',
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0, "rgba(34, 197, 94, 0.3)"],
                            [1, "rgba(34, 197, 94, 0)"],
                        ],
                    },
                }
            ]
        });
    }


    setInterval(updateThreshold, 2500);

    function calculateRPS(current, previous) {
        if (previous === null) {
            return 0;
        }

        var elapsedTimeInSeconds = 1;
        return (current - previous) / elapsedTimeInSeconds;
    }

    function initiateChart(divid) {
        mychart = Highcharts.chart(divid, {
            chart: {
                type: "area",
                backgroundColor: "#131515",
            },







            title: {
                text: "CONCUBEXIU"
            },
            xAxis: {
                type: "datetime",
                dateTimeLabelFormats: {
                    day: "%a"
                },
                gridLineColor: "#0044D8",
                labels: {
                    style: {
                        color: "#0049A9"
                    }
                },
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Requests Per Second',  
                    style: {
                        color: '#FFFFFF'  
                    }
                },
                labels: {
                    formatter: function() {
                        if (this.value > 1000) {
                            return this.value / 1000 + "k";
                        } else {
                            return this.value;
                        }
                    },
                },
            },
            tooltip: {
                pointFormat: "{series.name}: {point.y:,.0f}"
            },
            credits: {
                enabled: true,
                text: 'BUFFA_DZ',  
                href: "https://t.me/ongnoicuamay",
                style: {
                    color: 'rgba(255, 255, 255, 0.5)',
                    fontSize: '27px',  
                    fontWeight: 'bold', 
                    position: {
                        align: 'left',
                        x: 10,
                        y: -5,
                    },
                },
            },

            colors: [{
                linearGradient: [0, 0, 0, 0],
                stops: [
                    [0, "#437CF0"]
                ]
            }, ],
            plotOptions: {
                area: {
                    color: '#FFFFFF',

                    lineWidth: 2,
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, "#437CF0"],
                            [1, new Highcharts.Color("#437CF0").setOpacity(0).get("rgba")],
                        ],
                    },
                    pointStart: 1940,

                },
                line: {
                    lineWidth: 3,
                    dashStyle: 'longdash',
                },
                series: {
                    marker: {
                        enabled: false, 
                    },
                },
            },
            legend: {
                itemStyle: {
                    color: '#FFFFFF', 
                    fontWeight: 'bold',
                },

            },
            series: [{
                name: "RPS",
                data: [],
                threshold: [],
                lineWidth: 3,
                zones: [
                    {
                        value: [],
                        color: '#ff6968',
                        fillColor: {
                          linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                          stops: [
                            [0, "rgba(255, 105, 104, 0)"],
                            [1, "rgba(255, 105, 104, 0.3)"],
                          ],
                        },

                        threshold: Infinity,
                    },
                    {
                        color: '#00ff86',
                        fillColor: {
                            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                            stops: [
                                [0, "rgba(34, 197, 94, 0.3)"],
                                [1, "rgba(34, 197, 94, 0)"],
                            ],
                        },

                    }
                ]



            },{
                name: "Average RPS",
                data: [],
                type: 'line',
                color: "#FFE53B",



            }],
        });
    }
});
