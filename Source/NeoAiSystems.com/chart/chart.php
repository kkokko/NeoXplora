<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'></title>
    <!-- link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" /-->
    <script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.js"></script>
 <script>
   $qry= jQuery.noConflict();
   </script>
  <?php 
    $id = isset($_REQUEST['id'])?$_REQUEST['id']:0; 
    $width = isset($_REQUEST['width'])?$_REQUEST['width']:0;
  ?>	
    <script type="text/javascript">
        $qry(document).ready(function ($) {
            // prepare the data
            var source =
            {
                datatype: "csv",
                datafields: [
                    { name: 'percentage' },
                    { name: 'guess1' },
                    { name: 'guess2' },
					{ name: 'guess3' }
                ],
                url: 'ali.php?id=<?php echo $id; ?>'
//                url: 'ali.txt'				
            };

            var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });

            // prepare jqxChart settings
            var settings = {
                title: "Representation accuracy vs Prediction DB size",
                description: "",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 10, top: 5, right: 10, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                categoryAxis:
                    {
                        dataField: 'percentage',
                        formatFunction: function (value) { 
                            return value;
                        },
                        toolTipFormatFunction: function (value) {
                            return value;
                        },
                        type: 'number',
                        
                        showTickMarks: true,
                        tickMarksInterval: 1,
                        tickMarksColor: '#888888',
                        unitInterval: 1,
                        showGridLines: false,
						description: '% of sentences used',
                        gridLinesInterval: 3,
                        gridLinesColor: '#888888',
						displayValueAxis: true,
                        unitInterval: 10,
                        minValue: 10,
                        maxValue: 100,						
                        valuesOnTicks: false
						
                    },
                colorScheme: 'scheme04',
                seriesGroups:
                    [
                        {
                            type: 'line',
                            valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                maxValue: 100,
                                displayValueAxis: true,
								gridLinesInterval: 3,	
		                        showGridLines: false,															
                                description: 'Accuracy (%)',
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                            series: [
                                    { dataField: 'guess1', displayText: 'Text' },
                                    { dataField: 'guess2', displayText: 'POS' },
									{ dataField: 'guess3', displayText: 'Hybrid' }									
                                ]
                        }
                    ]
            };

            // setup the chart
            $('#jqxChart').jqxChart(settings);

        });
    </script>
</head>
<body class='default'>
    <div id='jqxChart' style="width:<?php echo ($width-(($width*50)/100));?>px; height:400px">
    </div>
</body>
</html>
