# API REST - Gerenciamento de Informações do Usuário

## Configuração

Para a configuração da API, utilizamos o Linux Ubuntu 18.04 e algumas ferramentas como:

- [Composer](https://getcomposer.org/download/) 
- [Slim Framework](http://www.slimframework.com/docs/v3/start/installation.html)
- [Doctrine](https://www.doctrine-project.org/projects/orm.html)
- [PSR7-Middlewares](https://github.com/oscarotero/psr7-middlewares)
- [Monolog](https://packagist.org/packages/monolog/monolog)
- [PHPUnit](https://phpunit.de/getting-started/phpunit-8.html)

Primeiramente instalamos o Composer que é uma ferramenta de gerenciamento de dependências para o PHP, usando o comando:

```bash
$ sudo apt update

$ sudo apt install curl php-cli php-mbstring git unzip

$ cd ~

$ curl -sS https://getcomposer.org/installer | php

```
O que fizemos aqui foi atualizar o cache de gerenciador de pacotes e logo em seguida instalar o  `curl` para instalação do  `composer` e o  `php-cli` para instalação e execução dele.
Devemos acessar o diretório home para a instalação do  `composer` utilizando o  `curl`.

```bash
$ chmod +x composer.phar

$ mv composer.phar /usr/local/bin/composer

$ composer self-update

```
Precisamos tornar o composer executável utilizando  `chmod` e após isso movê-lo para a pasta de binários, e por fim atualizando seu binário para a última versão.

Feito isso, vamos instalar o Slim Framework que é um microframework PHP para construções de API.

```bash
$ composer require slim/slim nomeprojeto

```
Após este comando, um arquivo `composer.json` é criado com as configurações do slim.

E vamos utilizar a biblioteca ORM (Object Relational Mapping) do Doctrine que é um design pattern para se trabalhar com bancos de dados relacionais, instalando-o com o seguinte comando no terminal:

```bash
$ composer require doctrine/orm

```
Sendo após isso adicionado ao corpo do require do `composer.json`.

Utilizaremos também o PSR7-Middlewares para tratar as requisições ao fim de que um acesso assim:

```bash
http://localhost/users/

```

Tenha o mesmo efeito que esse:

```bash
http://localhost/users

```

Tratando assim que as requisições da nossa aplicação não gere implementações defeituosas.
Vamos instalar com esse comando:

```bash
$ composer require oscarotero/psr7-middlewares

```

Vamos também gerar serviços de logs com o `monolog`, apenas afim de manter níveis de registros. Vamos instalar com esse comando:

```bash
$ composer require monolog/monolog

```

E por fim, vamos instalar o PHPUnit para testes de unidade que serão melhor descritos depois.

```bash
$ sudo wget https://phar.phpunit.de/phpunit.phar

$ chmod +x phpunit.phar

$ sudo mv phpunit.phar /usr/local/bin/phpunit

$ phpunit --version

```
Instalamos o PHPUnit e após isso o tornamos executável e o movemos para a pasta de binários locais. Após os procedimentos, verificamos se tudo foi instalado corretamente e qual a versão dele. 
E um detalhe importante: o PHPUnit tem que ser utilizado como `require-dev` dentro do `composer.jon`, pois todos os testes devem ser feitos em ambiente de desenvolvimento.

O melhor a se fazer para configuração de servidor é a instalação do [Docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/) e até mesmo o [Laradock](https://laradock.io/) que facilita a criação dos containers para toda a infraestrutura dos projetos.
Nesse primeiro momento, utilizarei o servidor interno do PHP com o seguinte comando:

```bash
$ php -S localhost:8000

```

## Execução

Poderiamos utilizar excelentes ferramentas para execuções de requisições de API como o [Postman](https://www.getpostman.com/downloads/) ou o [Insomnia](https://insomnia.rest/download/), mas faremos todas as execuções por linha de comando no terminal.

Mas primeiro devemos criar nosso Banco de Dados de usuários utilizando o `doctrine` para persistência de dados, utilizando o comando:

```bash
$ vendor/bin/doctrine orm:schema-tool:create

$ vendor/bin/doctrine orm:schema-tool:update --force

```
Nosso schema de dados sendo criado podemos começar a adicionar usuários para a execução das requisições com qualquer gerenciador de banco de dados. Eu particularmente utilizo o [Valentina](https://www.valentina-db.com/en/all-downloads/current) e recomendo pela facilidade em manusear os bancos.

Agora finalmente podemos testar as requisições.

**POST**

```bash
$ curl -X POST http://localhost:8000/users -H "Content-type: application/json" -d '{"name":"Patricia Santos"}'

```

**GET**

```bash
$ curl -X GET http://localhost:8000/users

$ curl -X GET http://localhost:8000/users/1

```

**PUT**

```bash
$ curl -X PUT http://localhost:8000/users/1 -H "Content-type: apllication/json" -d '{"name":"Carlos Eduardo"}'

```

**DELETE**

```bash
$ curl -X DELETE http://localhost:8000/users/1

```

As 4 requests de GET, POST, PUT e DELETE são assim executadas e retornadas em formato json.

## Testes

TDD (Test Driven Development) basicamente é uma técnica de desenvolvimento que consiste em um ciclo RGB, conforme figura abaixo:

[![TDD ](https://cdn-images-1.medium.com/max/800/1*Mjb3IFooRmFumA2IgNEWbw.png)]

**Red**: Primeiramente seu teste tem que ser escrito para falhar.

**Green**: Faça com que o seu teste passe.

**Blue**: Pense em uma forma de refatorar seu código atendendo o mínimo de boas práticas da linguagem na qual o código foi escrito para que ele seja limpo e testável.

Vamos utilizar o seguinte comando para realização dos testes:

```bash
$ composer run test

```

**Cobertura de Código com PHPUnit**

Existe essa feature do PHPUnit que nos permite ter uma visualização gráfica do quanto nosso código foi testado, a porcentagem de cobertura dos testes e outras informações como:

- Complexidade Ciclomática
- Distribuição de Cobertura
- Complexidade do Método

Então vamos utilizar esse recurso nos testes que fizemos até o momento. 
Nosso arquivo phpunit.xml tem que ficar assim:

```bash
<?xml version="1.0" encoding="UTF-8" ?>

<phpunit bootstrap="vendor/autoload.php" colors="true" verbose="true" stopOnFailue="true">
    <testsuites>
        <testsuite name="ingresse">
            <directory suffix=".php">tests/</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist>
            <directory suffix=".php">src/</directory>
        </whitelist>
    </filter>
    <logging>
        <log type="tap" target="tests/build/report.tap"/>
        <log type="junit" target="tests/build/report.junit.xml"/>
        <log type="coverage-html" target="tests/build/coverage" charset="UTF-8" yui="true" highlight="true"/>
        <log type="coverage-text" target="tests/build/coverage.txt"/>
        <log type="coverage-clover" target="tests/build/logs/clover.xml"/>
    </logging>
</phpunit>

```
Para gerarmos o relatório devemos ter o Xdebug habilitado, assim:

```bash
$ sudo phpenmod xdebug

```

Rodamos novamente os testes com o comando: 

```bash
$ composer run test

```

Após a execução do comando, será criando dentro da pasta tests/ a pasta build/ com todos os arquivo que dizem respeito a cobertura do seu código. Depois disso podemos verificar o resultado gerado pelos nossos testes entrando dentro da pasta tests/build/coverage/index.html.






