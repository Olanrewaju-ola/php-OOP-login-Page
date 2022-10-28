<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    private $db;
    
    public function __construct(){
        $this->db = new Dbh();
    }


    public function validatepassword($password, $confirmPassword){
        
        $this->password = $password;
        $this->confirmpassword = $confirmPassword;

        if($this->password == $this->confirmPassword){
            return true;
        }
        else{
            return false;
        }
       
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $this->email = $email;
        $sql = "SELECT * FROM students WHERE email='$this->email'";
        $result = $this->db->connect()->query($sql);

        if(result->num_row>0){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }

    public function checkPassword($password){

        $conn = $this->db->connect();
        $this->password = $password;
        $sql = "SELECT * FROM students WHERE password='$this->password'";
        $result = $conn()->query($sql);

        if($result->num_row>0){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db();
        if($this->validatepassword($password, $confirmPassword)){
            if(($this->checkEmailExist($email)) !== TRUE)
                $sql = ("INSERT INTO students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')");
                if($conn->query($sql)){
                echo "Registration was successful";
                header("Location: ../forms/login");
            }
            else{
                echo "Email already exist";
                header("refresh:0.5; url=../forms/register.php");
        }
        }
        else{
            echo "Password does not match";
            header("refresh:0.5; url=../forms/register.php");
        }
        
    }

    public function login($email, $password){
        $conn = $this->db->connect();

       // if(this->checkEmailExist($email) == true && this->checkPassword($password) ==true){
        if(this->checkEmailExist($email) == true){
            if (this->checkPassword($password) ==true){
                $_SESSION['email'] = $email;
                $this->email = $_SESSION['email'];
                echo "<script>alert('You are logged in successfully')";
                header("Location: ../dashboard.php");
                
            }
        
            else{
                echo "<script>alert('Either your email or password is wrong')";
                header("Location: ../forms/login.html");
            }
        }
        
        else{
            echo "<script>alert('Either your email or password is wrong')";
            // header
        }
    }

    public function updateUser($email, $password){

        $conn = $this->db->connect();
        $result = $this->checkEmailExist($email);
        
        if($result){
            $sql = "UPDATE students SET password = 'password' WHERE email = 'email'";
                if ($conn->query($sql) == true){
                    echo "<script>alert('Password has been changed')";
                    // header
                }
    }
        else{
            echo "<script>alert('PEmail does not exist')";
            // header
        }
}   
    public function deleteUser($email){

        $conn = $this->db->connect();
        $sql_one = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql_one);
            if($result->num_rowa>0){
                $sql_two = "DELETE FROM students WHERE email = '$email'";
                if($conn->query($sql_two) == true){
                    echo "<script>alert('Account Record Deleted Successfully')";
                    // header dashboard
                }
                else{
                    echo "<script>alert('Account could not be deleted');
                    window.location = 'dashboard.php';</script>";
                }
            }
        }

    public function logout($email){
        $this->email = $_SESSION['email'];
        if ($this->email){
            session_unset();
            session_destroy();
            header('Location: ../forms/login.php');
        }
    }


    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

}