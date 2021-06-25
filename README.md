# patriciaInternTest

a restful API written with Laravel framework and API authorization and authentication using Sanctum 

## Installation

use the [Git](https://github.com/yakubu234/patriciaInternTest.git) repository link to install patriciaInternTest

```bash
git clone https://github.com/yakubu234/patriciaInternTest.git
```

run 
```bash
composer update 
```
[and]

```bash
php artisan migrate
```

## feature test
user registration test at tests/Feature/feature/UserTest

## usage

kindly click on this [link](https://documenter.getpostman.com/view/12538701/TzY68ZWY) to view the documentation published on postman

[OR]

## API Endpoints

### Register User

```bash
 POST      api/register
 ```
registers the user and returns json data with the status.

### Login User

```bash
POST       api/login
```
authenticates user and returns json data with status.

### Fetch User

```bash
GET        api/fetch-user/{user_id}
```
required bearer token to access, pass user id along with the request. returns json data with status.

### Update User

```bash
PUT        api/update-user
```
required bearer token to update, pass raw data through body. returns json data with status.

### Delete User

```bash
DELETE     api/delete-user/{user_id}
```
required bearer token to access, pass user id along with the request. returns json data with status.



