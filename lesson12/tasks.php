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
        
            $columnNames = array ('description' => 'Description', 'date_added'=>'Time', 'is_done'=>'Status', 'creator'=>'Created by', 'assignee'=>'Assigned to');
            
            $dbConnection = new DatabaseConnection;
            
            if (isset($_POST['addTask']) && !empty($_POST['description']) ) 
            {
                $dbConnection->addTask($userName, $_POST['description']);
            }
            
            $sortField = isset($_POST['doSort']) ? $_POST['sortBy'] : 'date_added';
        
            $tasks = $dbConnection->getUserTasks($userName, $sortField);
            $userRecords = $dbConnection->getUsers();

            echo '<table name="Tasks">';
           
            echo '<tr>';
            foreach($columnNames as $columnName)
            {
                echo '<th>', $columnName, '</th>';
            }
            echo '<th>Delegation<th/>';
            echo '</tr>';
            
            foreach ($tasks as $taskRecord)
            {
                echo '<tr>';
                foreach ($taskRecord as $internalColumnName=>$taskRecordColumn)
                {
                    $columnVal = $taskRecordColumn;
                    if ($internalColumnName === 'is_done')
                    {
                        $columnVal = $taskRecordColumn ? 'Complete' : 'In progress';
                    }
                    echo '<td>', $columnVal, '</td>';
                }
                echo '<td> <form method=\'POST\'> <select name=\'assigned_id\'>';
                
                foreach($userRecords as $userRecord)
                {
                    echo '<option value=', $userRecord['id'], '>', $userRecord['userName'], '</option>';
                }
                
                echo '</select> <input type=\'submit\' name=\'delegate\' value=\'Delegate\'> </form> </td>';
                echo '</tr>';
            }
            
            echo '</table>';
        ?>
        
        <br/><br/>
        
        <a href="logout.php">Logout</a>
        
     </body>
</html>