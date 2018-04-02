<html lang="ru">
    <head>
        <title>Books</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        
        <h2>Books</h2> 
        
        <form name="bookSearchForm" method="GET">
            <fieldset>
                <legend>Specify search criteria</legend>
                ISBN: <input type="text" name="isbn"/> <br/> <br/>
                Book name: <input type="text" name="name"/> <br/> <br/>
                Author: <input type="text" name="author"/> <br/> <br/> <br/>
                <input type="submit" name="search" value="Search"/> <br/>
            </fieldset>
        </form>
     </body>
</html>
     
<?php

    if (isset($_GET['search'])) 
    {
        $dsn = 'mysql:dbname=global;host=localhost;charset=utf8';
        $user = 'gnidenko';
        $password = 'neto1689';
        
        try
        {
            $dbh = new PDO($dsn, $user, $password);
            
            $sqlQuery = 'SELECT * FROM books';
            
            if (!empty($_GET['isbn']) || !empty($_GET['name']) || !empty($_GET['author']))
            {
                $sqlQuery.=' WHERE ';
            }
            
            $searchKeys = array ('isbn', 'name', 'author');
            $searchArray = array();
            
            $firstCondition = true;
            foreach ($searchKeys as $searchKey)
            {
                if (array_key_exists($searchKey, $_GET) && !empty($_GET[$searchKey]))
                {
                    $searchArray[$searchKey] = $_GET[$searchKey];
                    
                    if (!$firstCondition)
                    {
                        $sqlQuery.=' AND ';
                    }
                    $sqlQuery.='books.'.$searchKey.' LIKE :'.$searchKey;
                    $firstCondition = false;
                }
            }
            
            $sqlStatement = $dbh->prepare($sqlQuery);
            
            foreach ($searchArray as $searchKey => $searchValue)
            {
                $sqlStatement->bindValue(':'.$searchKey, '%'.$searchValue.'%', PDO::PARAM_STR);
            }
 
            $sqlStatement->execute();
            
            $books = $sqlStatement->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($books))
            {
                echo '<p>No books matching specified criteria found</p>';
            }
            else
            {
                echo '<table name="Books" border="1">';

                echo '<tr>';
                foreach($books[0] as $columnName => $value)
                {
                    echo '<th>', $columnName, '</th>';
                }
                echo '</tr>';
                
                foreach ($books as $bookRecord)
                {
                    echo '<tr>';
                    foreach ($bookRecord as $bookRecordColumn)
                    {
                        echo '<td>', $bookRecordColumn, '</td>';
                    }
                    echo '</tr>';
                }
                
                echo '</table>';
            }
        }
        catch (PDOException $e) 
        {
            echo '<p style="color:red">Book search failed due to ', $e->getMessage(),'</p>';
        }
    }
?>