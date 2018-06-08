# Download your favorite youtube video in PHP
This project is to download your favorite youtube video.


### How it works
- Use browser to save your favorite page to file "youtube.txt".
- Run php.exe favorite-youtube-to-video.php.
- If there is no error, mp4 file will be downloaded in current folder.

### You can view the youtube id of every songs, not download it
```
for ($i = 0; $i < sizeof($songs); $i++) {
    $title = $songs[$i]['playlistVideoRenderer']['title']['simpleText'];
    $title = mb_convert_encoding($title, "big5", "utf-8");
    $title = str_replace([' ', '?', '/'], ['', '', ''], $title);

    $id = $songs[$i]['playlistVideoRenderer']['videoId'];

    echo "$id $title \n";

    $video = sprintf("%s\\%s.mp4", __DIR__, $title);

    //processVideo($id, $title, $video);

    //sleep(60);
}
```
```
GblmaE2RGZ0 方宥心-今夜無伴(2017-05-29最美的歌) 
X2TuRcKcCN4 方宥心-風(2016-11-24最美的歌) 
ImoEv6H17hs 方宥心-烏龍茶(2016-05-18最美的歌) 
2Vp2hCCKcKc 方宥心-傷心的所在(2016-07-06最美的歌) 
R6sli8xU2Y8 方宥心-可憐的戀花再會吧(2017-01-16最美的歌) 
jyJ0EhNsZq8 方宥心-算命(2016-07-08最美的歌) 
KXOD-oVSIC8 方宥心-生蚵仔嫂(2016-07-14最美的歌) 
f9m1sUKrcy4 方宥心-舊夢(2016-11-21最美的歌) 
R4pE-fqU7TY 方宥心、蕭煌奇-出嫁(2016-09-14最美的歌) 
Xttlzmvx1xQ 方宥心-等無人(2016-10-09最美的歌) 
2WIGiVDiK3o 方宥心-心掛意無路用(2016-07-12最美的歌) 
P5KLRO8ip9w 方宥心-春花望露-2017-03-09最美的歌)  
```

### Note
Some files can't be downloaded.
