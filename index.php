<?php
// Caminho do arquivo JSON
$jsonFile = 'cat.json';

// Carregar e decodificar o conteúdo do arquivo JSON
$jsonData = json_decode(file_get_contents($jsonFile), true);

// Verificar se a decodificação foi bem-sucedida
if ($jsonData === null) {
    die("Erro ao decodificar o arquivo JSON.");
}

// Inicializar uma string para armazenar as dorks da categoria "Footholds"
$footholdsDorks = "";

// Percorrer os dados e extrair as dorks da categoria "Footholds"
foreach ($jsonData['data'] as $entry) {
    if (in_array("1", $entry['cat_id'])) {
        $dork = strip_tags($entry['url_title']); // Remover tags HTML
        $footholdsDorks .= $dork . PHP_EOL; // Adicionar a dork à string com uma nova linha
    }
}

// Caminho do arquivo de saída
$outputFile = 'Footholds';

// Escrever as dorks no arquivo de saída
file_put_contents($outputFile, $footholdsDorks);

echo "Dorks da categoria 'Footholds' foram salvas em: $outputFile\n";
?>
