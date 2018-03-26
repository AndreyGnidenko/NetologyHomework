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
    
    function getTestFileNames ()
    {
        $testUploadDir = __DIR__. '/testUploads/';
        $testFileNames = array_diff(scandir($testUploadDir), array('..', '.'));
        
        return $testFileNames;
    }
    
    function getTestName ($fileName)
    {
        $testFileJson = json_decode (file_get_contents($fileName), true);
        return $testFileJson['testName'];
    }
    
    function getTests ()
    {
        $testFileNames = getTestFileNames();
    
        $tests = array();
    
        foreach($testFileNames as $testFileName)
        {
            $testFileJson = json_decode (file_get_contents($testFileName), true);
            
            $test = array ();
            $testName = $testFileJson['testName'];
            
            $tests[$testName] = $testFileJson;
        }
        return $tests;
    }
?>