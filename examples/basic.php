<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>AirTemplate Examples</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<main>

<?php

    require './lib/bootstrap.php';
    require './lib/benchmark.php';

    use AirTemplate\Template;

    $templates = array(
        'table' => '<table id="{{id}}">
<thead>
<tr>
{{thead}}
</tr>
</thead>
<tbody>
{{tbody}}
</tbody>
</table>
',
        'th' => '<th>{{item}}</th>',
        'tr' => '<tr>
<td>{{id}}</td>
<td>{{value}}</td>
<td>{{desc}}</td>
</tr>',
    );

    function render($data)
    {
        global $engine;

        // prepare table data
        $table_data = array(
            'id' => 'testdata',
            'thead' => $engine->each('th', array('ID', 'Value', 'Description')),
            'tbody' => $engine->each('tr', $data, array('desc' => 'htmlspecialchars'), n),
        );
        // return rendered table
        return $engine->render('table', $table_data);
    }


    if (isset($_GET['iterations'])) {
        $iterations = is_numeric($_GET['iterations'])
            ? intval($_GET['iterations'])
            : PERF_TEST_ITERATIONS;
        if ($iterations > 100000) {
            $iterations = PERF_TEST_ITERATIONS;
        }
    } else {
        $iterations = 0;
    }

    // create testdata
    $rows = 10;
    $testdata = new TestdataGenerator($rows);

    // create the template engine object
    get_mem_usage('Start');
    $engine = new Template;
    get_mem_usage('Instance created');
    // load templates (the templates will be parsed immediatly)
    $engine->setTemplates($templates);
    get_mem_usage('Templates loaded');

    echo '<h2>Basic</h2>', n;
    echo '<p>Shows the basic use of AirTemplate.</p>', n;

    echo n, '<pre>', n, n;
    get_mem_usage('Before render');
    echo render($testdata);
    get_mem_usage('After render');
    echo n, '</pre>', n;

    if ($iterations > 0) {
        benchmark($testdata, $iterations);
    }

    get_mem_usage_report();
    echo '<h3>Run benchmark</h3>', n;
    echo '<p><a href="./basic.php?iterations=1000">Run benchmark test 1000 times</a></p>', n;
    echo '<p><a href="./basic.php?iterations=10000">Run benchmark test 10000 times</a></p>', n;

?>

</main>
<footer>
<p>&copy; 2016 Andreas Blaser</p>
</footer>
</body>
</html>