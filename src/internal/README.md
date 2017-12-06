# Internal side of data diode

## Configuration
Internal side IP, port, buffer length and database credentials can be changed into `gen_config.php`. To regenerate `config.json`, run :
```bash
$ make config
```
To create tables within database, run :
```bash
$ make tables
```

## Test
To test UDP server listening from external packets, run :
```bash
$ make run
```
Then you can make requests from external side to see if data is received.

## Installation
To install the server in production, copy `server.php`, `config.php`, `database.php`, `config.json` and `Makefile`
files to your web server root directory and run :
```bash
$ make run
```

## Usage
TODO: view data through web interface.
