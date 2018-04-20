<?php
    
    function getTaskValues ($taskRecord)
    {
        foreach ($taskRecord as $internalColumnName=>$taskRecordColumn)
        {
            if ($internalColumnName !== 'id')
            {
                $columnVal = $taskRecordColumn;
                if ($internalColumnName === 'is_done')
                {
                    $columnVal = $taskRecordColumn ? 'Complete' : 'In progress';
                }
                echo '<td>', $columnVal, '</td>';
            }
        }
    }
    
    function getColumnValues ($taskRecord)
    {
        $columnVals = array();
        foreach ($taskRecord as $internalColumnName=>$taskRecordColumn)
        {
            if ($internalColumnName !== 'id')
            {
                $columnVal = $taskRecordColumn;
                if ($internalColumnName === 'is_done')
                {
                    $columnVal = $taskRecordColumn ? 'Complete' : 'In progress';
                }
                echo '<td>', $columnVal, '</td>';
                
                $columnVals[] = $columnVal;
            }
        }
    }
    
    function performOperation (Model $model, $userName, $operation)
    {
        switch ($operation)
        {
            case 'addTask':
            {
                $taskDescription = $_POST['description'];
                
                if (!empty($taskDescription))
                {
                    $model->addTask($userName, $taskDescription);
                }
                break;
            }
            case 'delegate':
            {
                $assignedId = $_POST['assigned_id'];
                
                $regexMatches = array();
                
                preg_match('^task([0-9]*)_user([0-9]*)$^', $assignedId, $regexMatches);
                
                $taskId = (int)$regexMatches[1];
                $assignee = (int)$regexMatches[2];
                
                $model->assignTask($taskId, $assignee);
                
                break;
            }                
            case 'rename':
            {
                $taskId = $_POST['taskId'];
                $newDesc = $_POST['newDesc'];
                
                $model->renameTask($taskId, $newDesc);
                break;
            }
            case 'resolve':
            {
                $taskId = $_POST['taskId'];
                $model->resolveTask($taskId);
                
                break;
            }
            case 'delete':
            {
                $taskId = $_POST['taskId'];
                $model->deleteTask($taskId);
                break;
            }
        }
    }

    session_start();
    
    require_once('functions.php');
    require_once('model.php');
    
    if (!isset($_SESSION['user']))
    {
        redirect('reg');
    }
    
    $userName = $_SESSION['user'];
    $model = new Model;
    
    $sortColumn = 'date_added';
    $taskIdToRename = null;
    
    if (array_key_exists('operation', $_POST))
    {
        $operation = $_POST['operation'];
        performOperation ($model, $userName, $operation);
    }
    else if (array_key_exists('operation', $_GET))
    {
        $operation = $_GET['operation'];
        
        switch ($operation)
        {
            case 'sort':
            {
                $sortColumn = $_GET['sortBy'];
                break;
            }
            case 'modify':
            {
                $taskIdToRename = $_GET['taskId'];
                break;
            }
        }
    }
    
    require_once('vendor/autoload.php');
    $loader = new Twig_Loader_Filesystem(__DIR__);
    $twig = new Twig_Environment($loader);
    
    $columnNames = array ('description' => 'Description', 'date_added'=>'Time', 'is_done'=>'Status', 'creator'=>'Created by', 'assignee'=>'Assigned to');
            
    $userNames = $model->getUserNames();
    $tasks = $model->getUserTasks($userName, $sortColumn);
    $assignedTasks = $model->getAssignedTasks($userName, $sortColumn);
    
    $twigParams = array('userName' => $userName, 'columnNames'=> $columnNames, 'users'=>$userNames, 
    'sortColumn' => $sortColumn, 'tasks' =>$tasks, 'assignedTasks'=>$assignedTasks);
        
    if ($taskIdToRename!= null)
    {
        $twigParams['taskIdToRename'] = $taskIdToRename;
    }
        
    echo $twig->render('taskList.twig', $twigParams);
?>