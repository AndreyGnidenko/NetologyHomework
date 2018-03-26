<html lang="en">
    <head>
        <title>Test upload</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
    
        <form name="newsUpload" method="POST">
        
            <fieldset>
                <legend>Adding news</legend>
                <br/>

                Title <input name="title" type="input"/><br/><br/>
                Text <input name="text" type="input" /><br/><br/>
                Tags <select name="tags"> <option value="Tag1"/> <option>Tag1</option> <option>Tag2</option> <option>Tag3</option> <option>Tag4</option></select> <br/> <br/>
                <input type="submit" name="upload" value="Upload"/>
                <input type="submit" name="back" value="Back"/>
            </fieldset>
        </form>
    
        <?php
            require_once('functions.php');    
            require_once('news.php');
        
            if (array_key_exists("upload", $_POST))
            {
                var_dump($_POST);
                
                $news = new News($_POST['title']);
                $news->addTag($_POST['tags']);
                $news->setContent($_POST['text']);
                
                $serializedNews = serialize($news).PHP_EOL;
                
                $fileName = __DIR__.'/news.dat';
                
                file_put_contents($fileName, $serializedNews, FILE_APPEND);
                
                redirect('index');
            }
            else if (array_key_exists("back", $_POST))
            {
                redirect('index');
            }
        ?>

     </body>
</html>