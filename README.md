<h1 align="center">Laravel Vue Admin Panel</h1>
<h3 align="center">A Single page Vue admin panel for Laravel projects.</h3>
<p align="center">
<a href="https://packagist.org/packages/balajidharma/laravel-vue-admin-panel"><img src="https://poser.pugx.org/balajidharma/laravel-vue-admin-panel/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-vue-admin-panel"><img src="https://poser.pugx.org/balajidharma/laravel-vue-admin-panel/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-vue-admin-panel"><img src="https://poser.pugx.org/balajidharma/laravel-vue-admin-panel/license" alt="License"></a>
</p>

## Built with
- [Laravel 9](https://github.com/laravel/framework)
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- [Laravel Breeze](https://github.com/laravel/breeze)
- [balajidharma/laravel-menu](https://github.com/balajidharma/laravel-menu)
- [Vue 3](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Inertiajs](https://inertiajs.com/)
- [Admin One - Admin Dashboard](https://github.com/justboil/admin-one-vue-tailwind)

## Installation

- To get started, you need to install [Docker Desktop](https://www.docker.com/products/docker-desktop).
- You may run the following command in your terminal
- Windows open WSL2 Linux terminal. [Docker Desktop WSL 2 backend](https://docs.docker.com/desktop/windows/wsl/)
- `./vendor/bin/sail up`
- `./vendor/bin/sail npm install`
- `./vendor/bin/sail npm run dev`
- `./vendor/bin/sail artisan migrate --seed --seeder=AdminCoreSeeder`
- Now open http://localhost/

###### Super Admin Login
- Email - superadmin@example.com
- Password - password

#### Admin Configuration:

To change the Admin Prefix, change `prefix` on `config/admin.php` or add the `ADMIN_PREFIX` on env 

```php
'prefix' => env('ADMIN_PREFIX', 'admin'),
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
