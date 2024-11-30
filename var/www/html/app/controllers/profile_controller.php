<?php
class ProfileController{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserDataByName($username)
    {
        $consulta = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($consulta);
        // se me habia olvidado pasar el parametro a string y me segui a mi mismo soy un genio incomprendido
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch();

        if (!$userData) {
            header("Location: /404");
            exit();
        }

        return $userData;
    }

    public function getUserDataById($id_user)
    {
        $consulta = "SELECT * FROM users WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch();

        if (!$userData) {
            header("Location: /404");
            exit();
        }

        return $userData;
    }

    public function follow($id_follower, $id_followed)
    {
        $consulta = "INSERT INTO followers (id_follower, id_followed) VALUES (:id_follower, :id_followed)";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':id_follower', $id_follower, PDO::PARAM_INT);
        $stmt->bindValue(':id_followed', $id_followed, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function unfollow($id_follower, $id_followed)
    {
        $consulta = "DELETE FROM followers WHERE id_follower = :id_follower AND id_followed = :id_followed";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':id_follower', $id_follower, PDO::PARAM_INT);
        $stmt->bindValue(':id_followed', $id_followed, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getFollowers($id_user)
    {
        $consulta = "SELECT users.* FROM users INNER JOIN followers ON users.id_user = followers.id_follower WHERE followers.id_followed = :id_user";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFollowed($id_user)
    {
        $consulta = "SELECT users.* FROM users INNER JOIN followers ON users.id_user = followers.id_followed WHERE followers.id_follower = :id_user";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteFollower($id_follower, $id_followed)
    {
        return $this->unfollow($id_follower, $id_followed);
    }

}