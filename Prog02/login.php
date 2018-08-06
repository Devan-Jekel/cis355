<?php
session_start();
require "database.php";
if($_GET) $errorMessage = $_GET['errorMessage'];
else $errorMessage = '';
if($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = MD5($password);
    
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo $username . ' ' . $password; exit();
    
    $sql = "SELECT * FROM users WHERE email = '$username' AND password = '$password' LIMIT 1";
    $q = $pdo->prepare($sql);
    //$q->execute(array($username, $passwordhash));
    $q->execute(array());
    $data = $q->fetch(PDO::FETCH_ASSOC);
    //print_r ($data); exit();
    
    if($data) {
        $_SESSION["username"] = $username;
        header("Location: customers.php");
    }
    else {
        header("Location: login.php?errorMessage='Invalid Login Credentials!'");
        exit();
    }
}
// else just show empty login form. 
 
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<div class="container">
    
    <div class="span10 offset1">
    	<div class="row">
		    <h1>Log In</h1>
		</div>
		    		
        <form class ="form-horizontal" action="login.php" method="post">
            <input name="username" type="text" placeholder="me@email.com" required>
            <br><br>
            <input name="password" type="password" required>
            <br><br>
            <button type="submit" class="btn btn-success">Sign in</button>
            <a href="create_user.php" class="btn btn-success"> Create New Account</a>
            <br><br>
            <a href='logout.php'> Log out </a>
            <p style='color: red;'><?php echo $errorMessage; ?></p>
    <!--JOIN BUTTON NEEDED-->
        </form>
	</div>
				
</div> <!-- /container -->
