![Master workflow](https://github.com/ava239/php-project-lvl3/workflows/Master%20workflow/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/ac9843b4a5fd30b00ff1/maintainability)](https://codeclimate.com/github/ava239/page-analyzer/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ac9843b4a5fd30b00ff1/test_coverage)](https://codeclimate.com/github/ava239/page-analyzer/test_coverage)

App on heroku  
https://ava-page-analyzer.herokuapp.com/

# Description
SEO page analyzer. Made on Laravel.  
Check domain availability, track SEO values (H1, Keywords, Description) history  
Ready to deploy on Heroku

# Requirements
- PHP 7.4
- Extensions:
    * sqlite3
    * zip
    * pgsql
    * dom
    * fileinfo
    * filter
    * iconv
    * json
    * libxml
    * mbstring
    * openssl
    * pcre
    * PDO
    * Phar
    * SimpleXML
    * tokenizer
    * xml
    * xmlwriter
- Composer
    
# Setup
Server will be available at http://localhost:8000/ (same if you choose to run it in Docker)
### Local SQLite
``` sh
$ make setup
$ make start
```
### Local PostgreSQL
- First you have to run same steps as for local SQLite setup.
- Then you have to create database manually.  
- Edit *.env*: add your database host, database name, username and password
- Run migrations
``` sh
$ php artisan migrate
$ php artisan db:seed
```

### Docker with PostgreSQL
``` sh
$ make docker-setup
$ make compose-up
```
