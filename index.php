<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSS Feed</title>
<html>
<head>
    <meta content = "text/html; charset = ISO-8859-1" http-equiv = "content-type">
    <script>src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"</script>
    <script type = "application/javascript">
        function loadJSON(person){
            switch(person) {
                case "MLB":
                    link =
                    break;
                case "NHL":
                    link =
                    break;
                default:
                    link =
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
                        document.getElementById("Name").innerHTML = "Error";
                        document.getElementById("Country").innerHTML = "Error";
                    }
                    // jsonObj variable now contains the data structure and can
                    // be accessed as jsonObj.name and jsonObj.country.
                    document.getElementById("Name").innerHTML = jsonObj.name;
                    document.getElementById("Country").innerHTML = jsonObj.country;
                }
            }

            http_request.open("GET", data_file, true);
            http_request.send();
        }

    </script>

    <div class = "central">
        <select id='option'>
            <option value='NHL'>NHL</option>
            <option value='NBA'>NBA</option>
            <option value='MLB'>MLB</option>
        </select>
        <button id="select_person" type = "button">Get selected</button>
        <script>
            var select_person = document.getElementById("select_person");
            select_person.onclick = function()
            {
                console.log(document.getElementById("option").value);
                loadJSON(document.getElementById("option").value);
            };


            //start RSS
            //this forces javascript to conform to some rules, like declaring
            "use strict";
            var url = "http://www.espn.com/espn/rss/NBA/news";
            window.onload = function(){
                init(url);
            }
            function init(url){
                //NHL URL for ESPN RSS feed
                console.log("Entering Init");


                document.querySelector("#content").innerHTML = "<b>Loading news...</b>";
                $("#content").fadeOut(250);
                //fetch the data
                $.get(url).done(function(data){xmlLoaded(data);});
            }


            function xmlLoaded(obj){
                console.log("obj = " +obj);
                var items = obj.querySelectorAll("item");

                //show the logo
                var image = obj.querySelector("image")
                var logoSrc = image.querySelector("url").firstChild.nodeValue;
                var logoLink = image.querySelector("link").firstChild.nodeValue;
                $("#logo").attr("src",logoSrc);

                //parse the data
                var html = "";
                for (var i=0;i<items.length;i++){
                    //get the data out of the item
                    var newsItem = items[i];
                    var title
                        = newsItem.querySelector("title").firstChild.nodeValue;
                    console.log(title);
                    var description
                        = newsItem.querySelector("description").firstChild.nodeValue;
                    var link
                        = newsItem.querySelector("link").firstChild.nodeValue;
                    var pubDate
                        = newsItem.querySelector("pubDate").firstChild.nodeValue;

                    //present the item as HTML
                    var line = '<div class="item">';
                    line += "<h2>"+title+"</h2>";
                    line += '<p><i>'+pubDate+'</i> - <a href="'+link+'"target="_blank">See original</a></p>';
                    //line += "<p>"+description+"</p>";
                    line += "</div>";

                    html += line;
                }
                document.querySelector("#content").innerHTML = html;

                $("#content").fadeIn(1000);

            }

            var randomString = function(length) {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                for(var i = 0; i < length; i++) {
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return text;
            }


            $(window).scroll(function() {
                if($(window).scrollTop() == $(document).height()- $(window).height()) {
                    var random = randomString(4);
                    document.querySelector("#random").innerHTML = random;
                }
            });


        </script>
    </div>
        <body>

        <div id="header">
            <img id="logo" /><h1>NBA News</h1>
        </div>
        <div id="content">
            <p>No data has been loaded.</p>
        </div>
        <div id="random">
        </div>


        </body>
    </html>