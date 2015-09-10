<?php

require 'sgKeen.php';

/*
	Configure for your project
*/

// Set the API root
$apiRoot = "https://api.keen.io/3.0/projects/xxxxxxxxxxxxxxxxxx/queries/";

// Set your API key
$apiKey = '...................';

// Set your queries
//   Start it with, for example, 'count?' or 'count_unique?'
//   Use 'api_key={$apiKey}' in the URL for tidiness

$queries = [
	'TotalActivations' => "count?api_key={$apiKey}&event_collection=activations&timezone=UTC",
	'UniqueActivators' => "count_unique?api_key={$apiKey}&event_collection=activations&target_property=device_id&timezone=UTC",
	'UniqueExporters' => "count_unique?api_key={$apiKey}&event_collection=exports&target_property=device_id&timezone=UTC",
	'TotalExports' => "count?api_key={$apiKey}&event_collection=exports&timezone=UTC",
];

$results = getResults($queries);

header("content-type: text/plain");
echo json_encode($results);

?>