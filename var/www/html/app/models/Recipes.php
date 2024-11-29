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
        $query = "SELECT * FROM recipes WHERE id_recipe = " . $id;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 20241110 3:53 AM, completamente esquizo
    // btw si pones 2 puntos dentro de un string y lo siguiente que pones es el
    // nombre de la variable no te hace falta concatenar con puntos jeje
    // ya que con bind param te permite meter variables dentro de la query
    // por lo visto es una buena practica para no tener queries de un monton de texto
    public function createRecipe($recipe_type, $title, $description, $instructions, $difficulty, $ingredients, $image_recipe = null)
    {
        try {
            $this->pdo->beginTransaction();
            // Obtener id del usuario
            $id_user = $this->getUser('id_user');

            if (!$id_user) {
                throw new Exception('Usuario no encontrado');
            }

            if(!$image_recipe){
                $image_recipe = 'default.png';
            }

            // Insertar la receta
            $query = "INSERT INTO recipes (id_user, recipe_type, title, description, instructions, difficulty, image_recipe) 
                     VALUES (:id_user, :type, :title, :description, :instructions, :difficulty, :image_recipe)";
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

            foreach ($ingredients as $ingredient) {
                $query = "INSERT INTO ingredients (name) 
                         SELECT :name WHERE NOT EXISTS (
                             SELECT 1 FROM ingredients WHERE name = :name
                         )";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $ingredient);
                $stmt->execute();

                $query = "SELECT id_ingredient FROM ingredients WHERE name = :name";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $ingredient);
                $stmt->execute();
                $ingredient_id = $stmt->fetchColumn();

                $query = "INSERT INTO recipes_ingredients (id_recipe, id_ingredient) 
                         VALUES (:id_recipe, :id_ingredient)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id_recipe', $id_recipe);
                $stmt->bindParam(':id_ingredient', $ingredient_id);
                $stmt->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getUserRecipes()
    {
        $id_user = $this->getUser('id_user');
        $query = "SELECT * FROM recipes WHERE id_user = " . $id_user;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRecipeName($id_recipe)
    {
        // Obtener el id_user de la receta
        $query = "SELECT id_user FROM recipes WHERE id_recipe = :id_recipe";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_recipe', $id_recipe);
        $stmt->execute();
        $id_user = $stmt->fetchColumn();

        if (!$id_user) {
            return null;
        }
        $query = "SELECT username FROM users WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['username'] ?? null;
    }

    public function getIngredientsByRecipeId($recipe_id)
    {
        $query = "SELECT ingredients.name FROM ingredients 
              JOIN recipes_ingredients ON ingredients.id_ingredient = recipes_ingredients.id_ingredient 
              WHERE recipes_ingredients.id_recipe = :id_recipe";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_recipe', $recipe_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRecipe($id, $recipe_type, $title, $description, $instructions, $difficulty, $ingredients, $image_recipe = null)
    {
        try {
            $this->pdo->beginTransaction();

            $query = "UPDATE recipes SET 
                  recipe_type = :type, 
                  title = :title, 
                  description = :description, 
                  instructions = :instructions, 
                  difficulty = :difficulty ,
                  image_recipe = :image_recipe
                  WHERE id_recipe = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':type', $recipe_type);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':instructions', $instructions);
            $stmt->bindParam(':difficulty', $difficulty);
            $stmt->bindParam(':image_recipe', $image_recipe);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar ingredientes
            // Primero eliminar los existentes
            $query = "DELETE FROM recipes_ingredients WHERE id_recipe = :id_recipe";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_recipe', $id);
            $stmt->execute();

            foreach (json_decode($ingredients, true) as $ingredient) {
                $query = "INSERT INTO ingredients (name) 
                      SELECT :name WHERE NOT EXISTS (
                          SELECT 1 FROM ingredients WHERE name = :name
                      )";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $ingredient);
                $stmt->execute();

                $query = "SELECT id_ingredient FROM ingredients WHERE name = :name";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $ingredient);
                $stmt->execute();
                $ingredient_id = $stmt->fetchColumn();

                $query = "INSERT INTO recipes_ingredients (id_recipe, id_ingredient) 
                      VALUES (:id_recipe, :id_ingredient)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id_recipe', $id);
                $stmt->bindParam(':id_ingredient', $ingredient_id);
                $stmt->execute();
            }
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function deleteRecipe($id)
    {
        $query = "SELECT image_recipe FROM recipes WHERE id_recipe = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $imageName = $stmt->fetchColumn();

        $query = "DELETE FROM recipes WHERE id_recipe = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($imageName && file_exists(__DIR__ . '/../../assets/img/recipes/' . $imageName)) {
            unlink(__DIR__ . '/../../assets/img/recipes/' . $imageName);
        }
    }

    public function getCommentsByRecipeId($id_recipe)
    {
        $stmt = $this->pdo->prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.id_user = users.id_user WHERE id_recipe = ? ORDER BY comment_date DESC');
        $stmt->execute([$id_recipe]);
        return $stmt->fetchAll();
    }

    public function addComment($id_recipe, $description, $id_response_comment = null)
    {
        $id_user = $this->getUser('id_user');
        $stmt = $this->pdo->prepare('INSERT INTO comments (id_user, id_recipe, description) VALUES (:id_user, :id_recipe, :description)');
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_recipe', $id_recipe);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    public function deleteComment($id_comment)
    {
        $stmt = $this->pdo->prepare('DELETE FROM comments WHERE id_comment = ?');
        $stmt->execute([$id_comment]);
    }

    public function addRating($id_recipe, $score)
    {
        $id_user = $this->getUser('id_user');
        $stmt = $this->pdo->prepare('INSERT INTO ratings (id_user, id_recipe, score) VALUES (:id_user, :id_recipe, :score) ON DUPLICATE KEY UPDATE score = VALUES(score)');
        $stmt->execute([':id_user' => $id_user, ':id_recipe' => $id_recipe, ':score' => $score]);
    }
    public function deleteRating($id_recipe)
    {
        $id_user = $this->getUser('id_user');
        $stmt = $this->pdo->prepare('DELETE FROM ratings WHERE id_user = :id_user AND id_recipe = :id_recipe');
        $stmt->execute([':id_user' => $id_user, ':id_recipe' => $id_recipe]);
    }

    public function getRating($id_user ,$id_recipe)
    {
        $stmt = $this->pdo->prepare('SELECT score FROM ratings WHERE id_user = :id_user AND id_recipe = :id_recipe');
        $stmt->execute([':id_user' => $id_user, ':id_recipe' => $id_recipe]);
        return $stmt->fetchColumn();
    }
    public function getAverageRating($id_recipe)
    {
        $stmt = $this->pdo->prepare('SELECT AVG(score) as average_rating FROM ratings WHERE id_recipe = :id_recipe');
        $stmt->bindParam(':id_recipe', $id_recipe);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
