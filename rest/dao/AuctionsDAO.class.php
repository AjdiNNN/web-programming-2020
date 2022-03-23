<?php

class AuctionsDAO
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
  * Method used to read all users objects from database
  */
  public function get_all()
  {
    $stmt = $this->conn->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  /**
  * Method used to read users from database
  */
  public function get_by_id($id){
    $stmt = $this->conn->prepare("SELECT * FROM todos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return reset($result);
  }
  /**
  * Method used to add user to the database
  */
  public function add($userdata)
  {
    $hashedpassword = password_hash($password, PASSWORD_ARGON2I);
    echo $hashedpassword;
    $stmt = $this->conn->prepare("INSERT INTO users (username, firstname, secondname, email, password) VALUES (:username, :firstname, :secondname, :email, :hashedpassword)");
    $stmt->execute($userdata);
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

?>