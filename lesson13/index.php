<?php

    require_once('functions.php');
    require_once('connection.php');
    
    $dbConnection = new DatabaseConnection();
    $tableNames = $dbConnection->getTableNames();
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
        <title>Database schema</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        
        <h2>Database schema</h2>
        
        <table name="DatabaseSchema">
            <tr>
                <th>Table name</th>
                <th>Details</th>
            </tr>
            
            <?php foreach ($tableNames as $tableName): ?>
                <tr>
                    <td><?php echo $tableName ?></td>
                    <td><?php echo '<a href="tableDetails.php?tableName=',$tableName,'">View details</a>'?></td>                    
                </tr>
            <?php endforeach; ?>
            
        </table>
     </body>
</html>

