<?php

/* ---------------------------------------------------------------------------
 * filename    : events.php
 * author      : Devan Jekel, ddjekel@svsu.edu
 * description : This program utilizes the Events class (events.class.php)
 *               to run the object-oriented CRUD application for Prog01.
 *               (table: events)
 * ---------------------------------------------------------------------------
 */
require "database.php";
require "events.class.php";
$eve = new Events(); // Instantiation of Events class (events.class.php)

/* ---------------------------------------------------------------------------
 * Check to see if there is input data for (date,time,location,description,id)
 * and, if there is, update the Events object with the data.
 * Otherwise, list off entries from the database table as an html table.
 */
if(isset($_POST["date"])) $eve->date = $_POST["date"];
if(isset($_POST["time"])) $eve->time = $_POST["time"];  
if(isset($_POST["location"])) $eve->location = $_POST["location"];
if(isset($_POST["description"])) $eve->description = $_POST["description"];
if(isset($_GET["id"])) $id = $_GET["id"];

else $id = 0;
// "fun" (function): 0=list, 1=create, 2=read, 3=update, 4=delete
if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

// Switch statement for controlling CRUD operations from Events class.
switch ($fun) {
    case 1: // create
        $eve->event_create(); // display "create" form
        break;
    case 2: // read
        if($id > 0) $eve->event_read($id); // display "read" form
        else $eve->list_records(); // if no id, go to list screen
        break;
    case 3: // update
        if($id > 0) $eve->event_update($id); // display "update" form
        else $eve->list_records(); // if no id, go to list screen
        break;
    case 4: // delete
        if($id > 0) $eve->event_delete($id); // display "delete" form
        else $eve->list_records(); // if no id, go to list screen
        break;
    case 11: // insert database record from event_create() form
        $eve->event_insert(); // insert a new db record
        break;
    case 33: // update database record from event_update() form
        $eve->update_db_record($id);
        break;
    case 44: // delete database record from event_delete() form
        $eve->delete_db_record($id);
        break;
    case 0: // list
    default: 
        $eve->list_records();
        break;
}
?>