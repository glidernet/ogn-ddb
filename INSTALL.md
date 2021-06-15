## Development Setup
1. Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](https://www.vagrantup.com/)

2. Clone and run [Scotch Box](https://box.scotch.io/), a full-featured development environment for php
   ```
   git clone https://github.com/scotch-io/scotch-box.git ogn-ddb
   cd ogn-ddb
   vagrant up
   ```

3. Clone ogn-ddb repository into the webroot of the Scotch Box
   ```
   rm ./public/index.php
   git clone https://github.com/glidernet/ogn-ddb public
   vagrant ssh
   # The following commands get executed in the vm
   cd /var/www/public
   composer update
   cp sql.php.dist sql.php
   mysql --database scotchbox < database_schema.sql
   ```

4. Access your local ogn-ddb instance at [192.168.33.10](http://192.168.33.10)

5. (optional, for email debugging) Run [MailCatcher](https://mailcatcher.me/), accessible at [192.168.33.10](http://192.168.33.10:1080)
   ```
   vagrant@scotchbox:~$ mailcatcher --http-ip=0.0.0.0
   ```
