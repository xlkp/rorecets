<?php

class Follower
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function getUser($attribute)
    {
        // $atributosPermitidos = ['id_user', 'username', 'email', 'pwd', 'exp', 'is_admin', 'registration_date'];
        // Consulta segura con parámetro preparado
        $query = "SELECT $attribute FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result[$attribute] ?? null;
    }

    private function follow($id_followed)
    {
        $id_follower = $this->getUser('id_user');

        try {
            $query = "INSERT INTO followers (id_follower, id_followed) VALUES (:id_follower, :id_followed)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id_follower' => $id_follower,
                ':id_followed' => $id_followed
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    private function unfollow($id_followed)
    {
        $id_follower = $this->getUser('id_user');

        $query = "DELETE FROM followers WHERE id_follower = :id_follower AND id_followed = :id_followed";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':id_follower' => $id_follower,
            ':id_followed' => $id_followed
        ]);
    }

    private function getFollowers()
    {
        $user_id = $this->getUser('id_user');

        $query = "SELECT u.username, u.id_user, f.follow_date 
              FROM followers f 
              JOIN users u ON f.id_follower = u.id_user 
              WHERE f.id_followed = :user_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function removeFollower($id_follower)
    {
        $user_id = $this->getUser('id_user');

        $query = "DELETE FROM followers WHERE id_follower = :id_follower AND id_followed = :user_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':id_follower' => $id_follower,
            ':user_id' => $user_id
        ]);
    }
}
