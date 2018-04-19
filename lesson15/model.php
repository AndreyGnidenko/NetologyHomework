<?php

class Model
{
    private $dsn = 'mysql:dbname=tasks;host=localhost;charset=utf8';
    private $user = 'root';
    private $password = '';
    
    //private $dsn = 'mysql:dbname=gnidenko;host=localhost;charset=utf8';
    //private $user = 'gnidenko';
    //private $password = 'neto1689';
    
    private $dbh;
    
    public function __construct ()
    {
        $this->dbh = new PDO($this->dsn, $this->user, $this->password);
    }
    
    public function isExistingUser ($userName)
    {
        $sqlQuery = 'SELECT login FROM user WHERE login=:login';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        
        $insertValues = array();
        $insertValues['login'] = $userName;
        $sqlStatement->execute($insertValues);
        
        $fetchResult = $sqlStatement->fetch(PDO::FETCH_ASSOC);
        
        return !empty($fetchResult);
    }
    
    public function validateUser ($userName, $passMd5)
    {
        $sqlQuery = 'SELECT login FROM user WHERE login=:login AND password=:password';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        
        $insertValues = array();
        $insertValues['login'] = $userName;
        $insertValues['password'] = $passMd5;
        $sqlStatement->execute($insertValues);
        
        $fetchResult = $sqlStatement->fetch(PDO::FETCH_ASSOC);
        
        return !empty($fetchResult);
    }
    
    public function addUser ($userName, $passMd5)
    {
        $sqlQuery = 'INSERT into user (`login`, `password`) VALUES (:login, :password)';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        
        $insertValues = array();
        $insertValues['login'] = $userName;
        $insertValues['password'] = $passMd5;
        
        $sqlStatement->execute($insertValues);
    }
    
    public function addTask ($userName, $taskDescription)
    {
        $insertValues['description'] = $_POST['description'];
        $insertValues['date_added'] = date('Y-m-d H:i:s');
        $insertValues['is_done'] = 0;
        $insertValues['login'] = $userName;
        
        $sqlQuery = 'INSERT INTO task (`description`, `date_added`, `is_done`, `user_id`, `assigned_user_id`) VALUES (:description, :date_added, :is_done, (SELECT id FROM user WHERE login=:login), (SELECT id FROM user WHERE login=:login))';
        
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute($insertValues);
    }
    
    public function getUserTasks ($userName, $sortField)
    {
        $columnNames = array ('id', 'description', 'date_added', 'is_done');
        
        $sqlQuery = 'SELECT t.'.implode(', t.', $columnNames). ', u.login creator, u2.login assignee FROM task t INNER JOIN user u ON t.user_id = u.id INNER JOIN user u2 ON t.assigned_user_id = u2.id WHERE u.login=:userName ORDER BY '.$sortField;
        $sqlStatement = $this->dbh->prepare($sqlQuery);
    
        $insertValues = array();
        $insertValues['userName'] = $userName;
            
        $sqlStatement->execute($insertValues);
        return $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAssignedTasks ($userName, $sortField)
    {
        $columnNames = array ('description', 'date_added', 'is_done');
        
        $sqlQuery = 'SELECT t.'.implode(', t.', $columnNames). ', u.login creator, u2.login assignee FROM task t INNER JOIN user u ON t.user_id = u.id INNER JOIN user u2 ON t.assigned_user_id = u2.id WHERE u2.login=:userName AND t.user_id!=t.assigned_user_id ORDER BY :sortField';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
    
        $insertValues = array();
        $insertValues['userName'] = $userName;
        $insertValues['sortField'] = $sortField;
    
        $sqlStatement->execute($insertValues);
        return $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getUsers ()
    {
        $sqlQuery = 'SELECT id, login as userName FROM user';
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute();
        return $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function assignTask ($taskId, $assignee)
    {
        $sqlQuery = 'UPDATE task SET assigned_user_id=:assignee WHERE id=:taskId';
        
        $insertValues = array();
        $insertValues['taskId'] = $taskId;
        $insertValues['assignee'] = $assignee;
        
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute($insertValues);
    }
    
    public function renameTask($taskId, $newDescription)
    {
        $sqlQuery = 'UPDATE task SET description=:desc WHERE id=:taskId';
        
        $insertValues = array();
        $insertValues['taskId'] = $taskId;
        $insertValues['desc'] = $newDescription;
        
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute($insertValues);
    }
    
    public function resolveTask ($taskId)
    {
        $sqlQuery = 'UPDATE task SET is_done=1 WHERE id=:taskId';
        
        $insertValues = array();
        $insertValues['taskId'] = $taskId;
        
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute($insertValues);
    }
    
    public function deleteTask ($taskId)
    {
        $sqlQuery = 'DELETE FROM task WHERE id=:taskId';
        
        $insertValues = array();
        $insertValues['taskId'] = $taskId;
         
        $sqlStatement = $this->dbh->prepare($sqlQuery);
        $sqlStatement->execute($insertValues);
    }
}

?>