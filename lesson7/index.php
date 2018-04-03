<html lang="en">
    <head>
        <title>Test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <h2> Main test dashboard  </h2>
        <form name="inputForm" method="GET">
            <fieldset>
                <br/>
                <br/>
                <input type="submit" name="upload" value="Upload test"/>
                <input type="submit" name="pass" value="Pass test"/>
            </fieldset>
        </form>
     </body>
</html>
     
<?php

    require_once('functions.php');
    
    if (isset($_GET['upload'])) 
    {
        redirect('admin');
    }
    else if (isset($_GET['pass']))
    {
        redirect('list');
    }
?>