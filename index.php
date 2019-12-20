<?php
# YUTAKI GRAPHING DATA FROM DATABASE
# Functions in yutaki_func.php
# Needs SQLITE3 extension and highcharts
# (c) 2019 Js Goetschy
  require_once('yutaki_func.php');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Temperatures from Yutaki</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'spline',
            zoomType: 'x'
        },
        title: {
            text: 'Températures Yutaki S 81 Lazare Carnot - Clamart'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Source: rbpiJS1' :
                    'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: '°C'
            },
	    min: 0
        },
        legend: {
            enabled: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            spline: {
                marker: {
		enabled: false 
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'spline',
	    name: 'Outside Temp',
	    color: 'Blue',
            data: [
		<?php if(isset($_GET["nb_days"])) {
			echo getDataYutaki($_GET["nb_days"], 'temp_out');
			} else {
			echo getDataYutaki(30, 'temp_out');
			}
		?>
            ]},
	    {
	    type: 'spline',
	    name: 'Water Temp In',
	    color: 'Green',
	    data: [
		<?php if(isset($_GET["nb_days"])) {
			echo getDataYutaki($_GET["nb_days"], 'temp_water_in');
			} else {
			echo getDataYutaki(30, 'temp_water_set');
			}
		?>
	    ]},
	    {
	    type: 'spline',
	    name: 'Water Temp Out',
	    color: 'Orange',
	    data: [
		<?php if(isset($_GET["nb_days"])) {
			echo getDataYutaki($_GET["nb_days"], 'temp_water_out');
			} else {
			echo getDataYutaki(30, 'temp_water_out');
			}
		?>
	    ]},
	    {
	    type: 'spline',
	    name: 'Water Temp Set',
	    color: 'Black',
	    data: [
		<?php if(isset($_GET["nb_days"])) {
			echo getDataYutaki($_GET["nb_days"], 'temp_water_set');
			} else {
			echo getDataYutaki(30, 'temp_water_set');
			}
		?>
	    ]}
    ]
    });
});
		</script>
	</head>
	<body>
<script src="./highcharts/code/highcharts.js"></script>
<script src="./highcharts/code/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

	<H2 ALIGN=CENTER>
		<A HREF='index.php?nb_days=1'>[1 jour]</A>
		<A HREF='index.php?nb_days=14'>[2 semaines]</A>
		<A HREF='index.php?nb_days=31'>[1 mois]</A>
		<A HREF='index.php?nb_days=92'>[3 mois]</A>
		<A HREF='index.php?nb_days=366'>[1 An]</A>
	</H2>

	</body>
</html>

