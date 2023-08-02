# Programação Assíncrona em PHP

## 1. Introdução

O projeto contém **dois scripts** utilizados como exemplo na palestra **Programação Assíncrona em PHP**, para exemplificar as vantagens da **programação assíncrona** em relação a programação síncrona no contexto de um web scraper (ferramenta utilizada para coletar dados de sites de forma automatizada).

Neste exemplo, implementei um web scraper que visita a página dos **250 melhores filmes do IMDB** (https://www.imdb.com/chart/top/?ref_=nv_mv_250), obtém os links dos filmes, acessa cada um deles e extrai o **título**, **descrição** e **estrelas**.

O primeiro exemplo é implementado utilizando a extensão cURL e as requisições são feitas de *forma sequencial* exemplificando a **abordagem síncrona** em que deve-se esperar uma instrução terminar para executar a próxima instrução.

O segundo exemplo é implementado utilizando a biblioteca [ReactPHP](http://reactphp.org) e as requisições são feitas de *forma concorrente* exemplificando a **abordagem assíncrona** em que não é necessário esperar uma instrução terminar para executar a próxima instrução. Isso é possível pois **operações de I/O** (que são custosas por natureza) são enfileiradas e, após terminarem, disparam um evento com o resultado da operação para que a execução seja concluída.

Neste exemplo, a **abordagem assíncrona** possui um desempenho muito superior em relação a abordagem síncrona.

## 2. Instalação

1. Instalar as dependências do projeto
```
$ composer install
```

2. Executar o script síncrono *(utilize `time` para obter o tempo de execução)*
```
$ php scraper-sync.php
$ time php scraper-sync.php
```

3. Executar o script assíncrono *(utilize `time` para obter o tempo de execução)*
```
$ php scraper-async.php
$ time php scraper-async.php
```
