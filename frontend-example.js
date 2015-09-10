$(function(){
	//When page ready, get data
	getData();
});

function getData()
{
	//Call your PHP wrapper here
	$.get("/svc/keen/example.php").success(showData);
}

function showData(data)
{
	//Set your query names and nice names here
	data = JSON.parse(data);
	var $metrics = $('#metrics');
	var queries = ['TotalActivations', 'UniqueActivators', 'UniqueExporters', 'TotalExports'];
	var niceNames = ['Opened this many times', 'Total unique users', 'Users who exported', 'Total images exported'];

	for(qx in queries)
	{
		var queryName = queries[qx];
		var niceName = niceNames[qx];
		var d = data[queryName];
		var thisDiv = divFor(queryName, niceName, d);
		$metrics.append(thisDiv);
	}
}

function tagOf(tag, text)
{
	return '<' + tag + '>' + text + '</' + tag + '>';
}

function divFor(id, text, data)
{
	var nameSpan = tagOf('p', text);
	var dataSpan = tagOf('span', data);
	return "<div class='query' id='" + id + "'>" + nameSpan + dataSpan + "</div>";
}
