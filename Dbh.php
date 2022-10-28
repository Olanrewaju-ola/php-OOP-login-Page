<?php

// Creating Class Dbh
 class Dbh {

        private $hostname;
        private $username;
        private $password;
        private $dbname;

    // Creating the class properties

    protected function connect(){
       $this->hostname = 'localhost';
       $this->username = 'olanrewaju';
       $this->password = 'olanrewaju';
       $this->dbname = 'zuriphp';

       $conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname);

       if(!$conn){
        echo "<script> alert('Error connecting to the database') </script>";
    }
    return $conn;
    }
}
    


    


