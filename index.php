<!DOCTYPE html>
<html>
<head>
    <title>Check</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div style="position: fixed;z-index: 9999999999;width: 100%;top:0">
    <textarea rows="4" cols="50" id="domPath">
        Here will show the DOM path 
    </textarea>
    <form action="" method="post">
        <input type="url" id="urlId" name="urlId">
        <button type="submit">Submit</button>
    </form>
    </div>
    <script type="text/javascript">
        window.addEventListener("click", function(event) {
        var tg = event.target;
        var value= getXPath(tg);
        console.log(value);
        });

        function getXPath( element )
        {
        var val=element.value;
        //alert("val="+val);
        var xpath = '';
        for ( ; element && element.nodeType == 1; element = element.parentNode )
        {
        //alert(element);
        var id = jQuery(element.parentNode).children(element.tagName).index(element) + 1;
        id > 1 ? (id = '[' + id + ']') : (id = '');
        xpath = '/' + element.tagName.toLowerCase() + id + xpath;
        }
        document.getElementById("domPath").innerHTML = xpath;
        return xpath;
        }
    </script>
</body>
</html>


<?php
    error_reporting(0);
    ini_set('display_errors', 0);
 function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
if (isset($_POST['urlId'])) {
    $url = $_POST['urlId'];
}
$url1 = explode('/', $url);
$url2 = $url1[0]."//".$url1[2];
$result = get_web_page( $url );
$page = $result['content'];
//echo $url2;
$page= preg_replace("#(href\s*=\s*[\"'])(^((?!http).)*$)([^\"'>]+)([\"'>]+)#", 'href="'.$url2.'$2$3', $page);
$page= preg_replace("#(href\s*=\s*[\"'])(/)#", 'href="'.$url2.'$2$3', $page);
$page= preg_replace("#(src\s*=\s*[\"'])(^((?!http).)*$)([^\"'>]+)([\"'>]+)#", 'src="'.$url2.'$2$3', $page);
$page= preg_replace("#(src\s*=\s*[\"'])(/)#", 'src="'.$url2.'$2$3', $page);
echo $page;
?>
