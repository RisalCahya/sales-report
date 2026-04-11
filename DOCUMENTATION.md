# Sistem Laporan Kunjungan Sales - Dokumentasi Lengkap

## 📱 Deskripsi Aplikasi

**Sistem Laporan Kunjungan Sales** adalah aplikasi web modern yang memungkinkan tim sales untuk membuat dan mengelola laporan kunjungan harian dengan fitur-fitur canggih seperti pengambilan foto dari kamera, lokasi GPS, dan dashboard analytics.

## ✨ Fitur Utama

### 1. **Autentikasi & Otorisasi**

- Login/Logout dengan Laravel Breeze
- Two user roles: Admin dan Sales
- Protected routes dengan middleware
- Session management

### 2. **Dashboard**

- Dashboard yang berbeda untuk Admin dan Sales
- Statistik real-time laporan harian
- Quick access buttons
- Recent reports widget

### 3. **Manajemen Laporan**

#### Untuk Sales:

- ✅ Buat laporan harian (1 laporan per hari per user)
- ✅ Tambah multiple kunjungan dalam satu laporan
- ✅ Lihat daftar laporan pribadi dengan filter tanggal
- ✅ Lihat detail laporan beserta foto bukti

#### Untuk Admin:

- ✅ Lihat semua laporan dari semua sales
- ✅ Filter laporan berdasarkan:
    - Tanggal (dari/sampai)
    - Sales person
- ✅ Lihat detail kunjungan dari setiap laporan

### 4. **Form Kunjungan (Mobile-First)**

- **Outlet**: Nama toko/outlet (required)
- **Alamat**: Alamat lengkap (required)
- **PIC**: Nama person in charge (required)
- **Keterangan**: Catatan tambahan (optional)
- **Foto**: Ambil dari kamera dengan timestamp otomatis
- **GPS**: Lokasi geografis otomatis

### 5. **Teknologi Kamera**

- getUserMedia API untuk akses kamera
- Canvas untuk processing foto
- Timestamp visual di setiap foto
- Preview foto sebelum submit
- Validasi foto wajib diambil

### 6. **Fitur GPS**

- navigator.geolocation API
- Precision: 5 meter (8 desimal)
- Link ke Google Maps dari detail
- Koordinat disimpan otomatis

### 7. **UI/UX**

- Responsive design (mobile-first)
- Tailwind CSS styling
- Card-based layout
- Touch-friendly buttons
- Clean dan modern design
- Loading indicators

## 🗄️ Struktur Database

### Tabel: users

```sql
- id (bigint)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- role (enum: 'admin', 'sales')
- remember_token (string, nullable)
- created_at, updated_at (timestamps)
```

### Tabel: reports

```sql
- id (bigint)
- user_id (bigint, FK -> users)
- tanggal (date) - unique per user per day
- created_at, updated_at (timestamps)
- Indexes: user_id, tanggal
```

### Tabel: report_details

```sql
- id (bigint)
- report_id (bigint, FK -> reports)
- outlet (string)
- alamat (text)
- pic (string)
- keterangan (text, nullable)
- foto_path (string, nullable)
- latitude (decimal 10,8, nullable)
- longitude (decimal 11,8, nullable)
- created_at, updated_at (timestamps)
- Indexes: report_id
```

## 🛣️ Routes

```
GET/HEAD        /                              (welcome)
POSTlogin       /login                         (login.store)
GET             /login                         (login.create)
POST            /logout                        (logout)
POST            /register                      (register.store)
GET             /register                      (register.create)
GET             /forgot-password               (password.request)
POST            /forgot-password               (password.email)
GET             /reset-password/{token}        (password.reset)
POST            /reset-password                (password.update)
GET             /verify-email                  (verification.notice)
POST            /verify-email/resend           (verification.send)
GET             /verify-email/{id}/{hash}      (verification.verify)
POST            /dashboard                    Protected
GET             /dashboard                     (dashboard) - Protected
GET|HEAD        /profile                       (profile.edit) - Protected
PATCH           /profile                       (profile.update) - Protected
DELETE          /profile                       (profile.destroy) - Protected
GET|HEAD        /reports                       (reports.index) - Protected
POST            /reports                       (reports.store) - Protected
GET             /reports/create                (reports.create) - Protected
GET|HEAD        /reports/{report}              (reports.show) - Protected
GET             /reports/{report}/edit         (reports.edit) - Protected
PUT|PATCH       /reports/{report}              (reports.update) - Protected
DELETE          /reports/{report}              (reports.destroy) - Protected
POST            /reports/{report}/details      (reports.addDetail) - Protected
```

## 📁 File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── ReportController.php (280+ lines)
├── Models/
│   ├── User.php (updated with reports relation)
│   ├── Report.php (with relationships)
│   └── ReportDetail.php (with relationships)
└── Providers/
    └── AppServiceProvider.php

config/
├── app.php
├── auth.php
├── cache.php
├── database.php
├── filesystems.php (public disk configured)
├── logging.php
├── mail.php
├── queue.php
├── services.php
├── session.php
└── database/

database/
├── factories/
│   └── UserFactory.php (updated with role)
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_04_06_044223_create_reports_table.php
│   ├── 2026_04_06_044224_create_report_details_table.php
│   └── 2026_04_06_044225_add_role_to_users_table.php
├── seeders/
│   └── DatabaseSeeder.php (creates test users)

resources/
├── css/
│   └── app.css (Tailwind)
├── js/
│   └── app.js
└── views/
    ├── dashboard.blade.php (custom role-based dashboard)
    ├── welcome.blade.php
    ├── layouts/
    │   ├── app.blade.php (main layout)
    │   ├── guest.blade.php
    │   └── navigation.blade.php (updated with reports links)
    ├── reports/
    │   ├── index.blade.php (list reports with filters)
    │   ├── create.blade.php (form with camera + GPS + JS)
    │   └── show.blade.php (detail view)
    ├── auth/
    │   ├── login.blade.php
    │   ├── register.blade.php
    │   └── ...
    └── profile/
        └── ...

