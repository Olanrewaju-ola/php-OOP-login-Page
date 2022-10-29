<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    public $db;
    
    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if(($this->checkEmailExist($email)) == true){
            echo "<script>alert('Email Already exist');
                window.location = './forms/register.php';</script>";
                exit;
        }
        if($this->validatepassword($password, $confirmPassword)){
            $sql = ("INSERT INTO students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')");
            if($conn->query($sql)){
                echo "<script>alert('Registration was successful');
                window.location = './forms/login.php';</script>";
            }
            else{
                echo "<script>alert('Password does not match');
                window.location = './forms/login.php';</script>";
            }
    }
}

    public function validatepassword($password, $confirmPassword){
        $this->password = $password;
        $this->confirmpassword = $confirmPassword;

        if($this->password == $this->confirmPassword){
            return true;
        }
        else{
            echo "<script>alert('Opps!! Password does not match');
                window.location = './forms/register.php';</script>";
        }
       
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $this->email = $email;
        $sql = "SELECT * FROM students WHERE email='$this->email'";
        $result = $this->db->connect()->query($sql);

        if($result->num_row>0){
            return true;
        }
        else{
            echo "<script>alert('Email Already exist');
                window.location = './forms/register.php';</script>";
        }
        return $result;
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if($result->num_rows>0){       
            $_SESSION['email'] = $email;
            header("Location: ./dashboard.php");
        }
        else{
            header("Location: ./forms/login.php");
        }
    }

    public function updateUser($email, $password){
        $conn = $this->db->connect();
        $result = $this->checkEmailExist($email);
        if($result){
            $sql = "UPDATE students SET password = '$password' WHERE email = '$email'";
                if ($conn->query($sql) == true){
                    echo "<script>alert('Password change was successful');
                    window.location = './forms/login.php';</script>";
                }
    }
        else{
            echo "<script>alert('User does not exist');
            window.location = './forms/register.php';</script>";
        }
}   
    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql_one = "SELECT * FROM students WHERE id = '$id'";
        $result = $conn->query($sql_one);
            if($result->num_rows>0){
                $delete_user = "DELETE FROM students WHERE id = '$id'";
                if($conn->query($delete_user) == true){
                    echo "<script>alert('Account Record Deleted Successfully');
                    window.location = './dashboard.php';</script>";
                }
                else{
                    echo "<script>alert('Account could not be deleted');
                    window.location = './dashboard.php';</script>";
                }
            }
        }

    public function logout($email){
            session_unset();
            session_destroy();
            header('Location: ./index.php');
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