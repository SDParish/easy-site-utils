<?php


function http_post_curl($post,$url,$return=false){
	//url-ify the data for the POST
	foreach($post as $key => $value){
		$value=urlencode($value);
		$post_string.=$key.'='.$value.'&';
	}
	rtrim($post_string,'&');

	//open connection
	$ch=curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($post));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
	if ($return){
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	}

	//execute post
	$result=curl_exec($ch);

	//close connection
	curl_close($ch);
	return $result;
}

function http_stream($url,$data,$content='post'){
	switch ($content){
		case 'post':
			$data=http_build_query($data);
			$content_type="application/x-www-form-urlencoded";
		break;
		case 'xml':
			$content_type='text/xml';
		break;
	}
	$options=array(
		'http'=>array(
			'header'=>"Content-type: $content_type\r\n",
			'method'=>'POST',
			'content'=>$data,
		),
	);
	$context=stream_context_create($options);
	$result=file_get_contents($url,false,$context);
	return $result;
}