<?php
require 'vendor/autoload.php';

use App\GoogleSearch;
use App\UserInput;
use App\FileReader;

$userInput = new UserInput();
$fileReader = new FileReader();
$googleSearch = new GoogleSearch();

// Solicitar a string de pesquisa do usuário
$query = $userInput->getInput("Digite a string de pesquisa: ");

// Listar as categorias disponíveis
$categories = [
    'categories/Footholds',
    'categories/Files_Containing_Usernames',
    'categories/Sensitive_Directories',
    'categories/Web_Server_Detection',
    'categories/Vulnerable_Files',
    'categories/Vulnerable_Servers',
    'categories/Error_Messages',
    'categories/Files_Containing_Juicy_Info',
    'categories/Files_Containing_Passwords',
    'categories/Sensitive_Online_Shopping_Info',
    'categories/Network_or_Vulnerability_Data',
    'categories/Pages_Containing_Login_Portals',
    'categories/Various_Online_Devices',
    'categories/Advisories_and_Vulnerabilities'
];

echo "Categorias disponíveis:\n";
foreach ($categories as $index => $category) {
    echo ($index + 1) . ". " . basename($category) . "\n";
}
echo (count($categories) + 1) . ". Todas as categorias\n";

// Solicitar a escolha da categoria
$choice = (int)$userInput->getInput("Escolha uma categoria (número): ");
$gaps = [];

if ($choice > 0 && $choice <= count($categories)) {
    $gaps = $fileReader->getGapsFromFile($categories[$choice - 1]);
} elseif ($choice == count($categories) + 1) {
    foreach ($categories as $category) {
        $gaps = array_merge($gaps, $fileReader->getGapsFromFile($category));
    }
} else {
    echo "Escolha inválida.\n";
    exit;
}

$links = $googleSearch->search($query, $gaps);

if (!$links) {
    echo "\n\n";
    echo "No vulnerabilities found.\n";
}
?>
