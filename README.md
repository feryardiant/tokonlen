# Simple Tokonlen

Just another simple yet modular PHP Application :grin:

## Requirements

- PHP 5.4 or newer.
- HTTP Server, e.g. NginX or Apache either.
- MySQL Server 5.x or newer for main database.

## Install

Simply create new composer project.

```bash
$ composer create-project feryardiant/tokonlen my-app
$ cd my-app
```

then you need to create new database, you could do it from PHPMyAdmin or simply run.

```bash
$ mysql -uroot -p create database [db-name]
```

at last but not least, run the installer.

```bash
$ php system/install [baseurl] [db-user]:[db-pass]@[db-host]/[db-name]
```

The installer will:

- Import `system/database.sql` to your `[db-name]`
- Create `system/config.php` & save your installation config.
- Create `.htaccess` file based on your `[baseurl]` installation config.

everything is done, now you should open the `[baseurl]` from your favorite web browser.

## Usage & Demo

Live demo is available here: [https://tokonlen.herokuapp.com/](https://tokonlen.herokuapp.com/)

Use these credential to login into it

* **Admin**

  _Username_: admin & _Password_: 1234

* **Customer**

  _Username_: pelanggan & _Password_: 1234

everything is done, now you should open the `[baseurl]` from your favorite web browser.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
