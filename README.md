# Petitions.io API PHP Wrapper
This is PHP wrapper to Petitions.io API

## Contents
- Installation
- Pre-requisites
- Usage
  - Generating login URL
  - Acquring Access Token
  - Making API calls
  
## Installation
Installation is done using composer. Run the following command to include this package into your project  
`composer require cloudwelder/petitions-api`

## Pre-requisites
In order to use this package, you must have an active App at petitions.io. If you dont already have one, register for one at petitions.io
After the registration, note down your `client_id`, `client_secret` and `redirect_uri` fields

## Usage
Make sure you have the following line in your code before using any of the API classes. Thi is **not** required if you are using a framework such as laravel.
```php
require "vendor/autoload.php"
```
### Initialize the API
To use any of the APi calls, you must first get an instance of the API like below
```php
use CloudWelder/PetitionsApi/PetitionsApi;

$api = new PetitionsApi('client_id', 'client_secret', 'reidrect_uri');
```
Substitute `client_id`, `client_secret`, `redirect_uri` with your own values.

### Generating login URL
To gain access rights from a user, you must first redirect him to petitions.io. You can use the `getRedirectUrl()` method to generate this url.
```php
$api
