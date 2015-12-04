# Simple Tokonlen

Just another simple yet modular PHP Application :grin:

## Requirements

- PHP 5.4 or newer.
- HTTP Server, e.g. NginX or Apache either.
- MySQL Server 5.x or newer for main database.

## Install

Simply clone this repo to your local directory then `cd` into it.

```bash
$ git clone git@github.com/feryardiant/tokonlen --depth 1 my-app
$ cd my-app
```
**Note**: use `--depth 1` option to clone only one last commit history.

then you need to create new database, you could do it from PHPMyAdmin or simply run.

```bash
$ mysql -uroot -p create database [db-name]
```

at last but not least, run the installer.

```bash
$ php system/install [baseurl] [db-user]:[db-pass]@[db-host]/[db-name]
```

The installer will:

- Import `system/database.sql` to your [db-name]
- Create & save your installation config to `system/config.php`
- Create `.htaccess` file based on your [baseurl] installation config.

everything is done, now you should open the [baseurl] from your favorite web browser.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
