<?php

header( 'Content-Type: text/html; charset=utf-8' );
header( 'Cache-Control: s-maxage=2678400, max-age=2678400' );

$path = $_SERVER['REQUEST_URI'];
$actual_link = 'https://' . $_SERVER['HTTP_HOST'] . $path;
$encUrl = htmlspecialchars( $path );
http_response_code( 410 );

echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="icon" type="image/x-icon" href="https://static.wikioasis.org/images/metawiki/1/18/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>
Wiki Not Found
</title>
<style type="text/css">
* {
    font-family: 'Gill Sans', 'Gill Sans MT', sans-serif;
}
a:link { 
    color: #005b90;
    }
a:visited { 
    color: #005b90;
    }
a:hover { 
    color: #900000;
    }
a:active { 
    color: #900000;
    }
body {
    background-color: white;
    color: #484848
}
h1 {
    color: black;
    margin: 0px;
}
h2 {
    color: #484848
    padding: 0px;
    margin: 0px;
}
p {
    margin-top: 10px;
    margin-bottom: 0px
}
#logo {
    display: block;
    float: left;
    height: 300px;
    width: 250px;
}
#logo > img:nth-child(1) {
    width: 200px;
    right: -20px;
}	   
#center {
    position: absolute;
    top: 50%;
    width: 100%;
    height: 1px;
    overflow: visible
}  
#main {
    position: absolute;
    left: 50%;
    width: 720px;
    margin-left: -360px;
    height: 300px;
    top: -150px
}
#divider {
    display: block;
    float: left;
    background-repeat: no-repeat;
    height: 300px;
    width: 2px;
}
#message {
    padding-left: 10px;
    float: left;
    display: block;
    height: 300px;
    width: 390px;
}
@media (prefers-color-scheme: dark) {
    body {
        background-color: #282828;
    }
    h1, p, h2 {
        color: white;
    }

    a:link, a:visited {
        color: cyan;
    }

}
</style>
<link rel="shortcut icon" href="https://static.wikioasis.org/metawiki/1/18/favicon.ico" />
</head>
<body>

<div id="center"><div id="main">


<div id="logo">
    <img src="https://static.wikioasis.org/metawiki/3/38/WikiOasis_Logo.png" />
</div>
<div id="divider">

</div>

<div id="message">
<h1>ERROR</h1>
<h2>410 &ndash; Wiki Deleted</h2>
<p style="font-style: italic">$actual_link</p>
<p>This wiki has been deleted. <a href="https://meta.wikioasis.org/">Return to charted territory.</a></p>
</div>

</div></div>
</html>
EOF;
die( 1 );

