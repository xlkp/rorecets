<?php
class Recipes
{
    private $pdo;

    // funcion para obtener el atributo que quiera de los usuarios
    private function getUser($attribute)
    {
        // me lo dejo de referencia
        // $atributes = ['id_user', 'username', 'email', 'pwd', 'exp', 'is_admin', 'registration_date'];
        $query = "SELECT $attribute FROM users WHERE username =" . $_SESSION['username'];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)[$attribute];
    }

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllRecipes()
    {
        $query = "SELECT * FROM recipes";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecipeById($id)
    {
        $query = "SELECT * FROM recipes WHERE id = " . $id;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 20241110 3:53 AM, completamente esquizo
    // btw si pones 2 puntos dentro de un string y lo siguiente que pones es el
    // nombre de la variable no te hace falta concatenar con puntos jeje
    // ya que con bind param te permite meter variables dentro de la query
    // por lo visto es una buenapractica para no tener queries de un monton de texto
    public function createRecipe($recipe_type, $title, $description, $instructions, $difficulty)
    {
        $id_user = $this->getUser('id_user');
        $query = "INSERT INTO recipes (id_user, recipe_type, title, description, instructions, difficulty) VALUES (:id_user, :type, :title, :description, :instructions, :difficulty)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':type', $recipe_type);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':difficulty', $difficulty);
        return $stmt->execute();
    }
}
