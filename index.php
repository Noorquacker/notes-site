<?php

function startsWith ($string, $startString) {
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

/* No longer using PHP Parsedown

include 'Parsedown.php';
include 'ParsedownExtended.php';
//include 'ParsedownMath.php';
//$Parsedown = new ParsedownMath(['math'=>['matchSingleDollar'=>true]]);
//$Parsedown = new ParsedownMath();
//$Parsedown = new ParsedownExtended(['math'=>['single_dollar'=>true]]);
$Parsedown = new Parsedown();
$Parsedown->setMarkupEscaped(true);

*/

if(!isset($_GET['path'])) {
	$path = 'source';
}
else {
	if(!file_exists($_GET['path']) && !is_dir($_GET['path'])) {
		$path = 'source/';
	}
	else {
		$path = $_GET['path'];
	}
}


// no hacking
if(realpath($path) !== false) {
	if(!startsWith(realpath($path), '/usr/share/nginx/html/www.nqind.com/notes/source')) {
		$path = 'source/';
	}
}

// CLEAN THE REALPATH
$path = str_replace(getcwd() . '/', '', $path);
// echo $path;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Quacker Notes</title>

	<link href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/styles/vs2015.min.css" rel="stylesheet"/>
	<script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.4.0/build/highlight.min.js"></script>
	<script>hljs.initHighlightingOnLoad();</script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.13.18/dist/katex.min.css" integrity="sha384-zTROYFVGOfTw7JV7KUu8udsvW2fx4lWOsCEDqhBreBwlHI4ioVRtmIvEThzJHGET" crossorigin="anonymous">
	<script defer src="https://cdn.jsdelivr.net/npm/katex@0.13.18/dist/katex.min.js" integrity="sha384-GxNFqL3r9uRJQhR+47eDxuPoNE7yLftQM8LcxzgS4HT73tp970WS/wV5p8UzCOmb" crossorigin="anonymous"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/katex@0.13.18/dist/contrib/auto-render.min.js" integrity="sha384-vZTG03m+2yp6N6BNi5iM4rW4oIwk5DfcNdFfxkk9ZWpDriOkXX8voJBFrAO7MpVl" crossorigin="anonymous" onload="renderMathInElement(document.body);"></script>
	<!-- screw mathjax all my homies use katex -->
	<!--script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script><script type="text/javascript">MathJax.Hub.Config({"showProcessingMessages" : false,"messageStyle" : "none","tex2jax": { inlineMath: [ [ "$", "$" ] ] }});</script-->

	<!-- haha kyogi go brrrr -->
	<link rel="stylesheet" href="css/all.css">


<!-- RANDOM JAVASCRIPT I FOUND FROM SOME STRANGE SITE!!!!! https://kevcaz.github.io/notes/hugo/katex_and_goldmark/ -->

<script>
    const macros = {};
    document.addEventListener("DOMContentLoaded", function() {
        renderMathInElement(document.body, {
            throwOnError: false,
            macros: {},
            delimiters: [
                {left: "$$", right: "$$", display: true},
                {left: "$", right: "$", display: false}
            ]
        });
    });
</script>

<!-- I stole this from Remarkable!!! Thank you guys :) -->
<!--style type="text/css">
body,table tr{background-color:#fff}table tr td,table tr th{border:1px solid #ccc;text-align:left;padding:6px 13px;margin:0}pre code,table,table tr{padding:0}hr,pre code{background:0 0}body{font:16px Helvetica,Arial,sans-serif;line-height:1.4;color:#333;word-wrap:break-word;padding:10px 15px}strong,table tr th{font-weight:700}h1{font-size:2em;margin:.67em 0;text-align:center}h2{font-size:1.75em}h3{font-size:1.5em}h4{font-size:1.25em}h1,h2,h3,h4,h5,h6{font-weight:700;position:relative;margin-top:15px;margin-bottom:15px;line-height:1.1}h1,h2{border-bottom:1px solid #eee}hr{height:0;margin:15px 0;overflow:hidden;border:0;border-bottom:1px solid #ddd}a{color:#4183C4}a.absent{color:#c00}ol,ul{padding-left:15px;margin-left:5px}ol{list-style-type:lower-roman}table tr{border-top:1px solid #ccc;margin:0}table tr:nth-child(2n){background-color:#aaa}table tr td :first-child,table tr th :first-child{margin-top:0}table tr td:last-child,table tr th :last-child{margin-bottom:0}img{max-width:100%}blockquote{padding:0 15px;border-left:4px solid #ccc}code,tt{margin:0 2px;padding:0 5px;white-space:nowrap;border:1px solid #eaeaea;background-color:#f8f8f8;border-radius:3px}pre code{margin:0;white-space:pre;border:none}.highlight pre,pre{background-color:#f8f8f8;border:1px solid #ccc;font-size:13px;line-height:19px;overflow:auto;padding:6px 10px;border-radius:3px}
</style-->
<link rel="stylesheet" href="github-dark.css">


</head>
<body id="MathPreviewF">

<?php

/*
$fname = '/tmp/math3304/4.4.md';
$f = fopen($fname,'r');
$fsize = filesize($fname);
$text = fread($f, $fsize);
fclose($f);
*/
$text = "# Notes that I host\n\nThese notes might suck!  \nIf you think these notes are sussy contact me at [this email](mailto:noorquacker@nqind.com) and you should join my minecraft server at <a>mc.nqind.com</a> or [join my discord](http://discord.gg/KbJG69ykA5)\n\n";

if(is_dir($path)) {
	$text .= "You are in folder `" . $path . "`\n\n";
	$dir = scandir($path);
	$text .= "<i class=\"fas fa-level-up-alt\"></i> [Go Back a folder](index.php?path=" . urlencode(realpath($path . "/..")) . ")\n\n";

	// We want folders FIRST
	$folders = array();
	$files = array();
	foreach($dir as $i) {
		if(is_dir($path . '/' . $i)) {
			array_push($folders, $i);
		}
		else {
			array_push($files, $i);
		}
	}
	foreach(array_merge($folders, $files) as $i) {
		if(substr($i, 0, 1) != '.') {
			$text .= '- ' . (is_dir($path . '/' . $i) ? '<i class="far fa-folder"></i> ' : '<i class="far fa-file-alt"></i> ') . '[' . $i . '](index.php?path=' . urlencode($path) . '/' . urlencode($i) . ')' . "\n"; 
		}
	}
}
else if (file_exists($path)){
	if(substr($path, -3, 3) == '.md') {
		$f = fopen($path,'r');
		if($f === false) {
			$text .= "Failed to open file";
		}
		else {
			$fsize = filesize($path);
			$text .= "You are viewing `" . $path . "`\n\n\n" . fread($f, $fsize);
			fclose($f);
		}
	}
	else {
		$text .= "## Ayo you're not allowed to read non-markdown files";
	}
}
else {
	$text .= "HEY THIS IS NEITHER A FOLDER NOR A FILE WTF";
}

//echo $Parsedown->text($text);

/*
Why yes, I'm going to call a Python script in order to get the exact same experience as my notes editor
How could you tell?
*/
$descspec = array(
	0 => array('pipe', 'r'),
	1 => array('pipe', 'w'),
	2 => array('file', '/tmp/nqind.com-notes-index.php-markdown-stderr.txt', 'a')
);
$pymark = proc_open(['python3', 'to-md.py'], $descspec, $pipes);
fwrite($pipes[0], $text);
fclose($pipes[0]);
echo stream_get_contents($pipes[1]);
proc_close($pymark);


?>
</body>
</html>
