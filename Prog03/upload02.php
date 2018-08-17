<?php

// see HTML form (upload02.html) for overview of this program

echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
echo "<br><br>";


// include code for database access
require 'database.php';

// set PHP variables from data in HTML form 
$fileName       = $_FILES['Filename']['name'];
$tempFileName   = $_FILES['Filename']['tmp_name'];
$fileSize       = $_FILES['Filename']['size'];
$fileType       = $_FILES['Filename']['type'];
$fileDescription = $_POST['Description']; 

// set server location (subdirectory) to store uploaded files
//$fileLocation = "/cis355/Prog03/uploads/";
$fileLocation ="uploads/";
$fileFullPath = $fileLocation . $fileName; 
if (!file_exists($fileLocation))
    mkdir ($fileLocation); // create subdirectory, if necessary

// execute debugging code...
// echo phpinfo(); exit(); // to see location of php.ini
// note: can't set php.ini:file_uploads on the fly
// echo ini_set('file_uploads', '1'); // "set" does not work
// echo ini_get('file_uploads'); // "get" does work
// echo "<pre>"; print_r(ini_get_all()); echo "</pre>"; exit();
// echo "<pre>"; print_r($_FILES); echo "</pre>"; exit(); 

//Button to return to customers table
echo "<a class='btn btn-secondary' href='customers.php'>Back</a>";

// connect to database
$pdo = Database::connect();

// exit, if requested file already exists -- in the database table 
$fileExists = false;
$sql = "SELECT filename FROM upload02 WHERE filename='$fileName'";
foreach ($pdo->query($sql) as $row) {
    if ($row['filename'] == $fileName) {
        $fileExists = true;
    }
}
if ($fileExists) {
    echo "File <html><b><i>" . $fileName 
        . "</i></b></html> already exists in DB. Please rename file.";
    exit(); 
}

// exit, if requested file already exists -- in the subdirectory 
if(file_exists($fileFullPath)) {
    echo "File <html><b><i>" . $fileName 
        . "</i></b></html> already exists in file system, "
        . "but not in database table. Cannot upload.";
    exit(); 
}

// if all of above is okay, then upload the file
$result = move_uploaded_file($tempFileName, $fileFullPath);

// if upload was successful, then add a record to the SQL database
if ($result) {
    echo "Your file <html><b><i>" . $fileName 
        . "</i></b></html> has been successfully uploaded";
    $sql = "INSERT INTO upload02(filename,filetype,filesize,description,path)"
        . " VALUES ('$fileName','$fileType',$fileSize,"
        . "'$fileDescription', '$fileFullPath')";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $q = $pdo->prepare($sql);
    $q->execute(array());
// otherwise, report error
} else {
    echo "Upload denied for this file. Verify file size < 2MB. ";
}


/*This code is no longer needed but I am leaving it in for my future reference
 * 
 */
// list all files in database 
// ORDER BY BINARY filename ASC (sorts case-sensitive, like Linux)
//echo '<br><br>All files in database...<br><br>';
//$sql = 'SELECT * FROM upload02 ' 
//    . 'ORDER BY BINARY filename ASC;';
//$i = 0; 
//foreach ($pdo->query($sql) as $row) {
//    echo ' ... [' . $i++ . '] --- ' . $row['filename'] . '<br>';
//    
//}
//echo '<br><br>';


/* This is the newly added code for generating a list of all files in the 
 * database table, displaying user-entered descriptions, the images themselves,
 * the full path of each image, and the filenmae of each image file on the
 * server.
 */

// list all uploads in database 
// ORDER BY BINARY filename ASC (sorts case-sensitive, like Linux)
echo '<br><br>All files in database...<br><br>';
$sql = 'SELECT * FROM upload02 ' 
    . 'ORDER BY BINARY filename ASC;';

foreach ($pdo->query($sql) as $row) {
    $id = $row['id'];
    $sql = "SELECT * FROM upload02 where id=$id"; 
    echo $row['id'] . ' - ' . $row['filename']
        . '<br>' . 'Description: ' . $row['description'] . '<br>'
        . '<img width=100 src="' . $row['path'] . '"/>'
        . '<br>' . 'Full Path: ' . realpath($row['path']) . '<br>'
        . 'File Name: ' . $row['filename'] . '<br><br>';
}
echo '<br><br>';


/* This code is no longer necessary but I want to leave it in for my future
 * reference.
 */

// list all files in subdirectory
//echo 'All files in subdirectory...<br>';
//echo '<pre>';
//$arr = array_slice(scandir("$fileLocation"), 2);
//asort($arr);
//print_r($arr);
//echo '<pre>';
//echo '<br><br>';


// disconnect
Database::disconnect(); 