<?php

/* Timing */

function startTiming(){
	$GLOBALS['start'] = microtime(true);
}

function stopTiming(){
	$duration = (microtime(true) - $GLOBALS['start']);
	return $duration;
}

/* Caching */

function fetchAndCache($qName, $queries)
{
	global $apiRoot;
	
	//Get full URI for query
	$query = $apiRoot . $queries[$qName];
	
	//Load data remotely from keen.io
	$resultJson = file_get_contents($query);
	
	//Put it in cache file
	file_put_contents($qName, $resultJson);
	
	//Return the JSON
	return $resultJson;
}

function readCache($qName)
{
	return file_get_contents($qName);
}

/* Processing queries */

function getResults($queries)
{
	$today_ts = strtotime('today');
	$today = new DateTime("@$today_ts");

	$results = [];
	$numberFromCache = 0;
	
	startTiming();
	
	foreach($queries as $qName => $query)
	{
		$rebuild = false;
		$json = null;
		
		// Analysis phase
		//  Check status of existing cached data
		if (file_exists($qName))
		{
			$lastCheckedTs = filemtime($qName);
			$lastChecked = new DateTime("@$lastCheckedTs");
			$difference = $today->diff($lastChecked);
			
			//Rebuild if cache was made yesterday or before
			$rebuild = ($difference->days > 0);
		}
		else
		{
			//No flag file exists
			$rebuild = true;
		}
		
		// Action phase
		//   Fetch, cache, and return results as appropriate
		if ($rebuild)
		{
			$json = fetchAndCache($qName, $queries);
		}
		else
		{
			$numberFromCache += 1;
			$json = readCache($qName);
		}

		$queryResult = json_decode($json);
		$results[$qName] = $queryResult->result;
	}

	$results['time_taken'] = stopTiming();
	$results['results_from_cache'] = $numberFromCache;
	$results['rebuilt'] = $rebuild;
	
	return $results;
}
