<?php
class Recipes
{
    private $pdo;

    // funcion para obtener el atributo que quiera de los usuarios
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

    public function getAllIngredients()
    {
        $query = "SELECT * FROM ingredients";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    // por lo visto es una buena practica para no tener queries de un monton de texto
    public function createRecipe($recipe_type, $title, $description, $instructions, $difficulty, $ingredients, $quantities, $units, $new_ingredient =null, $image_recipe=null)
    {
        // Obtener id del usuario
        $id_user = $this->getUser('id_user');

        if ($new_ingredient) {
            $query = "SELECT id_ingredient FROM ingredients WHERE name = :name";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $new_ingredient);
            $stmt->execute();

            $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ingredient) {
                $query = "INSERT INTO ingredients (name) VALUES (:name)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $new_ingredient);
                $stmt->execute();
                $new_ingredient_id = $this->pdo->lastInsertId();
            } else {
                $new_ingredient_id = $ingredient['id_ingredient'];
            }
            $ingredients[] = $new_ingredient_id;
            $quantities[] = 1;
            $units[] = 1;
        }

        $query = "INSERT INTO recipes (id_user, recipe_type, title, description, instructions, difficulty, image_recipe) VALUES (:id_user, :type, :title, :description, :instructions, :difficulty, :image_recipe)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':type', $recipe_type);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':image_recipe', $image_recipe);
        $stmt->execute();

        $id_recipe = $this->pdo->lastInsertId();

        // Insertar cada ingrediente en recipes_ingredients
        for ($i = 0; $i < count($ingredients); $i++) {
            $query = "INSERT INTO recipes_ingredients (id_recipe, id_ingredient, quantity, unit) VALUES (:id_recipe, :id_ingredient, :quantity, :unit)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_recipe', $id_recipe);
            $stmt->bindParam(':id_ingredient', $ingredients[$i]);
            $stmt->bindParam(':quantity', $quantities[$i]);
            $stmt->bindParam(':unit', $units[$i]);
            $stmt->execute();
        }

        return true;
    }


    public function getUserRecipes()
    {
        $id_user = $this->getUser('id_user');
        $query = "SELECT * FROM recipes WHERE id_user = " . $id_user;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRecipeName()
    {
        $id_user = $this->getUser('id_user');
        $query = "SELECT users.username FROM recipes JOIN users ON recipes.id_user = users.id_user WHERE recipes.id_user = :id_user";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
