<?php

session_start();
require_once('functions.php');
require_once('model.php');

if (!empty($_SESSION['user']))
{
    redirect('taskList');
}

$errors = array();
$model = new Model;

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

            if ($model->validateUser($login, $password))
            {
                $_SESSION['user'] = $login;
                redirect ('taskList');
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
            
            if ($model->isExistingUser($login))
            {
                $errors[] = 'The specified user already exists';
            }
            else
            {
                $model->addUser($login, $password);
                
                $_SESSION['user'] = $login;
                redirect ('taskList');
            }
        }
    }
    else
    {
        $errors[] = 'Incorrect login or password';
    }
}    
    
require_once('vendor/autoload.php');
$loader = new Twig_Loader_Filesystem(__DIR__);
$twig = new Twig_Environment($loader);

echo $twig->render('reg.twig', array('errors' => $errors));
    
?>



