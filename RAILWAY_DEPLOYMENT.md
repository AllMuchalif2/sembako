# Tutorial Hosting di Railway (Free Tier)

Railway adalah platform cloud yang menyediakan free tier dengan limit **$5 kredit per bulan** atau **500 jam eksekusi**. Tutorial ini akan memandu Anda untuk deploy aplikasi MyMart Sembako ke Railway.

---

## Prasyarat

-   [x] Akun GitHub dengan repository project ini
-   [x] Akun Railway (daftar di [railway.app](https://railway.app))
-   [x] Akun Midtrans (untuk payment gateway)
-   [x] Project sudah di-push ke GitHub

---

## Persiapan File

### 1. Buat File `Procfile`

Buat file `Procfile` di root project (tanpa ekstensi):

```
web: php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 2. Buat File `nixpacks.toml` (Opsional)

Untuk konfigurasi build yang lebih spesifik:

```toml
[phases.setup]
nixPkgs = ["nodejs", "php82", "php82Packages.composer"]

[phases.install]
cmds = [
    "composer install --optimize-autoloader --no-dev",
    "npm ci",
    "npm run build"
]

[start]
cmd = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
```

### 3. Update `.env.example`

Pastikan `.env.example` lengkap untuk referensi environment variables di Railway.

---

## Langkah Deployment

### Step 1: Setup Project di Railway

1. **Login ke Railway**

    - Buka [railway.app](https://railway.app)
    - Login dengan GitHub account

2. **Create New Project**

    - Klik **"New Project"**
    - Pilih **"Deploy from GitHub repo"**
    - Pilih repository `sembako`
    - Railway akan otomatis detect Dockerfile atau PHP project

3. **Add Database (MySQL)**
    - Di dashboard project, klik **"+ New"**
    - Pilih **"Database"** → **"Add MySQL"**
    - Railway akan provision MySQL database secara otomatis

---

### Step 2: Konfigurasi Environment Variables

Railway menyediakan environment variables otomatis untuk database. Anda perlu menambahkan variable Laravel lainnya.

1. **Klik service aplikasi Anda**
2. **Buka tab "Variables"**
3. **Klik "Raw Editor"** dan paste konfigurasi berikut:

```env
# App Configuration
APP_NAME="MyMart Sembako"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_NEW_KEY_HERE
APP_URL=https://your-app-name.up.railway.app

# Database (Railway auto-generated, jangan override!)
# DB_CONNECTION=mysql
# DB_HOST=${{MYSQL.MYSQL_HOST}}
# DB_PORT=${{MYSQL.MYSQL_PORT}}
# DB_DATABASE=${{MYSQL.MYSQL_DATABASE}}
# DB_USERNAME=${{MYSQL.MYSQL_USER}}
# DB_PASSWORD=${{MYSQL.MYSQL_PASSWORD}}

# Untuk custom, gunakan reference variables:
DATABASE_URL=${{MYSQL.DATABASE_URL}}

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Mail Configuration (opsional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

4. **Generate APP_KEY**

    - Jalankan di local: `php artisan key:generate --show`
    - Copy hasil dan paste ke `APP_KEY`

5. **Update APP_URL**
    - Setelah deploy, Railway akan memberikan URL
    - Format: `https://sembako-production.up.railway.app`

---

### Step 3: Konfigurasi Database Railway

Railway MySQL akan otomatis tersedia dengan variables:

-   `${{MYSQL.MYSQL_HOST}}`
-   `${{MYSQL.MYSQL_PORT}}`
-   `${{MYSQL.MYSQL_DATABASE}}`
-   `${{MYSQL.MYSQL_USER}}`
-   `${{MYSQL.MYSQL_PASSWORD}}`

**Setup Database Connection:**

Tambahkan di Variables (jika menggunakan Dockerfile):

```env
DB_CONNECTION=mysql
DB_HOST=${{MYSQL.MYSQL_PRIVATE_URL}}
DB_PORT=3306
DB_DATABASE=${{MYSQL.MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL.MYSQL_USER}}
DB_PASSWORD=${{MYSQL.MYSQL_PASSWORD}}
```

---

### Step 4: Deploy & Migration

1. **Trigger Deployment**

    - Railway akan otomatis deploy saat ada push ke GitHub
    - Atau klik **"Deploy"** manual di dashboard

2. **Monitor Build Logs**

    - Buka tab **"Deployments"**
    - Klik deployment terbaru
    - Lihat **"View Logs"** untuk monitor progress

3. **Run Database Migration**

    Setelah deployment sukses, buka **Railway CLI** atau gunakan **Command** di dashboard:

    ```bash
    php artisan migrate --force
    ```

    Atau tambahkan seeder:

    ```bash
    php artisan migrate:fresh --seed --force
    ```

---

### Step 5: Setup Custom Domain (Opsional)

1. **Di Railway Dashboard**

    - Pilih service aplikasi
    - Buka tab **"Settings"**
    - Scroll ke **"Domains"**
    - Klik **"Generate Domain"** untuk subdomain Railway gratis
    - Atau **"Custom Domain"** untuk domain sendiri

2. **Update APP_URL**
    - Setelah dapat domain, update variable `APP_URL`

---

## Troubleshooting

### 1. Build Failed - Composer Install Error

**Problem:** `composer install` gagal karena memory limit

**Solution:**

```bash
# Tambahkan variable di Railway
COMPOSER_MEMORY_LIMIT=-1
```

### 2. Migration Gagal

**Problem:** Database connection refused

**Solution:**

-   Pastikan MySQL service sudah running
-   Verify database variables reference syntax: `${{MYSQL.VARIABLE_NAME}}`
-   Cek di **"Connect"** tab MySQL service untuk connection string

### 3. Assets Not Loading (CSS/JS)

**Problem:** Vite build assets tidak muncul

**Solution:**

-   Pastikan `npm run build` berjalan di build phase
-   Tambahkan build command di Procfile atau nixpacks.toml
-   Verify `public` folder ter-generate setelah build

```bash
# Di nixpacks.toml, pastikan ada:
[phases.install]
cmds = [
    "composer install --no-dev --optimize-autoloader",
    "npm ci",
    "npm run build"
]
```

### 4. Storage Permission Error

**Problem:** Laravel tidak bisa write ke `storage/`

**Solution:**

Buat file `railway.json`:

```json
{
    "build": {
        "builder": "nixpacks"
    },
    "deploy": {
        "startCommand": "chmod -R 775 storage bootstrap/cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
    }
}
```

### 5. Midtrans Callback Tidak Jalan

**Problem:** Midtrans callback URL tidak bisa diakses

**Solution:**

-   Pastikan route `/midtrans/callback` tidak memerlukan CSRF token
-   Update **Notification URL** di Midtrans Dashboard:
    ```
    https://your-app.up.railway.app/midtrans/callback
    ```
-   Verify `APP_URL` sudah benar di environment variables

---

## Optimasi Free Tier

Railway free tier memberikan **$5/bulan** atau **~500 jam**. Tips menghemat:

### 1. Sleep Inactive Services

Jika aplikasi tidak digunakan 24/7:

-   Railway otomatis sleep setelah tidak ada traffic
-   Auto-wake saat ada request (cold start ~10-30 detik)

### 2. Minimize Build Time

```toml
# nixpacks.toml
[phases.install]
cmds = [
    "composer install --no-dev --optimize-autoloader --no-progress --no-interaction",
    "npm ci --prefer-offline --no-audit"
]
```

### 3. Gunakan Cache

```env
# Di Railway Variables
CACHE_DRIVER=file
SESSION_DRIVER=file
```

Untuk production lebih baik, upgrade ke Railway Pro dan gunakan Redis.

### 4. Disable Unused Services

-   Jangan aktifkan queue worker jika tidak digunakan
-   Disable logging verbose: `LOG_LEVEL=error`

---

## Railway CLI (Opsional)

Install Railway CLI untuk development lokal:

```bash
# Install CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# Run migration dari local
railway run php artisan migrate

# View logs
railway logs

# Open shell
railway shell
```

---

## Monitoring

### View Application Logs

1. Dashboard → Service → **"Deployments"**
2. Klik deployment aktif
3. **"View Logs"** untuk real-time logs

### Check Resource Usage

1. Dashboard → Project Settings → **"Usage"**
2. Monitor:
    - Execution hours
    - Credit usage
    - Network bandwidth

---

## Update & Redeploy

### Auto Deploy (Default)

Railway otomatis deploy saat ada push ke branch yang di-watch (biasanya `main`).

### Manual Deploy

1. Dashboard → Service → **"Deployments"**
2. Klik **"Deploy"** atau **"Redeploy"**

### Rollback

1. Dashboard → Service → **"Deployments"**
2. Klik deployment sebelumnya
3. **"Redeploy"**

---

## Checklist Post-Deployment

-   [ ] Aplikasi bisa diakses via Railway URL
-   [ ] Database migration berhasil
-   [ ] Seeder terjalankan (untuk data awal)
-   [ ] Login admin berfungsi
-   [ ] Upload gambar produk berfungsi (test storage)
-   [ ] Checkout Midtrans berfungsi
-   [ ] Callback Midtrans terima notifikasi
-   [ ] Email notification terkirim (jika digunakan)
-   [ ] Map Leaflet.js muncul di checkout
-   [ ] Mobile responsive berfungsi

---

## Link Berguna

-   **Railway Dashboard:** [https://railway.app/dashboard](https://railway.app/dashboard)
-   **Railway Docs:** [https://docs.railway.app](https://docs.railway.app)
-   **Railway Templates:** [https://railway.app/templates](https://railway.app/templates)
-   **Midtrans Dashboard:** [https://dashboard.midtrans.com](https://dashboard.midtrans.com)

---

## Estimasi Biaya Free Tier

| Resource       | Limit Free Tier | Catatan                          |
| -------------- | --------------- | -------------------------------- |
| Credit         | $5/bulan        | Reset setiap bulan               |
| Execution Time | ~500 jam        | 24/7 = ~720 jam (butuh optimasi) |
| Database       | 100MB - 1GB     | MySQL shared resource            |
| Bandwidth      | Unlimited       | Tidak dibatasi di free tier      |
| Build Time     | Unlimited       | Tidak dihitung dari credit       |

**Tips:** Jika aplikasi hanya demo/portfolio, Railway free tier cukup. Untuk production, pertimbangkan upgrade Railway Pro ($5-20/bulan).

---

## Alternatif Deployment Gratis

Jika Railway credit habis, alternatif lain:

1. **Render.com** - Free tier 750 jam/bulan
2. **Fly.io** - Free tier dengan limit resource
3. **Heroku** - Tidak gratis lagi (butuh paid plan)
4. **DigitalOcean App Platform** - $5/bulan (bukan gratis)

Railway tetap recommended untuk Laravel + MySQL stack.
