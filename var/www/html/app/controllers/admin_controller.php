<?php
class AdminController
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function getUsername($userId)
    {
        $query = "SELECT username FROM users WHERE id_user = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }

    public function toggleAdmin($user)
    {
        $newStatus = $user['is_admin'] == 1 ? 0 : 1;
        $query = "UPDATE users SET is_admin = ? WHERE id_user = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$newStatus, $user['id_user']]);
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRecipes()
    {
        $query = "SELECT * FROM recipes";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllComments()
    {
        $query = "SELECT * FROM comments";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRatings()
    {
        $query = "SELECT * FROM ratings";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($userId)
    {
        $query = "DELETE FROM users WHERE id_user = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteRecipe($recipeId)
    {
        $query = "DELETE FROM recipes WHERE id_recipe = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $recipeId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteComment($commentId)
    {
        $query = "DELETE FROM comments WHERE id_comment = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $commentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteRating($ratingId)
    {
        $query = "DELETE FROM ratings WHERE id_rating = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $ratingId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>