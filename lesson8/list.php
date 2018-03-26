<?php
    require_once('functions.php');
    $tests = getTests();
?>

<html lang="en">
    <head>
        <title>Test choice</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
   
        <p>Please, enter your name and select test</h2>
        <form name="selectTest" method="GET" action="test.php">
        
            <fieldset>
                <legend>Test selection</legend>
                <br/>
                
                Please, enter your name <input type="text" name="username" size="40"> </input> 
                <br/>
                <br/>
                
                <select name="testSelect">
                    <?php foreach ($tests as $test): ?>
                        <option><?php echo $test['testName'] ?> </option>
                    <?php endforeach;?>
                </select>
                    
                <br/>
                <br/>
                
                <input type="submit" name="pass" value="Pass test"/>
            </fieldset>
        </form>
     </body>
</html>
     