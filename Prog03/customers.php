<?php

/* ---------------------------------------------------------------------------
 * filename    : customers.php
 * author      : Devan Jekel, ddjekel@svsu.edu
 * description : This program utilizes the Customers class (customers.class.php)
 *               to run the object-oriented CRUD application for Prog01.
 *               (table: customers)
 * ---------------------------------------------------------------------------
 */
require "database.php";
require "customers.class.php";
$cust = new Customers(); // Instantiation of Customers class (customers.class.php)

/* ---------------------------------------------------------------------------
 * Check to see if there is input data for (name,email,mobile,id)
 * and, if there is, update the Customers object with the data.
 * Otherwise, list off entries from the database table as an html table.
 */
if(isset($_POST["name"])) $cust->name = $_POST["name"];
if(isset($_POST["email"])) $cust->email = $_POST["email"];
if(isset($_POST["mobile"])) $cust->mobile = $_POST["mobile"];
if(isset($_GET["id"])) $id = $_GET["id"];

else $id = 0;
// "fun" (function): 0=list, 1=create, 2=read, 3=update, 4=delete
if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

// Switch statement for controlling CRUD operations from Customers class.
switch ($fun) {
    case 1: // create
        $cust->customer_create(); // display "create" form
        break;
    case 2: // read
        if($id > 0) $cust->customer_read($id); // display "read" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 3: // update
        if($id > 0) $cust->customer_update($id); // display "update" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 4: // delete
        if($id > 0) $cust->customer_delete($id); // display "delete" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 11: // insert database record from customer_create() form
        $cust->customer_insert(); // insert a new db record
        break;
    case 33: // update database record from customer_update() form
        $cust->update_db_record($id);
        break;
    case 44: // delete database record from customer_delete() form
        $cust->delete_db_record($id);
        break;
    case 0: // list
    default: 
        $cust->list_records();
        break;
}
?>