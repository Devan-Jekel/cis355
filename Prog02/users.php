<?php 
    session_start();
    if(!$_SESSION) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<p>Link to Prog02 GitHub code: <a href='https://github.com/Devan-Jekel/cis355/tree/master/Prog02'>https://github.com/Devan-Jekel/cis355/tree/master/Prog02</a></p>
<a href='logout.php' class="btn btn-danger"> Log Out </a>
    <div class="container">
    		<div class="row">
    			<h3>Users Table</h3>
    		</div>
			<div class="row">
				<p>
					<a href="customers.php" class="btn btn-success">Customers Table</a>
                    <a href="events.php" class="btn btn-danger">Events Table</a>
				</p>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Name</th>
		                  <th>Email Address</th>
		                  <th>Mobile Number</th>
                          <th>Password</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   require 'database.php';
					   $pdo = Database::connect();
					   $sql = 'SELECT * FROM users ORDER BY id DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['name'] . '</td>';
							   	echo '<td>'. $row['email'] . '</td>';
							   	echo '<td>'. $row['mobile'] . '</td>';
                                echo '<td>'. $row['password'] . '</td>';
                                echo '</tr>';
					   }
					   Database::disconnect();
					  ?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>