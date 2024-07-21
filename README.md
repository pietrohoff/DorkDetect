
# DorkDetect

```
  _____             _    _____       _            _   
 |  __ \           | |  |  __ \     | |          | |  
 | |  | | ___  _ __| | _| |  | | ___| |_ ___  ___| |_ 
 | |  | |/ _ \| '__| |/ / |  | |/ _ \ __/ _ \/ __| __|
 | |__| | (_) | |  |   <| |__| |  __/ ||  __/ (__| |_ 
 |_____/ \___/|_|  |_\_\_____/ \___|\__\___|\___|\__|
                                                     
                                                     
```

## Resumo da Ferramenta
Esta ferramenta, chamada DorkDetect, é uma solução para busca e análise de vulnerabilidades na web utilizando Google Dorks. 
O DorkDetect permite realizar buscas automatizadas em diversas categorias, detectando possíveis falhas de segurança e vulnerabilidades 
em sites específicos. Utilizando técnicas de alternância de User-Agents e atrasos entre requisições, a ferramenta minimiza a possibilidade 
de bloqueio por tráfego incomum. Com uma interface de linha de comando fácil de usar, o DorkDetect é uma adição ao arsenal 
de qualquer pesquisador de segurança ou profissional de TI.

## Estrutura do Projeto

```
project/
├── src/
│   ├── GoogleSearch.php
│   ├── UserInput.php
│   ├── FileReader.php
├── vendor/
│   └── autoload.php
├── categories/
│   ├── category1.txt
│   ├── category2.txt
│   ├── category3.txt
└── main.php
```

## Instalação

1. Clone o repositório:

   ```sh
   git clone https://github.com/pietrohoff/dorkdetect.git
   ```

2. Navegue até o diretório do projeto:

   ```sh
   cd dorkdetect
   ```

3. Instale as dependências do Composer:

   ```sh
   composer install
   ```

4. Gere o autoload do Composer:

   ```sh
   composer dump-autoload
   ```

## Uso

1. Execute o script principal:

   ```sh
   php main.php
   ```

2. Siga as instruções no terminal para digitar a string de pesquisa e escolher a categoria de dorks a ser utilizada.

## Exemplo de Uso

```sh
Digite a string de pesquisa: http://example.com
Categorias disponíveis:
1. category1
2. category2
3. category3
4. Todas as categorias
Escolha uma categoria (número): 1
```

## Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir uma issue ou enviar um pull request.

## Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para mais detalhes.
