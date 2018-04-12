<?php

class DatabaseConnection
{
    private $dbName = 'tasks';
    private $user = 'root';
    private $password = '';
    
    private $dbh;
    
    public function __construct ()
    {
        $dsn = 'mysql:dbname='.$this->dbName.';host=localhost;charset=utf8';
        $this->dbh = new PDO($dsn, $this->user, $this->password);
    }
    
    public function getTableNames ()
    {
        $sqlQuery = 'SHOW tables';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute();
        $fetchResult = $sqlStatement->fetchAll(PDO::FETCH_ASSOC);

        $tableNames = array();
        
        foreach ($fetchResult as $fetchRecord)
        {
            $fetchIdx = 'Tables_in_'.$this->dbName;
            $tableNames[] = $fetchRecord[$fetchIdx]; 
        }
        
        return $tableNames;
    }
    
    public function getTableSchema ($tableName)
    {
        $sqlQuery = 'DESCRIBE '.$tableName;
        $sqlStatement = $this->dbh->prepare($sqlQuery);

        $sqlStatement->execute();
        return $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function alterTableField ($tableName, $fieldName, $newType, $newName)
    {
        $sqlQuery = 'ALTER TABLE '.$tableName. ' CHANGE COLUMN `:fieldName` `:newName` :newType';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        
        $insertValues = array();
        $insertValues['fieldName'] = $fieldName;
        $insertValues['newName'] = $newName;
        $insertValues['newType'] = $newType;
        
        $sqlStatement->execute($insertValues);
    }
}

?>