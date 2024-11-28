<?php
class CommentsController {
    private $recipes;

    public function __construct($recipes) {
        $this->recipes = $recipes;
    }

    public function handleRequest($id_recipe) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['submit_comment'])) {
                $this->addComment($id_recipe);
            } elseif (isset($_POST['delete_comment'])) {
                $this->deleteComment($id_recipe);
            }
        }
    }

    private function addComment($id_recipe) {
        $description = $_POST['description'];
        $score = $_POST['score'];
        $this->recipes->addComment($id_recipe, $description);
        $this->recipes->addRating($id_recipe, $score);
        header("Location: /recipes/view?id_recipe=$id_recipe");
        exit;
    }

    private function deleteComment($id_recipe) {
        $id_comment = $_POST['id_comment'];
        $this->recipes->deleteComment($id_comment);
        header("Location: /recipes/view?id_recipe=$id_recipe");
        exit;
    }
}
?>