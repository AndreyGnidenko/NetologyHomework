<html lang="en">
    <head>
        <title>Test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        
        <h2>News</h2> 
        
        <?php
            require_once('news.php');
        
            $newsFileName = __DIR__.'/news.dat';
            if (file_exists($newsFileName))
            {
                $newsFileContents = file_get_contents($newsFileName);
                $newsArray = explode (PHP_EOL, $newsFileContents);
                
                foreach ($newsArray as $newsStr)
                {
                    $news = unserialize($newsStr);
                    
                    if (is_a($news, 'News'))
                    {
                        echo $news->getFullText();
                    }
                    
                    echo '<br/><br/>';
                }
            }                
        ?>
        
        <h2> Main test dashboard  </h2>
        <form name="inputForm" method="GET">
            <fieldset>
                <br/>
                <br/>
                <input type="submit" name="newsupload" value="Upload news"/>
                <input type="submit" name="upload" value="Upload test"/>
                <input type="submit" name="pass" value="Pass test"/>
            </fieldset>
        </form>
     </body>
</html>
     
<?php

    require_once('functions.php');
    require_once('news.php');
    
    if (isset($_GET['upload'])) 
    {
        redirect('admin');
    }
    else if (isset($_GET['pass']))
    {
        redirect('list');
    }
    else if (isset($_GET['newsupload']))
    {
        redirect('newsupload');
    }
?>