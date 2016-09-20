
Trabalho de Redes e Sistemas de Comunicações Móveis
============================


Instalação (simplificada)
------------
    Necessita de Apache2.4, PHP5.6, sqlite e composer instalados.
    git clone https://github.com/Fyrewell/trabredes
    cd trabredes
    composer install

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:load

Passo a passo executado para instalação no embarcado pcduinov2 (lubuntu 12.04)
------------
    Instalar o editor de texto nano
    sudo apt-get install nano

    Adicionar a package do php5.6 e apache2.4
    sudo nano /etc/apt/sources.list
    Referências:
    https://launchpad.net/~ondrej/+archive/ubuntu/php
    https://launchpad.net/~ondrej/+archive/ubuntu/apache2
    No final do arquivo basta adicionar as linhas:
    deb http://ppa.launchpad.net/ondrej/php/ubuntu precise main
    deb-src http://ppa.launchpad.net/ondrej/php/ubuntu precise main
    deb http://ppa.launchpad.net/ondrej/apache2/ubuntu precise main
    deb-src http://ppa.launchpad.net/ondrej/apache2/ubuntu precise main

    Atualizar packages
    sudo apt-get update

    Instalar php, apache e sqlite
    sudo apt-get install apache2
    sudo apt-get install php5.6
    sudo apt-get install libapache2-mod-php5.6
    sudo apt-get install php5.6-sqlite

    Dar permissoes para o user do apache e de acesso a pastas
    sudo chown -R www-data /var/www
    sudo chmod -R 755 /var/www

    Baixar projeto
    cd /var/www
    sudo wget "https://github.com/Fyrewell/trabredes/archive/master.zip"
    sudo unzip master.zip
    sudo mv trabredes-master trabredes

    Instalar dependencias do projeto (vendors do composer)
    na pasta do projeto executar
    sudo wget "https://getcomposer.org/download/1.2.1/composer.phar"
    sudo php composer.phar install

    Ativar o apache mod rewrite para ler do .htaccess
    sudo a2enmod rewrite

    No arquivo de configuração do apache configurar para ler .htaccess
    sudo nano /etc/apache2/apache2.conf
    em <Directory /> e <Directory /var/www/html/> editar
    AllowOverwrite de None para All

    Restart do apache para aplicar alterações
    sudo service apache2 restart

    Criar esquema da base de dados
    em /var/www/html/trabredes
    sudo php bin/console doctrine:database:create
    sudo php bin/console doctrine:schema:load

    E voy là!

Infos
----

Baseado no modelo Silex - Kitchen Sink Edition
https://github.com/lyrixx/Silex-Kitchen-Edition

Utiliza Silex Framework.
Banco de dados sqlite.
