star_wars_api
=============

A Symfony project to for Star wars API.

Following setup is for Ubuntu environment. 

Project setup
=============
1. Clone the repository
2. Run php composer.phar install to install packages
3. Give nessary foler permissions for var folder inside the directory. 
    sudo chown -R ${USER}:www-data var
    sudo chmod -R 7555 var/
4. Create a virtual host for your project folder
    This is an example 

    
    <VirtualHost *:80>
        ServerName local.starwars.api

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/star-wars-api/web

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
    </VirtualHost>

5. Add an entry in the /etc/hosts file to use above server name
    
    127.0.0.1 local.starwars.api

