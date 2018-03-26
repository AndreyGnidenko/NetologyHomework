<html lang="en">
    <head>
        <title>Test upload</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
    
        <?php
        
        ?>
    
        <p>Please, select test file for upload</p>
        <form name="uploadTest" enctype="multipart/form-data" method="POST">
        
            <fieldset>
                <legend>Test file selection</legend>
                <br/>
                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                <input name="testFile" type="file" />
                <br/>
                <br/>
                <input type="submit" name="upload" value="Upload test"/>
                <input type="submit" name="back" value="Back"/>
            </fieldset>
        </form>
     </body>
</html>

<?php

    require_once('functions.php');
    
    if (array_key_exists("upload", $_POST))
    {
        if (array_key_exists("testFile", $_FILES))
        {
            $fileContents = file_get_contents ($_FILES["testFile"]['tmp_name']);
            $testJson = json_decode($fileContents, true);
            
            if (!$testJson)
            {
                echo "Некорректный формат файла";
                die;
            }
            
            $testUploadDir = __DIR__. '/testUploads/';
                    
            $destName = $testUploadDir.$_FILES["testFile"]['name'];
            move_uploaded_file($_FILES["testFile"]['tmp_name'], $destName);
            
            redirect('list');
        }
    }
    else if (array_key_exists("back", $_POST))
    {
        redirect('index');
    }

?>