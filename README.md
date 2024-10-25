# Base Filament
## Introduction 👋
Base ini berisi filament yang bisa di gunakan ulang untuk base project-project yang membutuhkan filament,
di dalam sudah di sediakan `sample crud` dan `acl role base`

## Version 🔗
Berikut version yang sudah kita naikkan dari versi aslinya.

| Name     | Version  |
|----------|----------|
| Laravel  | 11       |
| PHP      | 8.2      |
| Filament | 3.2      |

## Running Development
1. buat file `.env` bisa copy dari `.env.sample`
2. Untuk running development bisa gunakan `docker-compose-dev.yml`
3. Menggunakan docker compose, gunakan perintah:
```
docker compose -f docker-compose.dev.yml up -d
------------- or -------------
docker-compose -f docker-compose.dev.yml up -d
```
4. Setelah running lakukan migrate di dalam container filament-template / app nya
```
php artisan migrate
```
5. Lakukan Seeder
```
php artisan db:seed
```
6. Generate Permission
```
php artisan shield:generate --all
```
7. Set Super Admin
```
php artisan shield:super-admin --user=1
```
8. Login menggunakan 
```bash
email : test@example.com
pass : password
```

## Running Production
1. Bisa gunakan `docker-compose-dev.yml` sebagai referensi
2. Jika ada settingan-settingan tambahan untuk production, kami serahkan ke team infra tercinta ✌️🙏

