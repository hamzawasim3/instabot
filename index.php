<!DOCTYPE HTML>
<html>
  <head>
    <title>Insta Quote Bot</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="bg"></div>
      <div id="overlay"></div>
      <div id="main">
          <header id="header">
          <h1>Insta Quote Bot <small>v1.0</small></h1>
          <p>Insta Quote Bot is a simple web app that can auto create an image with an inspirational quote and a background and then auto post the image to its Instagram Page.</p>
          <p>This bot post quotes every hour!</p><br>
          <p class="text-center"><code>
				<?php
			    $time = 60 - date("i");
			    if($time == 1){
			        echo "A new quote will be posted on our Instagram Page in the next " . $time . " minute! ";
			    }
			    else if($time == 60){
			        echo "Posting a new quote now!";
			    }
			    else{
				   echo "A new quote will be posted on our Instagram Page in the next " . $time . " minutes! ";
			    }
				?>
			</code></p>
          <nav>
            <ul>
              <li><a href="live_demo.php" class="fa fa-desktop"></a></li>
              <li><a href="https://www.instagram.com/quotes4lifebot/" class="icon brands fa-instagram"></a></li>
            </ul>
          </nav><br>
          <h3></h3>
        </header>

          <footer id="footer">
            <span class="copyright">&copy; <?php echo date("Y"); ?> Made by <a href="https://hamzawasim.net/">Hamza Wasim</a></span>
          </footer>

      </div>
    </div>
    <script>
      window.onload = function() { document.body.classList.remove('is-preload'); }
      window.ontouchmove = function() { return false; }
      window.onorientationchange = function() { document.body.scrollTop = 0; }
    </script>
  </body>
</html>
