<?php
//{
//  username:
//    {password : password, favorites : [list of favs]}
//}
$json_data = file_get_contents('data.txt');
$json_data = json_decode($json_data);


if (isset($_POST['username']) and isset($_POST['password'])){
    //Get user info
    $username = $_POST['username'];
    if ($_POST['password'] !=  $json_data->$username->password){
        echo "<script>alert(\"Wrong password\"); window.location = '/login.html';</script>";
    }


}
else if (isset($_POST["\username"]) and isset($_POST["txtNewPassword"]) and isset($_POST["txtConfirmPassword"])){
    echo "<script>alert(\"Creating user\");</script>";
    if ($_POST['txtNewPassword'] == $_POST['txtConfirmPassword']){
        //create new user
        //check user name isnt taken
        $username = $_POST['username'];
        if (isset($json_data->$username)){
            echo "<script>alert(\"Username already taken, please re-register with another name\"); window.location = '/login.html';</script>";
        }
        else{
            $json_data->$username->password = addslashes($_POST['password']);
            $json_data->$username->favorites = [];
            $fp = fopen('data.txt', 'w');
            fwrite($fp, json_encode($json_data));
            fclose($fp);
        }
    }
    else{
        echo "<script>alert(\"Passwords did not match\"); window.location = '/login.html';</script>";
    }
}
else{
    echo var_dump($_POST);
    //echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" >";
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
    <script type = "application/javascript">
        function loadJSON(person){
            var link = "";
            switch(person) {
                case "MLB":
                    link = "http://www.espn.com/espn/rss/MLB/news";
                    break;
                case "NHL":
                    link = "http://www.espn.com/espn/rss/NHL/news";
                    break;
                default:
                    link = "http://www.espn.com/espn/rss/NBA/news";
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

                if (http_request.readyState == 4  ){
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
        // console.log("Entering Init");


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

            //present the item as HTML
            var line  = '<li class="article">';
            line += '<div class="bs-callout bs-callout-danger">';
            line += "<input type=\"button\" class='fav-txt' value=\"Favorite\">";

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
    </script>
    </div>
        <body>
        <div>
            <ul class="nav">
                <li class="nav-li">Hello, USERNAME</li>

                <li class="select_feed">
                    <select id='option' onchange="getNew();">
                        <option value='NBA'>NBA</option>
                        <option value='NHL'>NHL</option>
                        <option value='MLB'>MLB</option>
                    </select>
                </li>
                <li class="nav-li-right">Select Feed:</li>

            </ul>
        </div>
        <div class = "">

        </div>
        <div id="header">
            <h1 class="text-center" id="title">NBA News</h1>
<!--            <img id="logo" />-->
        </div>
        <div id="content" style="width: 70%; margin: auto;" class="">
            <p>No data has been loaded.</p>
        </div>



        </body>
    </html>