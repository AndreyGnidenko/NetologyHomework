<?php

    require_once('functions.php');
    require_once('connection.php');
    
    if (!isset($_GET['tableName']) || empty($_GET['tableName']))
    {
        redirect('index');
    }
    
    $tableName = $_GET['tableName'];
    
    $dbConnection = new DatabaseConnection();
    
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
        echo '<form name="rename" method="POST"><fieldset><legend>New name for field ', $fieldName, '</legend>';
        echo '<input type="text" name="newName" placeholder="Specify new name"/><br/>';
        echo '<input type="submit" name="rename" value="Rename"/>';
        echo '</fieldset></form>';
    }
    
    function showChangeTypeForm ($tblName, $fieldName)
    {
        $supportedTypes = array('int(11)', 'FLOAT', 'TINYINT', 'TEXT', 'datetime');
        
        echo '<form name="changeType" method="POST"><fieldset><legend>New type for field ', $fieldName, '</legend>';
        echo 'Specify new type <select name="newType">';
        
        foreach ($supportedTypes as $supportedType)
        {
            echo '<option value="', $supportedType, '">', $supportedType, '</option>';        
        }
        
        echo '</select><br/>';

        echo '<input type="submit" name="changeType" value="Change type"/>';
        echo '</fieldset></form>';
    }
    
    function showNewColumnForm ($tblName)
    {
        $supportedTypes = array('int(11)', 'FLOAT', 'TINYINT', 'TEXT', 'datetime');
        
        echo '<form name="newColumn" method="POST"><fieldset><legend>New column</legend>';
        
        echo '<input type="text" name="newName" placeholder="New column name"/><br/>';
        
        echo 'New column type <select name="newType">';
                
        foreach ($supportedTypes as $supportedType)
        {
            echo '<option value="', $supportedType, '">', $supportedType, '</option>';        
        }
        
        echo '</select><br/><br/>';

        echo '<input type="submit" name="add" value="Add"/>';
        echo '</fieldset></form>';
    }
            
    function displayFieldOperations ($tblName, $fieldName, $fieldType, $isPrimary)
    {
        $operations = array ('Rename'=>'green', 'Change type'=>'blue');
        
        $strOperations = '<td>';
        
        if (!$isPrimary)
        {
            foreach ($operations as $operationName=>$color)
            {
                $strOperations.='<a href="?tableName='.$tblName.'&fieldName='.$fieldName.'&fieldType='.$fieldType.'&operation='.$operationName.'" style="color: '.$color.'">'.$operationName.'</a>&ensp;'; 
            }
            $strOperations.='<form method="post" class="frm-link">';
            $strOperations.='<button type="submit" name="delete" value="delete" class="btn-link">Delete</button>';
            $strOperations.='<input type="hidden" name="fieldName" value="'.$fieldName.'"/>';
            $strOperations.='</form>';
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
    
    .frm-link {
        display:inline;
        margin:0;
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
    
</style>

<html lang="ru">
    <head>
        <title>Table schema</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
    
        <?php
        
            if (isset($_GET['fieldName']) && isset($_GET['fieldType']))
            {
                $fieldName = $_GET['fieldName'];
                $fieldType = $_GET['fieldType'];
            }    
            
            if (empty($_POST) && isset($_GET['operation']))
            {
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
                    case 'Add':
                    {
                        ShowNewColumnForm($tableName);
                    }
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
            else if (isset($_POST['delete']) && isset($_POST['fieldName']))
            {
                $fieldName = $_POST['fieldName'];
                $dbConnection->removeTableField($tableName, $fieldName);
            }
            else if (isset($_POST['add']) && isset($_POST['newName']) && isset($_POST['newType']))
            {
                $fieldName = $_POST['newName'];
                $fieldType = $_POST['newType'];
                $dbConnection->addTableField($tableName, $fieldName, $fieldType);
            }
            
            $tableSchema = $dbConnection->getTableSchema($tableName);
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
        
        <?php echo '<a href="?tableName='.$tableName.'&operation=Add">New column</a>'; ?>
        
        <br/><br/>
        <a href="index.php">Home</a> 
     </body>
</html>

