<?php
include 'restrito/conexao.php';

function buildSearchQuery($terms, $columns) {
    $conditions = [];
    foreach ($terms as $term) {
        $termConditions = [];
        foreach ($columns as $column) {
            $termConditions[] = "$column LIKE '%$term%'";
        }
        $conditions[] = '(' . implode(' OR ', $termConditions) . ')';
    }
    return implode(' AND ', $conditions);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $terms = explode(' ', $query);
    $columns = ['nome', 'categoria', 'cor', 'marca', 'preco', 'genero', 'imagem']; // Colunas existentes na tabela 'produtos'
    $sql = "SELECT * FROM produtos WHERE " . buildSearchQuery($terms, $columns);
    
    $result = $conn->query($sql);

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
} elseif (isset($_GET['name']) || isset($_GET['categoria']) || isset($_GET['cor']) || isset($_GET['marca']) || isset($_GET['preco']) || isset($_GET['genero'])) {
    $name = $_GET['name'] ?? '';
    $categoria = $_GET['categoria'] ?? '';
    $cor = $_GET['cor'] ?? ''; // Correção de 'cores' para 'cor'
    $marca = $_GET['marca'] ?? '';
    $preco = $_GET['preco'] ?? '';
    $genero = $_GET['genero'] ?? '';
    $imagem = $_GET['imagem'] ?? '';

    $sql = "SELECT * FROM produtos WHERE 1=1";

    if ($name) $sql .= " AND nome LIKE '%$name%'";
    if ($categoria) $sql .= " AND categoria LIKE '%$categoria%'";
    if ($cor) $sql .= " AND cor LIKE '%$cor%'";
    if ($marca) $sql .= " AND marca LIKE '%$marca%'";
    if ($preco) $sql .= " AND preco LIKE '%$preco%'";
    if ($genero) $sql .= " AND genero LIKE '%$genero%'";
    if ($imagem) $sql .= " AND imagem LIKE '%$imagem%'";

    $result = $conn->query($sql);

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
}

?>
