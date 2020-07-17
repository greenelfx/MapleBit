<p align="center">
<img src="https://travis-ci.com/greenelfx/MapleBit.svg?branch=v2" alt="Build Status">
<a href="https://codecov.io/gh/greenelfx/MapleBit">
<img src="https://codecov.io/gh/greenelfx/MapleBit/branch/v2/graph/badge.svg" />
</a>
<img src="https://github.styleci.io/repos/12365782/shield?branch=v2"/>
</p>

----------------------------

## About MapleBit

MapleBit is a CMS built with [Laravel](http://laravel.com/) and [React](https://reactjs.org/). It is currently under active development, and is not suitable for general consumption yet. In the v2 iteration, we seek to maintain feature parity with MapleBit v1, with the secondary goal of retaining visual parity with v1.

MapleBit v2 exposes an underlying API server with [Swagger Specs](https://swagger.io/resources/open-api/), making it easy for anyone to build frontend UIs on top of MapleBit v2. By default, MapleBit v2 ships with a feature-complete React frontend, though you are free to replace or rewrite this in your own deployment.

## Server Requirements
Since MapleBit is built with Laravel, our requirements are the same as the framework itself:
- PHP >= 7.2.5
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation
The web installer is not completed yet, so manual installation is required:
1. MapleBit utilizes [Composer](https://getcomposer.org/) to manage its dependencies. So, before using MapleBit, make sure you have Composer installed on your machine.
1. Clone the repository to your desired server, and switch to the v2 branch with `git checkout v2`
1. Copy `.env.example` into `.env`, and fill the following information:
    - Database connection details - Note that MapleBit is database agnostic, but you are likely using the `mysql` driver.
    - `API_BASE_PATH` and `MIX_API_BASE_PATH` - For example, `localhost/maplebit/api` (`/api` is what's important here, everything else is dependent on your deployment)
    - Finally, run `php artisan key:generate` in your console.
1. Run `composer install` in the cloned directory. This will automatically run database migrations and regenerate the Swagger Spec.
1. The default frontend that ships with MapleBit uses React. If you intend to use this frontend, you'll need to setup the frontend:
    1. [Install Node >=12.x](https://nodejs.org/).
    1. Run `npm install` in the root directory of MapleBit.
    1. Run `npm run production` to build production assets.
    1. Reload your root URL. The frontend should work now.

## Contributing
Pull requests are welcome!

## License

MapleBit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
