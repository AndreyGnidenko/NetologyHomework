<?php

session_start();
require_once('functions.php');
require_once('connection.php');

if (!empty($_SESSION['user']))
{
    redirect('tasks');
}

$errors = array();

if (isset($_POST['sign_in']))
{
    if (isset($_POST['login']) && isset($_POST['password']))
    {
        if (empty($_POST['login']) || empty($_POST['password']))
        {
            $errors[] = 'Empty login or password';
        }
        else
        {
            $login = prepareInput($_POST['login']);
            $password = md5(prepareInput($_POST['password']));
            
            $dbConnection = new DatabaseConnection;
            
            if ($dbConnection->validateUser($login, $password))
            {
                $_SESSION['user'] = $login;
                redirect ('tasks');
                die;
            }
            else
            {
                $errors[] = 'Incorrect login or password';
            }
        }
    }
    else
    {
        $errors[] = 'Incorrect login or password';
    }        
}

if (isset($_POST['sign_up']))
{
    if (isset($_POST['login']) && isset($_POST['password']))
    {
        if (empty($_POST['login']) || empty($_POST['password']))
        {
            $errors[] = 'Empty login or password';
        }
        else
        {
            $login = prepareInput($_POST['login']);
            $password = md5(prepareInput($_POST['password']));
            
            $dbConnection = new DatabaseConnection;
            
            if ($dbConnection->isExistingUser($login))
            {
                $errors[] = 'The specified user already exists';
            }
            else
            {
                $dbConnection->addUser($login, $password);
                
                $_SESSION['user'] = $login;
                redirect ('tasks');
                die;
            }
        }
    }
    else
    {
        $errors[] = 'Incorrect login or password';
    }        
}

?>

<html lang="ru">
    <head>
        <title>TODO manager</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <h2>Please login</h2>
        
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach; ?>
        </ul>
        
        <form method="POST">
            <input type="text" name="login" placeholder="Login" /><br/>
            <input type="password" name="password" placeholder="Password" /><br/>
            <input type="submit" name="sign_in" value="Sign in" />
            <input type="submit" name="sign_up" value="Sign up" />
        </form>
        
    </body>
</html> 

