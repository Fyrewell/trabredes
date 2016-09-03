
Trabalho de Redes e Sistemas de Comunicações Móveis
============================


Instalação
------------
    Necessita de Apache e PHP instalado.
    git clone https://github.com/Fyrewell/trabredes
    cd trabredes
    composer install

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:load
    php bin/console fixture:load

Infos
----

Baseado no modelo Silex - Kitchen Sink Edition
https://github.com/lyrixx/Silex-Kitchen-Edition

Utiliza Silex Framework.
Banco de dados sqlite.
