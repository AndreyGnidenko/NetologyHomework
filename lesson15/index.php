<?php

    require_once('functions.php');

    session_start();
    
    if (empty($_SESSION['user']))
    {
        redirect('reg');
    }
    else
    {
        redirect('tasks');
    }
    
 ?>