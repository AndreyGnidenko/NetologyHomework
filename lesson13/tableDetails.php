<?php

    require_once('functions.php');
    require_once('connection.php');
    
    if (!isset($_GET['tableName']) || empty($_GET['tableName']))
    {
        redirect('index');
    }
    
    $tableName = $_GET['tableName'];
    
    $dbConnection = new DatabaseConnection();
    
    $tableSchema = $dbConnection->getTableSchema($tableName);
    
    function getPrettyFieldValue ($fieldKey, $rawValue)
    {
        $prettyValue='';
        switch ($fieldKey)
        {
            case 'Null':
            {
                $prettyValue = ( $rawValue === 'NO' ? 'TRUE' : 'FALSE' );
                break;
            }
            case 'Key':
            {
                $prettyValue = ( $rawValue === 'PRI' ? 'TRUE' : 'FALSE' );
                break;       
            }
            default:
            {
                $prettyValue = $rawValue;
            }
        }
        return $prettyValue;
    }
    
    function showRenameForm ($tblName, $fieldName)
    {
        echo '<form name="rename" method="POST">';
        echo '<input type="text" name="newName" placeholder="Specify new name"/><br/>';
        echo '<input type="submit" name="rename" value="Rename"/>';
        echo '</form>';
    }
    
    function showChangeTypeForm ($tblName, $fieldName)
    {
        $supportedTypes = array('int(11)', 'FLOAT', 'TINYINT', 'TEXT');
        
        echo '<form name="changeType" method="POST">';
        echo '<input type="select" name="newType" placeholder="Specify new type">';
        
        foreach ($supportedTypes as $supportedType)
        {
            echo '<option value="', $supportedType, '"', $supportedType, '</option>';        
        }
        
        echo '</input><br/>';

        echo '<input type="submit" name="changeType" value="Change type"/>';
        echo '</form>';
    }
            
    function displayFieldOperations ($tblName, $fieldName, $fieldType, $isPrimary)
    {
        $operations = array ('Rename'=>'green', 'Change type'=>'blue', 'Delete'=>'red');
        
        $strOperations = '<td>';
        
        if (!$isPrimary)
        {
            foreach ($operations as $operationName=>$color)
            {
                $strOperations.='<a href="?tableName='.$tblName.'&fieldName='.$fieldName.'&fieldType='.$fieldType.'&operation='.$operationName.'" style="color: '.$color.'">'.$operationName.'</a>&ensp;'; 
            }
        }
        $strOperations.='</td>';
        echo $strOperations;
    }
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
        <title>Table schema</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
    
        <?php
        
            if (isset($_GET['operation']) && isset($_GET['fieldName']) && isset($_GET['fieldType']))
            {
                $fieldName = $_GET['fieldName'];
                $fieldType = $_GET['fieldType'];
                $operation = $_GET['operation'];
                
                switch ($operation)
                {
                    case 'Rename':
                    {
                        ShowRenameForm ($tableName, $fieldName);
                        break;
                    }
                    case 'Change type':
                    {
                        ShowChangeTypeForm ($tableName, $fieldName);
                        break;
                    }
                    case 'Delete':
                    {
                        break;
                    }
                }
                
                if (isset($_POST['rename']) && isset($_POST['newName']) && !empty($_POST['newName']))
                {
                    $dbConnection->alterTableField($tableName, $fieldName, $fieldType, $_POST['newName']);            
                }
                else if (isset($_POST['changeType']) && isset($_POST['newType']) && !empty($_POST['newType']))
                {
                    $fieldType = $_POST['newType'];
                    $dbConnection->alterTableField($tableName, $fieldName, $fieldType, $fieldName);            
                }
            }
        ?>
    
        
        <h2>Table <?php echo $tableName ?> schema</h2>
        
        <table name="TableSchema">
            <tr>
                <th>Field</th>
                <th>Type</th>
                <th>Nullable</th>
                <th>Primary Key</th>
                <th>Operations</th>
            </tr>
            
            <?php
            
                $schemaKeys = array ('Field', 'Type', 'Null', 'Key');
            
                foreach ($tableSchema as $tableField)
                {
                    echo '<tr>';
                    foreach($schemaKeys as $schemaKey)
                    {
                        echo '<td>', getPrettyFieldValue($schemaKey, $tableField[$schemaKey]), '</td>';
                    }
                    
                    $isPrimary = ($tableField['Key']==='PRI');
                    
                    displayFieldOperations($tableName, $tableField['Field'], $tableField['Type'], $isPrimary);
                    
                    echo '</tr>';
                }
            ?>
            
        </table>
        
        <br/><br/>
        <a href="index.php">Home</a> 
     </body>
</html>

