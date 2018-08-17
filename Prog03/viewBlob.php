<?php

require 'database.php';

echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  

//Button to return to customers table
echo "<a class='btn btn-secondary' href='customers.php'>Back</a>";

// connect to database
$pdo = Database::connect();

// list all uploads in database 
// ORDER BY BINARY filename ASC (sorts case-sensitive, like Linux)
echo '<br><br>All files in database...<br><br>';
$sql = 'SELECT * FROM upload03 ' 
    . 'ORDER BY BINARY filename ASC;';

foreach ($pdo->query($sql) as $row) {
    $id = $row['id'];
    $sql = "SELECT * FROM upload03 where id=$id"; 
    echo $row['id'] . ' - ' . $row['filename']
        . '<br>' . 'Description: ' . $row['description'] . '<br>'
        . '<img width=100 src="data:image/jpeg;base64,'
        . base64_encode( $row['content'] ).'"/>'
        . '<br><br>';
}
echo '<br><br>';

// disconnect
Database::disconnect(); 
