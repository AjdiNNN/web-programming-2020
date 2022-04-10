<?php

class UserDao
{

  private $conn;

  /**
  * constructor of dao class
  */
  public function __construct()
  {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $schema = "auctions";
    $this->conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);
    // set the PDO error mode to exception
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
  * Method used to read user from database
  */
  public function get_by_email($email){
    $stmt = $this->conn->prepare("SELECT * FROM todos WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return reset($result);
  }

  /** Check if email exists */
  public function check_email($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute(); 
    $checkemail = $stmt->fetch();
    return $checkemail ? true : false;
  }
  /**
  * Check if username is occupied
  */
  public function check_user($username)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute(); 
    $checkusername = $stmt->fetch();
    return $checkusername ? true : false;
  }
  /**
  * Method used to add user to the database
  */
  public function add($userdata)
  {
    $stmt = $this->conn->prepare("INSERT INTO users (username, firstname, secondname, email, password) VALUES (:username, :fname, :sname, :email, :hashedpassword)");
    $stmt->bindParam(':username', $userdata['username']);
    $stmt->bindParam(':fname', $userdata['fname']);
    $stmt->bindParam(':sname', $userdata['sname']);
    $stmt->bindParam(':email', $userdata['email']);
    $stmt->bindParam(':hashedpassword', $hashedpassword);
    $hashedpassword = password_hash($userdata['password'], PASSWORD_ARGON2I);
    $stmt->execute();
    $userdata['id'] = $this->conn->lastInsertId();
    return $userdata;
  }
  /**
  * Delete user record from the database
  */
  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE id=:id");
    $stmt->bindParam(':id', $id); // SQL injection prevention
    $stmt->execute();
  }

  /**
  * Update user record
  */
  public function update($userdata)
  {
    $hashedpassword = hash('sha256', $password, false);
    $stmt = $this->conn->prepare("UPDATE users SET username=:username, firstname=:firstname, secondname=:secondname, email=:email, hashedpassword=:hashedpassword   WHERE id=:id");
    $stmt->execute($userdata);
    return $userdata;
  }

}

function wh_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
} 

?>