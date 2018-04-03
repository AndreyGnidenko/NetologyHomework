<?php

    require_once('functions.php');
    
    $tests = getTests();
    
    if (!array_key_exists("testSelect", $_GET) || empty($_GET["testSelect"]) || !array_key_exists("username", $_GET) || empty($_GET["username"]))
    {
        redirect('list');
    }
        
    $userName = $_GET["username"];
    $testName = $_GET["testSelect"];
    
    if (!array_key_exists($testName, $tests))
    {
        header($_SERVER['SERVER_PROTOCOL']. ' 404 Not Found');
        echo "Test ", $testName, " does not exist!";  
        die;
    }
    
    $testQuestions = $tests[$testName]['testQuestions'];
    
    $score = 0;
    
?>

<html lang="en">
    <head>
        <title>Test <?php echo $testName ?> </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <h2> Test <?php echo $testName, " for user ", $userName ?> </h2>
        
        <?php if (!isset($_POST['submit'])) : ?>
        
            <form method="post" >
                
                  <fieldset>
                    <br/>
                    
                    <?php foreach ($testQuestions as $numQuestion => $testQuestion): ?>
                        <br/>
                        <?php echo $testQuestion['questionText'] ?>
                        <div>
                            <?php foreach ($testQuestion['answers'] as $numAnswer => $answer): ?>
                                <?php echo "<input type='radio' name=\"", $numQuestion, "\" id=\"", $numAnswer, "\", value=\"", $numAnswer, "\" />" ?>
                                <?php echo "<label for=\"", $numAnswer, "\">", $answer['text'], "</label>" ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach;?>
                </fieldset>
                <input type="submit" name="submit" value="Submit result"/>
            </form>
        <?php endif; ?>    
        
        <?php
            if (isset($_POST['submit']))
            {
                foreach ($testQuestions as $numQuestion => $testQuestion)
                {
                    if (array_key_exists($numQuestion, $_POST))
                    {
                        $answer = (int) $_POST[$numQuestion];
                    
                        if ($testQuestion['answers'][$answer]['correct'])
                        {
                            ++$score;
                        }
                    }
                }
                
                $params = array();
                $params['score'] = $score;
                $params['testName'] = $testName;
                $params['maxScore'] = count($testQuestions);
                $params['userName'] = $userName;
                                
                redirectWithParams('displayResult', $params);
            }
        ?>
        
    </body>
</html>    
