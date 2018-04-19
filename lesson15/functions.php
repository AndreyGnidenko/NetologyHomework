<?php

    function redirect ($scriptName)
    {
        header("Location: $scriptName.php");
        die;
    }
    
    function redirectWithParams ($scriptName, $params)
    {
        header("Location: $scriptName.php?".http_build_query($params));
        die;
    }
    
    function prepareInput ($inStr)
    {
        $outStr = htmlspecialchars (stripslashes($inStr));
        return trim($outStr);
    }
?>