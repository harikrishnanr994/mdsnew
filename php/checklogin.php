<?php
  session_start();
require_once("config.php");
$link = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die("Connection Failed");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $email= mysqli_real_escape_string($link, $_POST['email']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
 
    $query = mysqli_query($link, "Select * from admin WHERE email='$email'"); // Query the users table
    $exists = mysqli_num_rows($query); //Checks if email exists
  


    if($exists > 0) //IF there are no returning rows or no existing username
    {
       while($row = mysqli_fetch_array($query)) // display all rows from query
       {
          $table_users = $row['email']; // the first username row is passed on to $table_users, and so on until the query is finished
          $table_password = $row['password']; // the first password row is passed on to $table_password, and so on until the query is finished

       }
       if(($email== $table_users))// checks if there are any matching fields
       {
         
              if($password == $table_password)
              { 
                 $_SESSION['user'] = $email;
                  //set the username in a session. This serves as a global variable
                 header("location: index.php"); // redirects the user to the authenticated home page
              }
              else
              {
                Print '<script>alert("Incorrect Password!");</script>'; // Prompts the user
                Print '<script>window.location.assign("login.php");</script>'; // redirects to login.php
              }
          
        


        }
        else
        {
            Print '<script>alert("Incorrect email!");</script>'; // Prompts the user
           Print '<script>window.location.assign("login.php");</script>'; // redirects to login.php
        }
    }
    else
      {
        Print '<script>alert("User not found");</script>'; // Prompts the user
        Print '<script>window.location.assign("register.php");</script>'; // redirects to login.php
      }
?>