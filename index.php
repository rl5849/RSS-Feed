<?php

date_default_timezone_set('America/New_York');

if(isset($_COOKIE['lastvisit'])) {
    $last = $_COOKIE['lastvisit'];
    setcookie('lastvisit', time(), time()+604800);
    $cookie = "Your last visit was: " . date("m/d/y h:s", $last);

}
else {
    $year = 31536000 + time();
    setcookie('lastvisit', time(), time()+604800);
    $cookie = "Welcome to the site!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content = "text/html; charset = ISO-8859-1" http-equiv = "content-type">
    <title>RSS Feed</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<?php
try{
    $json_data = file_get_contents("data.json");
    $json_data = json_decode($json_data);
}
catch (Exception $e){
    echo $e;
}

session_start();

if (isset($_POST['username']) and isset($_POST['password']) and !isset($_POST["confirmPassword"])){
    //Get user info
    $username = $_POST['username'];
    $password = $_POST['password'];
    foreach ($json_data->USERS as $USER) {
        if ($USER->username == $username){
            if ($_POST['password'] !=  $USER->password){
                echo "<script>alert(\"Wrong password\"); window.location = '/RSS_Feed/login.php';</script>";
            }
            else{

                $_SESSION['username'] = $username;

                $_SESSION['favs'] = $json_data->USERS->$username->favorites;
            }
        }
    }

}
else if (isset($_POST["username"]) and isset($_POST["password"]) and isset($_POST["confirmPassword"])){
    if ($_POST['password'] == $_POST['confirmPassword']){
        //create new user
        //check user name isnt taken
        $username = $_POST['username'];
        if (array_key_exists($username, $json_data->USERS)){
            echo "<script>alert(\"Username taken\"); window.location='/RSS_Feed/login.php';</script>";
            return;
        }
        else{
			/*
			$username = $_POST['username'];
			$data = array("username" => addslashes($username), "password" => addslashes($_POST['password']), "favorites" => array());
            array_push($json_data->USERS, $username);
			$json_data->USERS=>$username=$data;
			*/
			$json_data->USERS[$username] = $data;
			
			
			/*
            $fp = fopen('data.json', 'w');
            fwrite($fp, json_encode($json_data));
            fclose($fp);

            $_SESSION['username'] = $username;
            $_SESSION['favs'] = [];
			*/
	
        }
    }
    else{
        echo "<script>alert(\"Passwords don't match\"); window.location = '/RSS_Feed/login.php';</script>";
    }
}
else if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

else{
  echo "Login pls";
  //echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" >";
}

?>
    <script type = "application/javascript">
            var favs = [<?php
                    if (isset($_SESSION['favs'])){
                        foreach($_SESSION['favs'] as $fav){
                            echo "'" . $fav ."',";
                        }
                    }
                    else{echo "";}?>]

            var favs_urls = [];
            for (i = 0; i < favs.length; i++) {
                var artical = JSON.parse(favs[i]);
                favs_urls.push(artical.url);
            }


            function loadJSON(choice){
            var link = "";
            switch(choice) {
                case "MLB":
                    link = "http://www.espn.com/espn/rss/MLB/news";
					init(link);
                    break;
                case "NHL":
                    link = "http://www.espn.com/espn/rss/NHL/news";
					init(link);
                    break;
                default:
                    link = "http://www.espn.com/espn/rss/NBA/news";
					init(link);
					break;
            }
			
            var http_request = new XMLHttpRequest();
            try{
                // Opera 8.0+, Firefox, Chrome, Safari
                http_request = new XMLHttpRequest();
            }catch (e){
                // Internet Explorer Browsers
                try{
                    http_request = new ActiveXObject("Msxml2.XMLHTTP");

                }catch (e) {

                    try{
                        http_request = new ActiveXObject("Microsoft.XMLHTTP");
                    }catch (e){
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                    }

                }
            }

            http_request.onreadystatechange = function(){

                if (http_request.readyState == 4){
                    // Javascript function JSON.parse to parse JSON data

                    try{
                        var jsonObj = JSON.parse(http_request.responseText);
                    }
                    catch(err){
                        console.log(err);

                    }
                    // jsonObj variable now contains the data structure and can
                    // be accessed as jsonObj.name and jsonObj.country.

                }
            }

            http_request.open("GET", data_file, true);
            http_request.send();
        }


    //start RSS
    //this forces javascript to conform to some rules, like declaring
    "use strict";
    var url = "http://www.espn.com/espn/rss/NBA/news";
    window.onload = function(){
        init(url);
    };
    function init(url){
        //NHL URL for ESPN RSS feed
        console.log("Entering Init");

        document.querySelector("#content").innerHTML = "<b>Loading news...</b>";
        $("#content").fadeOut(250);
        //fetch the data
        $.get(url).done(function(data){xmlLoaded(data);});
    }




    function xmlLoaded(obj){
        // console.log("obj = " +obj);
        var items = obj.querySelectorAll("item");

        //show the logo
        var image = obj.querySelector("image")
        var logoSrc = image.querySelector("url").firstChild.nodeValue;
        var logoLink = image.querySelector("link").firstChild.nodeValue;

            $("#logo").attr("src",logoSrc);

        //parse the data
        var html = "";
        html += "<ul >";

        for (var i=0;i<items.length;i++){
            //get the data out of the item
            var newsItem = items[i];
            var title
                = newsItem.querySelector("title").firstChild.nodeValue;
            // console.log(title);
            var description
                = newsItem.querySelector("description").firstChild.nodeValue;
            var link
                = newsItem.querySelector("link").firstChild.nodeValue;
            var pubDate
                = newsItem.querySelector("pubDate").firstChild.nodeValue;

            var fav_txt = "";

            if(favs_urls.indexOf(encodeURIComponent(link)) > -1){
                fav_txt = "Favorited";
            }else{
                fav_txt = "Favorite";
            }



            //present the item as HTML
            var line  = '<li class="article">';
            line += '<div class="bs-callout bs-callout-danger">';
            line += "<input type=\"button\" class='fav-txt' onclick='favorite(\"" + encodeURIComponent(title) + "\", \"" + encodeURIComponent(link) + "\");' value=\"" + fav_txt + "\">";

            line += "<h2 class='article-title'>"+title+"</h2>";
            line += '<p class=\'article-title\'><i>'+pubDate+'</i> - <a href="'+link+'"target="_blank">See original</a></p>';
            //line += "<p>"+description+"</p>";
            line += '</div>';
            line += "</li>";

            html += line;
        }
        document.querySelector("#content").innerHTML = html;

        $("#content").fadeIn(1000);

    }

    function getNew() {
        var e = document.getElementById("option");
        var strUser = e.options[e.selectedIndex].value;
        var url = "http://www.espn.com/espn/rss/" + strUser + "/news";

        document.getElementById("title").innerText = strUser + " News"
        init(url);
    }

    var list = $(".list-group-item");
    for(var i = 0; i < list.length; i++) {
        list[i].hover(function () {
            $(this).classList.add("active");
        })
    }


    function favorite(title, url) {
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            dataType: 'text',
            data: {'username': '<?php echo $username?>', 'fav-title': title, 'fav-url' : url},
            contentType: "application/x-www-form-urlencoded",
            async: false,
            success: function (result) {
                favs_urls.push(url);
                console.log(result);
            },
            error: function (msg) {
                console.log(msg.responseText);
            },
            complete: function () {
                $('#loading-image').hide();
            }
        });
    }

    function showFavorites() {
        var html = "<option>Choose</option>";

        for (i = 0; i < favs.length; i++) {
            var artical = JSON.parse(favs[i]);
            favs_urls.push(artical.url);
        }


        for (i = 0; i < favs.length; i++) {
            var artical = JSON.parse(favs[i]);
            var line = "";
            line += "<option value=" + decodeURIComponent(artical.url) +">" + decodeURIComponent(artical.title) +"</option>";
            html += line;
        }

        document.getElementById("favorites").innerHTML = html;
        $("#favorites").fadeIn(1000);
    }

    $(document).ready(function () {
        showFavorites();
    })




    </script>
        <div>
            <ul class="nav">
                <li class="nav-li">Hello, <?php echo $username?></li>
				
				<li class="nav-li-right">
					<div>
						<input onclick="loadJSON('NBA');" type="checkbox" id="addSource" name="addNBA" value="addNBA">
						<label style='color: white' for="addSource">NBA</label>
						<input onclick="loadJSON('NHL');" type="checkbox" id="addSource" name="addNHL" value="addNHL">
						<label style='color: white' for="addSource">NHL</label>
						<input onclick="loadJSON('MLB');" type="checkbox" id="addSource" name="addMLB" value="addMLB">
					</div>
				</li>
				<br>
				
                
                <li class="nav-li">Your Favorites: </li>
                <select onchange='location = this.value;' style='width: 10%;' id="favorites">

                </select >
                <a class="nav-li-right" href="logout.php" class="nav-li" >Logout</a>

            </ul>
        </div>



        <div id="header">
            <h1 class="text-center" id="title">ESPN News</h1>
<!--            <img id="logo" />-->
        </div>
        <div id="content" style="width: 70%; margin: auto;" class="">
            <p>No data has been loaded.</p>
        </div>
        <center><h4><?php echo $cookie;?></h4></center>



    </body>
</html>
