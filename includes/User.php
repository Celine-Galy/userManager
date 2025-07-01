<?php
class User {
    private $id;
    private $name;
    private $firstname;
    private $username;
    private $email;
    private $password;
    private $createdAt;
    
    public function __construct($id = null, $name, $firstname, $username, $email, $password = null, $createdAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->firstname = $firstname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt ?: date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getUsername() {
        return $this->username;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }

        public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Méthode pour vérifier le mot de passe
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
?>