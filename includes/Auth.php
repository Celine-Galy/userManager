<?php
require_once 'database.php';
require_once 'Session.php';
require_once 'User.php';

class Auth {
    private $db;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function findUserByEmail($email) {
    $stmt = $this->db->prepare(
        "SELECT id, name, firstname, username, email, password, created_at FROM users 
        WHERE email = :email LIMIT 1"
        );
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Debug crucial
    error_log("DEBUG - Données récupérées de la base : " . print_r($userData, true));
    
    return $userData;
}

    public function register($name, $firstname, $username, $email, $password) {
        if ($this->findUserByEmail($email)) {
            throw new Exception("Un utilisateur avec cet email existe déjà");
        }
        
        $user = new User(
            null, $name, $firstname, $username, $email);
        $user->setPassword($password);
        
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, firstname, username, email, password) 
             VALUES (:name, :firstname, :username, :email, :password)"
        );

        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':firstname', $user->getFirstname());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        
        if ($stmt->execute()) {
            return $this->login($email, $password);
        }
        
        return false;
    }
    
    public function login($email, $password) {
    $userData = $this->findUserByEmail($email);
    
    if (!$userData) {
        throw new Exception("Utilisateur non trouvé");
    }

    // Vérification EXPLICITE du hash
    if (!isset($userData['password']) || $userData['password'] === null) {
        error_log("ERREUR CRITIQUE : Le hash du mot de passe est NULL pour l'email : $email");
        throw new Exception("Erreur système : aucun mot de passe défini");
    }

    // Verification directe sans passer par User
    if (!password_verify($password, $userData['password'])) {
        throw new Exception("Mot de passe incorrect");
    }
      // Création de l'utilisateur seulement après vérification
    $user = new User(
        $userData['id'],
        $userData['name'],
        $userData['firstname'],
        $userData['username'],
        $userData['email'],
        null, // Ne jamais stocker le hash en session
        $userData['created_at']
    );

    Session::set('user_id', $user->getId());
    Session::set('user_name', $user->getName());
    Session::set('user_firstname', $user->getFirstname());
    Session::set('user_email', $user->getEmail());
    Session::set('user_username', $user->getUsername());

    return true;
}
  public function logout() {
    // Détruit toutes les données de session
    $_SESSION = array();
    
    // Si vous voulez détruire complètement la session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}
    
    public function getCurrentUser() {
        if (!Session::isLoggedIn()) {
            return null;
        }
        
        $userData = $this->findUserById(Session::get('user_id'));
        
        if (!$userData) {
            return null;
        }
        
        return new User(
            $userData['id'],
            $userData['name'],
            $userData['firstname'],
            $userData['username'],
            $userData['email'],
            null, // On ne retourne pas le mot de passe
            $userData['created_at']
        );
    }
    
    private function findUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>