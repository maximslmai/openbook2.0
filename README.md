Welcome to the openbook2.0 wiki!

# Welcome

Openbook2.0 is a very interactive and responsive invoice and inventory management system built using PHP and Yii. 
* Yii: http://www.yiiframework.com/
* Twitter Bootstrap: http://twitter.github.com/bootstrap/
* LAMP: http://www.howtoforge.com/ubuntu_lamp_for_newbies

## Demo: 
http://www.turtleland.net/openbook2.0

## Installation Instruction
* Download the source from github (https://github.com/maximmai/openbook2.0/archive/master.zip)
* Unzip it to your apache's WWW, i.e. **/var/www/openbook2.0**
* Create an "assets" directory: **/var/www/openbook2.0/assets** (optional)
* Add write permission to these directories: **/var/www/openbook2.0/assets** and **/var/www/openbook2.0/protected/runtime**

## Database Configuration
* Create a database in MySQL named "**openbook**"
* Create a database user for "openbook". (optional, you could config the database connection information in openbook2.0/protected/config/main.php). You could use this command to create an user:
`mysql -u root -p openbook -e "GRANT ALL ON openbook.* TO openbook@localhost IDENTIFIED BY '0penb00k'"`
* Import the database schema from **/var/www/openbook2.0/protected/data/openbook.sql**. You could use this command to import: 
`mysql -u openbook -p'0penb00k' openbook < /var/www/openbook2.0/protected/data/openbook.sql`
* Import sample data (optional). You could use this command to import: 
`mysql -u openbook -p'0penb00k' openbook < /var/www/openbook2.0/protected/tests/sample_test_data.sql`

## Developers:
* Maxim Mai <maxim.sl.mai@gmail.com>
* Eric Zhang <zhangtao727@gmail.com>