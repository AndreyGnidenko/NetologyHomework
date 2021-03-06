<?php
    session_start();
    
    require_once('functions.php');
    require_once('connection.php');
    
    if (!isset($_SESSION['user']))
    {
        redirect('reg');
    }
    
    $userName = $_SESSION['user'];
?>

<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    
    table th {
        background: #eee;
    }
    
    .frm-link {
        display:inline;
        margin:4;
        padding:0;
    }
    
    .btn-link{
          border:none;
          outline:none;
          background:none;
          cursor:pointer;
          color:#EE0000;
          padding:0;
          text-decoration:underline;
          font-family:inherit;
          font-size:inherit;
    }
    
    .btn-link-blue{
          border:none;
          outline:none;
          background:none;
          cursor:pointer;
          color:#0000EE;
          padding:0;
          text-decoration:underline;
          font-family:inherit;
          font-size:inherit;
    }
    
</style>

<html lang="ru">
    <head>
        <title>Tasks for today</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        
        <h2>Hello, <?php echo $userName ?>! Your tasks for today </h2> 
        <div style="float: left; margin-left: 20px;">
            <form method="post">
                <input type="text" name="description" placeholder="Task description" value="" />
                <input type="submit" name="addTask" value="Add" />
            </form>
        </div>
        
        <div style="float: left; margin-left: 20px;">
            <form method="post">
                <label for="sort">Sort by</label>
                <select name="sortBy">
                    <option value="date_added">Date</option>
                    <option value="is_done">Status</option>
                    <option value="description">Description</option>
                </select>
                <input type="submit" name="doSort" value="Sort" />
            </form>
        </div>
        <div style="clear: both"></div>
        
        <?php
        
            function displayTaskCells ($taskRecord)
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
            
            function displayTaskOperations ($taskId)
            {
                $operations = array ('Modify'=>'green');
                
                $strOperations = '<td>';
                
                foreach ($operations as $operationName=>$color)
                {
                    $strOperations.='<a href="?taskId='.$taskId.'&operation='.$operationName.'" style="color: '.$color.'">'.$operationName.'</a>&ensp;'; 
                }
                
                $strOperations.='<form method="post" class="frm-link">';
                $strOperations.='<button type="submit" name="resolve" value="resolve" class="btn-link-blue"> Resolve </button>';
                $strOperations.='<input type="hidden" name="taskId" value="'.$taskId.'"/>';
                $strOperations.='</form>';
                
                $strOperations.='<form method="post" class="frm-link">';
                $strOperations.='<button type="submit" name="delete" value="delete" class="btn-link">Delete</button>';
                $strOperations.='<input type="hidden" name="taskId" value="'.$taskId.'"/>';
                $strOperations.='</form>';
                
                $strOperations.='</td>';
                echo $strOperations;
            }
            
            function displayChangeDescForm ($taskId)
            {
                echo '<form name="rename" method="POST"><fieldset><legend>New description for task</legend>';
                echo '<input type="text" name="newDesc" placeholder="New description"/><br/>';
                echo '<input type="hidden" name="taskId" value="'.$taskId.'"/>';
                echo '<input type="submit" name="rename" value="Change description"/>';
                echo '</fieldset></form>';
            }
            
            
            $columnNames = array ('description' => 'Description', 'date_added'=>'Time', 'is_done'=>'Status', 'creator'=>'Created by', 'assignee'=>'Assigned to');
            
            $dbConnection = new DatabaseConnection;
            
            if (empty($_POST) && isset($_GET['operation']) && isset($_GET['taskId']))
            {
                $operation = $_GET['operation'];
                if ($operation === 'Modify')
                {
                    displayChangeDescForm($_GET['taskId']);
                }
            }
            
            if (isset($_POST['addTask']) && !empty($_POST['description']) ) 
            {
                $dbConnection->addTask($userName, $_POST['description']);
            }
            
            if (isset($_POST['delegate']) )
            {
                $assignedId = $_POST['assigned_id'];
                
                $regexMatches = array();
                
                preg_match('^task([0-9]*)_user([0-9]*)$^', $assignedId, $regexMatches);
                
                $taskId = (int)$regexMatches[1];
                $assignee = (int)$regexMatches[2];
                
                $dbConnection->assignTask($taskId, $assignee);
            }
            
            if (isset($_POST['rename']) && isset($_POST['taskId']) && isset($_POST['newDesc']) )
            {
                $taskId = $_POST['taskId'];
                
                $dbConnection->renameTask($taskId, $_POST['newDesc']);
            } 
            
            if (isset($_POST['resolve']) && isset($_POST['taskId']) )
            {
                $taskId = $_POST['taskId'];
                $dbConnection->resolveTask($taskId);
            }  

            if (isset($_POST['delete']) && isset($_POST['taskId']) )
            {
                $taskId = $_POST['taskId'];
                $dbConnection->deleteTask($taskId);
            }                
            
            $sortField = isset($_POST['doSort']) ? $_POST['sortBy'] : 'date_added';
        
            $tasks = $dbConnection->getUserTasks($userName, $sortField);
            $userRecords = $dbConnection->getUsers();

            {
                echo '<h2>Tasks created by me</h2>';
            
                echo '<table name="MyTasks">';
               
                echo '<tr>';
                foreach($columnNames as $columnName)
                {
                    echo '<th>', $columnName, '</th>';
                }
                echo '<th>Actions</th>';
                echo '<th>Delegation</th>';
                echo '</tr>';
                
                foreach ($tasks as $taskRecord)
                {
                    echo '<tr>';
                    
                    $taskId = $taskRecord['id'];
                    
                    displayTaskCells($taskRecord);

                    displayTaskOperations($taskId);

                    echo '<td> <form method=\'POST\'> <select name=\'assigned_id\'>';
                    
                    foreach($userRecords as $userRecord)
                    {
                        echo '<option value=task', $taskId, '_user', $userRecord['id'], '>', $userRecord['userName'], '</option>';
                    }
                    
                    echo '</select> <input type=\'submit\' name=\'delegate\' value=\'Delegate\'> </form> </td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            }
            
            {
                echo '<h2>Tasks assigned to me by others</h2>';
                
                echo '<table name="AssignedTasks">';
                
                echo '<tr>';
                foreach($columnNames as $columnName)
                {
                    echo '<th>', $columnName, '</th>';
                }
                echo '</tr>';
                
                $assignedTasks = $dbConnection->getAssignedTasks($userName, $sortField);
                
                foreach ($assignedTasks as $assignedTaskRecord)
                {
                    echo '<tr>';
                    
                    displayTaskCells($assignedTaskRecord);
                    
                    echo '</tr>';
                }
                echo '</table>';
            }

        ?>
        
        <br/><br/>
        
        <a href="logout.php">Logout</a>
        
     </body>
</html>