## Instalasi


Persyaratan [Node.js](https://nodejs.org/) versi LTS.

Persyaratan [PHP] minimal versi 8.2.

Persyaratan [DATABASE] PostgreSQL.

Klon repositori ini.

```
git clone https://github.com/yuisa-scarlet/merchan-be.git
```

Masuk ke direktori.

```
cd merchan-be
```

Unduh pustaka yang diperlukan.

```
composer install
```

```
npm install
```

## Running project

Jalankan project

```
php artisan serve
```

```
php artisan migrate
```

Database seeder akan membuat data role dan user

Admin : admin@example.com / password

Admin : user@example.com / password
```
php artisan db:seed
```


```
npm run dev
```