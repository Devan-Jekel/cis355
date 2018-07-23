<?php

/* ---------------------------------------------------------------------------
 * filename    : events.class.php
 * author      : Devan Jekel, ddjekel@svsu.edu
 * description : This program details the Events class used in a CRUD
 *               application made to satisfy the requirements of Prog01
 *               (table: events)
 * ---------------------------------------------------------------------------
 */
class Events {
  public $id;
  public $date;
  public $time;
  public $location;
  public $description;
  
  // Errors, Titles, and Table Names are attributes we don't want outside users
  // to be able to access directly, thus these attributes are private.
  private $noerrors = true;
  private $dateError = null;
  private $timeError = null;
  private $locationError = null;
  private $descriptionError = null;
  private $title = "Event";
  private $tableName = "events";
  
  // A function to list the Event table contents
  // This represents the events table that should be displayed 
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
                        <a href='customers.php' class='btn btn-info'>Customers Table</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM $this->tableName ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["event_date"] . "</td>";
            echo "<td>". $row["event_time"] . "</td>";
            echo "<td>". $row["event_location"] . "</td>";
            echo "<td>". $row["event_description"] . "</td>";
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
                            <h3>$funWord an $this->title</h3>
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
                $funButton = "";
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
    if (empty($this->date)) {
        $this->dateError = 'Please enter Date';
        $valid = false;
    }
    if (empty($this->time)) {
        $this->timeError = 'Please enter Time';
        $valid = false;
    } 
    if (empty($this->location)) {
        $this->locationError = 'Please enter Location';
        $valid = false;
    }
    if (empty($this->description)) {
        $this->descriptionError = 'Please enter Description';
        $valid = false;
    }
    return $valid;
  } // end function fieldsAllValid()
  
  // A function for creating new event records
  function event_create() { // display "create" form
        $this->generate_html_top (1);
        $this->control_group("date", $this->dateError, $this->date);
        $this->control_group("time", $this->timeError, $this->time);
        $this->control_group("location", $this->locationError, $this->location);
        $this->control_group("description", $this->descriptionError, $this->description);
        $this->generate_html_bottom (1);
  } //end of event_create()
  
  // A function for inserting event records into the database table
  function event_insert() {
    
       if ($this->fieldsAllValid ()) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $this->tableName (event_date,event_time,event_location,event_description) values(?, ?, ?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->date,$this->time,$this->location,$this->description));
            Database::disconnect();
            header("Location: $this->tableName.php");
        }
        else {
            $this->event_create(); // go back to "create" form
        }
  }  //end of event_insert()
  
  // A function for reading event records
  function event_read($id) { // display "read" form
      $this->select_db_record($id);
      $this->generate_html_top(2);
      $this->control_group("date", $this->dateError, $this->date, "readonly");
      $this->control_group("time", $this->timeError, $this->time, "readonly");
      $this->control_group("location", $this->locationError, $this->location, "readonly");
      $this->control_group("description", $this->descriptionError, $this->description, "readonly");
      $this->generate_html_bottom(2);
  } // end of event_read()
  
  // A function for updating event records
  function event_update($id) { // display "update form"
      if($this->noerrors) $this->select_db_record($id);
      $this->generate_html_top(3, $id);
      $this->control_group("date", $this->dateError, $this->date);
      $this->control_group("time", $this->timeError, $this->time);
      $this->control_group("location", $this->locationError, $this->location);
      $this->control_group("description", $this->descriptionError, $this->description);
      $this->generate_html_bottom(3);
  } // end of event_update
  
  // A function for deleting event records
  function event_delete($id) { // display "delete" form
      $this->select_db_record($id);
      $this->generate_html_top(4, $id);
      $this->control_group("date", $this->dateError, $this->date, "readonly");
      $this->control_group("time", $this->timeError, $this->time, "readonly");
      $this->control_group("location", $this->locationError, $this->location, "readonly");
      $this->control_group("description", $this->descriptionError, $this->description, "readonly");
      $this->generate_html_bottom(4);
  } // end function event_delete()
  
  // A function for selecting event records
  function select_db_record($id) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM $this->tableName where id = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($id));
      $data = $q->fetch(PDO::FETCH_ASSOC);
      Database::disconnect();
      $this->date = $data['event_date'];
      $this->time = $data['event_time'];
      $this->location = $data['event_location'];
      $this->description = $data['event_description'];
  } // function select_db_record
  
  // A function for performing the update operation
  function update_db_record ($id) {
        $this->id = $id;
        if ($this->fieldsAllValid()) {
            $this->noerrors = true;
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE $this->tableName  set event_date = ?, event_time = ?, event_location = ?, event_description = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->date,$this->time,$this->location,$this->description,$this->id));
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
  
} // end of Events class
?>