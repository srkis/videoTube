<?php

class Account
{
    private $con;
    private $errorArray = [];

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function login($username, $password)
    {
        $password = hash("sha512", $password);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $query->bindParam(":username", $username);
        $query->bindParam(":password", $password);

        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }
        else{
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }

    public function register($firstName, $lastName, $username, $email, $password, $password2)
    {
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password, $password2);

        if(empty($this->errorArray)){

           return $this->insertUserDetails($firstName, $lastName, $username, $email, $password);
        }
        else{
            return false;
        }

    }

    public function updateDetails($firstName, $lastName, $username, $email){

        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateLastName($lastName);
        $this->validateNewEmail($email,$username);

        if(empty($this->errorArray)){
           $query = $this->con->prepare("UPDATE users SET firstName=:firstName, lastName=:lastName, email=:email WHERE username=:username");
           $query->bindParam(":firstName", $firstName);
           $query->bindParam(":lastName", $lastName);
           $query->bindParam(":email", $email);
           $query->bindParam(":username", $username);

           return $query->execute();

        }else{
            return false;
        }
    }

    public function updatePassword($oldPassword, $newPassword, $username, $newPassword2){

        $this->validateOldPassword($oldPassword, $username);
        $this->validatePassword($newPassword, $newPassword2);

        if(empty($this->errorArray)){
            $query = $this->con->prepare("UPDATE users SET password =:password WHERE username=:username");
            $password = hash("sha512", $newPassword);
            $query->bindParam(":password", $password);
            $query->bindParam(":username", $username);

            return $query->execute();

        }else{
            return false;
        }
    }

    private function validateOldPassword($oldPassword, $username){
        $password = hash("sha512", $oldPassword);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $query->bindParam(":username", $username);
        $query->bindParam(":password", $password);

        $query->execute();

        if($query->rowCount() == 0) {
            array_push($this->errorArray, Constants::$passwordIncorrect);
        }
    }


    public function insertUserDetails($firstName, $lastName, $username, $email, $password)
    {

        $password = hash("sha512", $password);
        $profilePic = "assets/images/profilePictures/default.png";

        $query = $this->con->prepare("INSERT INTO users (firstName,lastName,username,email,password, profilePic)
                                        VALUES(:firstName, :lastName, :username, :email, :password, :profilePic) ");

        $query->bindParam(":firstName", $firstName);
        $query->bindParam(":lastName", $lastName);
        $query->bindParam(":username", $username);
        $query->bindParam(":email", $email);
        $query->bindParam(":password", $password);
        $query->bindParam(":profilePic", $profilePic);

        return $query->execute();
    }

    private function validateFirstName($firstName)
    {
        if(strlen($firstName) > 25 || strlen($firstName) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }

    private function validateLastName($lastName)
    {
        if(strlen($lastName) > 25 || strlen($lastName) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }

    private function validateUsername($username)
    {
        if(strlen($username) > 25 || strlen($username) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT username FROM users WHERE username=:username");
        $query->bindParam(":username", $username);
        $query->execute();

        if($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    private function validateNewEmail($email, $username)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email  FROM users WHERE email=:email AND username != :username");
        $query->bindParam(":email", $email);
        $query->bindParam(":username", $username);
        $query->execute();

        if($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email  FROM users WHERE email=:email");
        $query->bindParam(":email", $email);
        $query->execute();

        if($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    public function validatePassword($password, $password2)
    {
        if($password != $password2){
            array_push($this->errorArray, Constants::$passwordsNotMatch);
            return;
        }

        if(preg_match("/[^A-Za-z0-9]/", $password)){
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }

        if(strlen($password) > 30 || strlen($password) < 5) {
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }

    public function getError($error)
    {
        if(in_array($error, $this->errorArray)){
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public function getFirstError(){
        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }else{
            return "";
        }
    }



}