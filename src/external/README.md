# External side of data diode

## Configuration
Internal side IP, port, admins and external users can be changed into `gen_config.php`. To regenerate `config.json`, run :
```bash
$ make config
```

## Test
To test your application server, you can run the PHP web server :
```bash
$ make run
```
This will launch web server on `0.0.0.0:8888`. IP and port can be changed in `Makefile`. You can make test requests using [RESTer browser extension](https://github.com/frigus02/RESTer).

## Installation
To install the server in production, copy `index.php`, `config.php` and `config.json` files to your web server root directory. If you want to manage users from a web interface, you can also copy `admin.php`.

## Usage
### Authentication
HTTP Basic authentification is used within header `Authorization`.
See http://blog.restcase.com/restful-api-authentication-basics/#basicauthentication for more informations.

### Data
JSON encoded data MUST be sent within HTTP request body using POST method.

### Error
For each request, a JSON encoded response is returned with two parameters :
- `success` boolean, success of request ;
- `message` string, human description of what happened.

In addition, HTTP response code is set according to success status.
If success is `true`, HTTP code `202 Accepted` is used.
If success is `false`, HTTP code `400 Bad Request` is used.
