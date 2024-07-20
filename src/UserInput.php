<?php

namespace App;

class UserInput
{

    public function getInput($prompt)
    {
        if ($prompt == "Digite a string de pesquisa: ") {

            $asciiArt = "
            _____             _    _____       _            _   
           |  __ \           | |  |  __ \     | |          | |  
           | |  | | ___  _ __| | _| |  | | ___| |_ ___  ___| |_ 
           | |  | |/ _ \| '__| |/ / |  | |/ _ \ __/ _ \/ __| __|
           | |__| | (_) | |  |   <| |__| |  __/ ||  __/ (__| |_ 
           |_____/ \___/|_|  |_|\_\_____/ \___|\__\___|\___|\__|
                                                                                                               
          ";

            $resumo = "
            Sobre:
            ---------------------
            Esta ferramenta, chamada DorkDetect, é uma solução para busca e análise de vulnerabilidades na web utilizando Google Dorks. 
            O DorkDetect permite realizar buscas automatizadas em diversas categorias, detectando possíveis falhas de segurança e vulnerabilidades 
            em sites específicos. Utilizando técnicas de alternância de User-Agents e atrasos entre requisições, a ferramenta minimiza a possibilidade 
            de bloqueio por tráfego incomum. Com uma interface de linha de comando fácil de usar, o DorkDetect é uma adição ao arsenal 
            de qualquer pesquisador de segurança ou profissional de TI.
            ";

            echo $asciiArt . "\n" . $resumo . "\n\n";
        }

        echo $prompt;
        return trim(fgets(STDIN));
    }
}
