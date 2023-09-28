<?
$doc = new DOMDocument();
$doc->load("http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=demoforclient3");
$arrFeeds = array();
foreach ($doc->getElementsByTagName('item') as $node) 
{
	$itemRSS = array 
	( 
		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
		'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
	);
	array_push($arrFeeds, $itemRSS);
}

//echo '<pre>';
//print_r($arrFeeds);
if(count($arrFeeds)>0)
{	
	for($n=0;$n<=0;$n++)
	//foreach($arrFeeds as $arrValue)
	{
		?>
		&raquo;<?=$arrFeeds[$n]['title'];?><br />
		<a href="<?=$arrFeeds[$n]['link']?>" target="_blank" title="News"><?=$arrFeeds[$n]['link']?></a><br />
		<? 
		$date=strtotime($arrFeeds[$n]['date']);
		echo date("l, F d, Y h:i A",$date);	//Sunday, February 07, 2010 9:40 AM
		?><br />
		<? 
	}
}
?>