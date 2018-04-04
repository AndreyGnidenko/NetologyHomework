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
        
        <h2>Tasks for today</h2> 
        
        <form method="post">
            <input type="text" name="description" placeholder="Task description" value="" />
            <input type="submit" name="addTask" value="Add" />
        </form>
        
        <form method="post">
            <label for="sort">Sort by</label>
            <select name="sortBy">
                <option value="date_added">Date</option>
                <option value="is_done">Status</option>
                <option value="description">Description</option>
            </select>
            <input type="submit" name="doSort" value="Sort" />
        </form>
        
     </body>
</html>
     
<?php

    $dsn = 'mysql:dbname=tasks;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    
    try
    {
        $columnNames = array ('description' => 'Description', 'date_added'=>'Time', 'is_done'=>'Status');
        $internalColumnNames = array_keys($columnNames);
        
        $dbh = new PDO($dsn, $user, $password);
        
        if (isset($_POST['addTask']) && !empty($_POST['description']) ) 
        {
            $insertValues = array();
            $insertValues['description'] = $_POST['description'];
            $insertValues['date_added'] = date('Y-m-d H:i:s');
            $insertValues['is_done'] = 0;
            
            $insertQuery = 'INSERT INTO `tasks` ('.implode(', ', $internalColumnNames).') VALUES (:'.implode(', :', $internalColumnNames).')';
            $insertStatement = $dbh->prepare($insertQuery);
            
            $insertStatement->execute($insertValues);
        }
        
        $sortField = isset($_POST['doSort']) ? $_POST['sortBy'] : 'date_added';
        
        $sqlQuery = 'SELECT '.implode(', ', $internalColumnNames).' FROM tasks ORDER BY '.$sortField;
        $sqlStatement = $dbh->prepare($sqlQuery);
        
        $sqlStatement->execute();
        
        $tasks = $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<table name="Tasks">';

        echo '<tr>';
        foreach($columnNames as $columnName)
        {
            echo '<th>', $columnName, '</th>';
        }
        echo '</tr>';
        
        foreach ($tasks as $taskRecord)
        {
            echo '<tr>';
            foreach ($taskRecord as $taskRecordColumn)
            {
                echo '<td>', $taskRecordColumn, '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
    }
    catch (PDOException $e) 
    {
        echo '<p style="color:red">Database communication failed due to ', $e->getMessage(),'</p>';
    }
?>