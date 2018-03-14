<?php

function downloadVideo($id, $title, $url, $video)
{
    $fp = fopen($video, "w");
    if (!$fp) {
        echo "$id $title can't create video file \n";
        return;
    }

    $ch = curl_init();

    if ($ch === false) {
        echo "$id $title curl_init error \n";
        fclose($fp);
        return;
    }
    
    //$proxy = 'proxy.hinet.net:80';

    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_HEADER , false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
    curl_setopt($ch, CURLOPT_AUTOREFERER , true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.146 Safari/537.36');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    
    $con = curl_exec($ch);
    curl_close($ch);

    if ($con === false) {
        echo "$id $title download video error \n";
        fclose($fp);
        return;
    }

    fclose($fp);
}

function processVideo($id, $title, $video)
{
    $api = sprintf("http://www.youtube.com/get_video_info?video_id=%s", $id);

    $res = file_get_contents($api);

    if ($res === '') {
        echo "$id $title res is null \n";
        return;
    }

    $params = [];
    parse_str($res, $params);

    if (!isset($params['url_encoded_fmt_stream_map'])) {
        echo "$id $title url_encoded_fmt_stream_map error \n";
        return;
    }

    $s = $params['url_encoded_fmt_stream_map'];
    $streams = explode(',', $s);

    $realURL = '';
    for ($i = 0; $i < sizeof($streams); $i++) {
        $params = [];
        parse_str($streams[$i], $params);

        if (!isset($params['url']) || !isset($params['type']) || !isset($params['quality'])) {
            echo "$id $title params error \n";
            return;
        }

        $url = urldecode($params['url']);
        $type = urldecode($params['type']);
        $quality = urldecode($params['quality']);

        if (strpos($type, 'video/mp4') == 0) {
            $realURL = $url;
            if ($quality === 'medium') {
                break;
            }
        }
    }

    if ($realURL === '') {
        echo "$id $title has no mp4 type \n";
        return;
    }

    //echo "$title, $realURL\n";

    downloadVideo($id, $title, $realURL, $video);
}



// "youtube.txt" is the file that you use browser to save the page of your favorite videos.
$html = file_get_contents('youtube.txt');

$pattern = '/window\[\"ytInitialData\"\] = ({.*});/';

if (!preg_match($pattern, $html, $matchs)) {
    echo "can't find favorites \n";
    return;
}

$s = $matchs[1];

$json = json_decode($s, true);

$songs = $json['contents']['twoColumnBrowseResultsRenderer']['tabs'][0]
              ['tabRenderer']['content']['sectionListRenderer']['contents'][0]
              ['itemSectionRenderer']['contents'][0]
              ['playlistVideoListRenderer']['contents'];

for ($i = 0; $i < sizeof($songs); $i++) {
    $title = $songs[$i]['playlistVideoRenderer']['title']['simpleText'];
    $title = mb_convert_encoding($title, "big5", "utf-8");
    $title = str_replace([' ', '?'], ['', ''], $title);

    $id = $songs[$i]['playlistVideoRenderer']['videoId'];

    //echo "$id $title \n";

    $video = sprintf("%s\\%s.mp4", __DIR__, $title);

    processVideo($id, $title, $video);

    sleep(10);
}
