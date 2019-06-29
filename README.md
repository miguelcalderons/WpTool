# WPTool

##Requirements

- PHP 5.6 or ^7.0.0
- WordPress ^5.0.0
- Composer
- NPM

##Instalation

###Configure locally PHPUnit

run this: `composer install` to install composer
after, run this to configure the phpunit ` bin/install-wp-tests.sh dbName user password localhost latest`

Where de *dbName* is the database name for testing, user and password of the database.

then run `phpunit` to run the test, careful about any errors, if it doesnt find the testing wordpress, verify the file inside of wordpress-tests-lib: wp-tests-config.php and change the ABSPATH with absolute path to your TEMP folder. For example:
define( 'ABSPATH', 'C:\Users{User}\AppData\Local\Temp/wordpress/' );


### Configure Sass

run `npm install`
try and run `npm run gulp watch`

For more further information go to: [https://gulpjs.com/](https://gulpjs.com/).


