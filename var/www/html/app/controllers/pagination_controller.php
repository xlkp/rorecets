<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Recipes.php';

class PaginationController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPaginatedRecipes($recetasPorPagina)
    {
        $recipesModel = new Recipes($this->pdo);
        $allRecipes = $recipesModel->getAllRecipes();
        $totalRecetas = count($allRecipes);
        $totalPaginas = ceil($totalRecetas / $recetasPorPagina);

        $paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        $offset = ($paginaActual - 1) * $recetasPorPagina;

        $consulta = "SELECT * FROM recipes LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindValue(':limit', $recetasPorPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $recetasPagina = $stmt->fetchAll();

        return [
            'recetasPagina' => $recetasPagina,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas
        ];
    }
}
