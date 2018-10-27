<?php 

$website_to_crawl= "https://www.homegate.ch/mieten/immobilien/kanton-zuerich/trefferliste?ep=1";


function get_links($url)
{
	global $all_links;
	$all_links= array();
	$contents= @file_get_contents($url);
	$regexp= "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	global $website_to_crawl;
	preg_match_all("/$regexp/siU", $contents, $matches);
	$path_of_url= parse_url($url, PHP_URL_HOST);

	if (strpos($website_to_crawl, "https://") == true)
	{
		$type= "https://";
	}
	else
	{
		$type= "http://";
	}


	$links_in_array= $matches[2];

	foreach ($links_in_array as $link)
	{
		if (strpos($link, "#") !== false)
		{
			$link= substr($link,0, strpos($link, "#"));
		}

		if (substr($link, 0, 1) == ".")
		{
			$link= substr($link,1);
		}

		if (substr($link, 0, 7) == "http://")
		{
			$link= $link;
		}

		else if (substr($link, 0, 8) == "https://") 
		{
			$link= $link;
		}

		else if (substr($link, 0, 2) == "//") 
		{
			$link= substr($link,2);
		}
		 
		else if (substr($link, 0, 1) == "#") 
		{
			$link= $url;
		}
		else if (substr($link, 0, 7) == "mailto:")
		{
			$link= "[" . $link . "]";
		}
		else if (substr($link, 0, 1) != "/") 
		{
			$link= "$type" .$path_of_url. "/" . $link;
		}
		else 
		{
			$link= "$type" .$path_of_url.$link;
		}


		if (!in_array($link,$all_links))
		{
			array_push($all_links, $link);
		}
	}//ends foreach 
}//ends function get_links

function print_links($all_links,$website_to_crawl)
{
	global $linkscount;
	$linkscount=0;
	echo "for this Link:<br> ".$website_to_crawl." <br> <hr>";

	foreach ($all_links as $currentlink)
	{
		if (strpos($currentlink, "homegate.ch") !== FALSE) 
		{	
		  if(ctype_digit(substr($currentlink,strrpos($currentlink,"/")+1)))
		  { 
			echo "<a href=" . "\"" . "$currentlink" . "\"" . ">$currentlink</a>" . "<br>";
			$linkscount++;
		  }
		}
		elseif(strpos($currentlink, "newhome.ch") !== FALSE) 
		{
			if(strpos($currentlink, "id=") == true)
			{
				echo "<a href=" . "\"" . "$currentlink" . "\"" . ">$currentlink</a>" . "<br>";
				$linkscount++;
			}
		}
	}

	echo "<br><br>There are ".$linkscount." links with IDs found by the crawler <br> <hr><hr>";
}



//first task => a
get_links($website_to_crawl);
print_links($all_links,$website_to_crawl);


//first task => b

$website_to_crawl=str_replace("1","2",$website_to_crawl);
get_links($website_to_crawl);
print_links($all_links,$website_to_crawl);

/////////////////////////////////////////////////////////////////////////////////

//second task => a
$website_to_crawl="https://www.newhome.ch/de/kaufen/suchen/haus_wohnung/kanton_zuerich/liste.aspx?p=1";
get_links($website_to_crawl);
print_links($all_links,$website_to_crawl);


//second task => b
$website_to_crawl=str_replace("1","2",$website_to_crawl);
get_links($website_to_crawl);
print_links($all_links,$website_to_crawl);


?>