routes/
└── web.php (with report routes)

storage/
└── app/
    └── public/
        └── kunjungan/ (foto disimpan di sini)

public/
└── storage -> symlink ke storage/app/public
```

## 🚀 Installation & Setup

### Prerequisites

- PHP 8.2 atau lebih tinggi
- MySQL/MariaDB
- Composer
- npm

### Step 1: Clone & Install

```bash
cd /path/to/project
composer install
npm install
```

### Step 2: Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sales_report
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Database Setup

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### Step 4: Build Assets

```bash
npm run build      # Production
npm run dev        # Development
```

### Step 5: Run Server

```bash
php artisan serve
# Buka http://localhost:8000
```

## 👥 Test User Credentials

### Admin User

- Email: `admin@example.com`
- Password: `password`
- Role: Admin (dapat melihat semua laporan)

### Sales Users

- Email: `sales1@example.com` - Budi
- Email: `sales2@example.com` - Andi
- Email: `sales3@example.com` - Doni
- Plus 5 random sales users
- Password: `password`
- Role: Sales (hanya bisa melihat laporan sendiri)

## 📋 Fitur Detail

### Dashboard

**Admin Dashboard**:

- Total Sales
- Total Reports Today
- Total Reports All Time
- Recent Reports List

**Sales Dashboard**:

- Today's Reports
- Today's Visits
- Total Reports
- Recent Reports List

### Create Report Page

- Dynamic kunjungan cards (dapat ditambah/dihapus)
- Form validation real-time
- Camera integration (not file upload)
- GPS auto-detection button
- Photo preview
- Loading indicator saat submit

### List Reports Page

- Table/card layout
- Filter by date range
- Filter by sales person (admin only)
- Pagination
- Total visits count per report
- Quick link to report detail

### Detail Report Page

- All visits displayed as cards
- Photo display (proportional size)
- GPS coordinates shown
- Google Maps link
- PC information
- Visit notes
- Timestamps

## 🔒 Security Features

- CSRF protection (built-in Laravel)
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade escaping)
- Authorization checks:
    - Sales users can only see their own reports
    - Admin can see all reports
- Password hashing (bcrypt)
- Secure session management

## 📱 Mobile Optimization

- Responsive breakpoints (sm, md, lg, xl, 2xl)
- Touch-friendly button sizes (3rem minimum)
- Large form inputs (44px height)
- Mobile-first CSS
- Optimized layout for small screens
- Flexible grid system

## 🎨 Design System

### Colors

- Primary: Blue (#2563eb)
- Success: Green (#16a34a)
- Warning: Yellow/Amber (#f59e0b)
- Danger: Red (#dc2626)
- Neutral: Gray shades

### Typography

- Font: Figtree
- Max width: 1280px (container)
- Responsive text sizes

### Spacing

- 0.25rem base unit (4px)
- Consistent padding/margin

### Components

- Cards with hover effects
- Buttons with loading states
- Forms with validation
- Icons (SVG inline)

## 🔧 Configuration

### Storage Configuration

```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL') . '/storage',
    'visibility' => 'public',
]
```

### Session Configuration

```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'database'),
'lifetime' => env('SESSION_LIFETIME', 120),
```

## 📊 Database Diagram

```
Users (1) ──────┐
                │
                ├─ (M) ──→ Reports
                │

Reports (1) ────┐
                ├─ (M) ──→ Report_Details
```

## 🐛 Common Issues & Solutions

### Issue: Camera not working

**Solution**:

- Ensure HTTPS or localhost (browser requirement)
- Check browser permissions for camera
- Modern browser required (Chrome, Firefox, Safari, Edge)

### Issue: GPS not working

**Solution**:

- Enable location services on device
- Check browser permissions
- May require HTTPS in production

### Issue: Photos not saved

**Solution**:

- Check storage permissions: `chmod -R 775 storage`
- Verify symbolic link: check public/storage exists
- Run: `php artisan storage:link`

### Issue: Database connection error

**Solution**:

- Verify MySQL is running
- Check .env database credentials
- Run: `php artisan migrate`

## 🌐 Browser Support

- Chrome 70+
- Firefox 55+
- Safari 12+
- Edge 15+

Required APIs:

- getUserMedia (camera)
- Geolocation
- Canvas
- Fetch API
- Local Storage

## 📈 Performance Notes

- Database queries are optimized with indexes
- Relationships use eager loading where possible
- Photos compressed clientside before upload
- Pagination: 10 items per page
- Static assets cached via browser

## 🛠️ Development Notes

### Adding New Fields to Report

1. Create migration for schema change
2. Update `ReportDetail::$fillable`
3. Update view form
4. Update controller validation
5. Run `php artisan migrate`

### Changing User Roles

```php
// Edit user role in database or via Tinker
User::find(1)->update(['role' => 'admin']);
```

### Customizing Validation Messages

Edit validation messages in `ReportController::store()` method.

## 📚 Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com
- Laravel Breeze: https://laravel.com/docs/breeze
- MDN Web APIs: https://developer.mozilla.org

## 📝 License

MIT License - feel free to use for commercial projects

## 👨‍💻 Developer Notes

Application built with:

- Laravel 12.x Framework
- Laravel Breeze Auth
- MySQL Database
- Tailwind CSS
- Vanilla JavaScript (no frameworks)
- Canvas API for photo processing
- Geolocation API

Total implementation time: Single session
Total files created/modified: 20+
Total lines of code: 1000+

---

**Last Updated**: April 6, 2026
**Version**: 1.0.0
