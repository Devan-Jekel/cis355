<?php

/* ---------------------------------------------------------------------------
 * filename    : customers.class.php
 * author      : Devan Jekel, ddjekel@svsu.edu
 * description : This program details the Customer class used in a CRUD
 *               application made to satisfy the requirements of Prog01
 *               (table: customers)
 * ---------------------------------------------------------------------------
 */
class Customers {
  public $id;
  public $name;
  public $email;
  public $mobile;
  
  // Errors, Titles, and Table Names are attributes we don't want outside users
  // to be able to access directly, thus these attributes are private.
  private $noerrors = true;
  private $nameError = null;
  private $emailError = null;
  private $mobileError = null;
  private $title = "Customer";
  private $tableName = "customers";
  
  // A function to list the customers table contents
  // This represents the customers table that should be displayed 
  function list_records() {
        echo "<!DOCTYPE html>
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
            <p>Link to Prog01 GitHub code: <a href='https://github.com/Devan-Jekel/cis355/tree/master/Prog01'>https://github.com/Devan-Jekel/cis355/tree/master/Prog01</a></p>
                <div class='container'>
                    <p class='row'>
                        <h3>$this->title" . "s" . "</h3>
                    </p>
                    <p>
                        <a href='$this->tableName.php?fun=1' class='btn btn-success'>Create</a>
                        <a href='events.php' class='btn btn-info'>Events Table</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM $this->tableName ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["name"] . "</td>";
            echo "<td>". $row["email"] . "</td>";
            echo "<td>". $row["mobile"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn btn-info' href='$this->tableName.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-warning' href='$this->tableName.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='$this->tableName.php?fun=4&id=".$row["id"]."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        Database::disconnect();        
        echo "
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
        </html>
                    ";  
    } // end function list_records()
  
  // A function to allow for the displaying of controls. 
  // It would be tedious to not compartmentalize this down into a function.
  private function control_group ($label, $labelError, $val, $modifier="") {
        echo "<div class='control-group";
        echo !empty($labelError) ? ' alert alert-danger ' : '';
        echo "'>";
        echo "<label class='control-label'>$label</label>";
        echo "<div class='controls'>";
        echo "<input "
            . "name='$label' "
            . "type='text' "
            . "$modifier "
            . "placeholder='$label' "
            . "value='";
        echo !empty($val) ? $val : '';
        echo "'>";
        if (!empty($labelError)) {
            echo "<span class='help-inline'>";
            echo "&nbsp;&nbsp;" . $labelError;
            echo "</span>";
        }
        echo "</div>";
        echo "</div>";
    } // end function control_group()
  
  // A function for generating the top portions of html
  private function generate_html_top ($fun, $id=null) {
      switch($fun) {
          case 1: // create records
              $funWord = "Create"; $funNext = 11;
              break;
          case 2: // read records
              $funWord = "Read"; $funNext = 0;
              break;
          case 3: // update records
              $funWord = "Update"; $funNext = "33&id=" . $id;
              break;
          case 4: // delete records
              $funWord = "Delete"; $funNext = "44&id=" . $id;
              break;
          case 0: // list records
          default:
              break;
      }
      echo "<!DOCTYPE html>
      <html>
          <head>
               <title>$funWord a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
                echo "
            <body>
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>$funWord a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='$this->tableName.php?fun=$funNext' method='post'>                        
                    ";
  } // end function generate_html_top()
  
  // A function for creating the bottom portions of html
  // It controls the CRUD buttons used by each of the CRUD functionalities
  private function generate_html_bottom ($fun) {
        switch ($fun) {
            case 1: // create records
                $funButton = "<button type='submit' class='btn btn-success'>Create</button>"; 
                break;
            case 2: // read records
                $funButton = ""; // No functionality specific button required (Only need "back" button)
                break;
            case 3: // update records
                $funButton = "<button type='submit' class='btn btn-warning'>Update</button>";
                break;
            case 4: // delete records
                $funButton = "<button type='submit' class='btn btn-danger'>Delete</button>"; 
                break;
            case 0: // list records
            default:
                break;
        }
        echo " 
                            <div class='form-actions'>
                                $funButton
                                <a class='btn btn-secondary' href='$this->tableName.php'>Back</a>
                            </div>
                        </form>
                    </div>
                </div> <!-- /container -->
            </body>
        </html>
                    ";
  } // end function generate_html_bottom()
  
  // A function for validating the contents of all fields
  // The form of "validation" used is that of checking that the fields contain some form of information
  private function fieldsAllValid() {
    $valid = true;
    if (empty($this->name)) {
        $this->nameError = 'Please enter Name';
        $valid = false;
    }
    if (empty($this->email)) {
        $this->emailError = 'Please enter Email Address';
        $valid = false;
    } 
    else if ( !filter_var($this->email,FILTER_VALIDATE_EMAIL) ) {
        $this->emailError = 'Please enter a valid email address: me@mydomain.com';
        $valid = false;
    }
    if (empty($this->mobile)) {
        $this->mobileError = 'Please enter Mobile Number';
        $valid = false;
    }
    return $valid;
  } // end function fieldsAllValid()
  
  // A function for creating new customer records
  function customer_create() { // display "create" form
        $this->generate_html_top (1);
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("mobile", $this->mobileError, $this->mobile);
        $this->generate_html_bottom (1);
  } //end of customer_create()
  
  // A function for inserting customer records into the database table
  function customer_insert() {
    
       if ($this->fieldsAllValid ()) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $this->tableName (name,email,mobile) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->mobile));
            Database::disconnect();
            header("Location: $this->tableName.php");
        }
        else {
            $this->cutsomer_create(); // go back to "create" form
        }
  }  //end of customter_insert()
  
  // A function for reading customer records
  function customer_read($id) { // display "read" form
      $this->select_db_record($id);
      $this->generate_html_top(2);
      $this->control_group("name", $this->nameError, $this->name, "readonly");
      $this->control_group("email", $this->emailError, $this->email, "readonly");
      $this->control_group("mobile", $this->mobileError, $this->mobile, "readonly");
      $this->generate_html_bottom(2);
  } // end of customer_read()
  
  // A function for updating customer records
  function customer_update($id) { // display "update form"
     if($this->noerrors) $this->select_db_record($id);
        $this->generate_html_top(3, $id);
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("mobile", $this->mobileError, $this->mobile);
        $this->generate_html_bottom(3);
  } // end of customer_update
  
  // A function for deleting customer records
  function customer_delete($id) { // display "delete" form
      $this->select_db_record($id);
      $this->generate_html_top(4, $id);
      $this->control_group("name", $this->nameError, $this->name, "readonly");
      $this->control_group("email", $this->emailError, $this->email, "readonly");
      $this->control_group("mobile", $this->mobileError, $this->mobile, "readonly");
      $this->generate_html_bottom(4);
  } // end function customer_delete()
  
  // A function for selecting customer records
  function select_db_record($id) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM $this->tableName where id = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($id));
      $data = $q->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();
      $this->name = $data['name'];
      $this->email = $data['email'];
      $this->mobile = $data['mobile'];
  } // function select_db_record
  
  // A function for performing the update operation
  function update_db_record ($id) {
        $this->id = $id;
        if ($this->fieldsAllValid()) {
            $this->noerrors = true;
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE $this->tableName  set name = ?, email = ?, mobile = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->mobile,$this->id));
            Database::disconnect();
            header("Location: $this->tableName.php");
        }
        else {
            $this->noerrors = false;
            $this->update_record($id);  // go back to "update" form
        }
    } // end function update_db_record 
  
  // A function for performing the deletion operation
  function delete_db_record($id) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM $this->tableName WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: $this->tableName.php");
    } // end function delete_db_record()
  
} // end of Customers class
?>