# 🚀 Quick Start Guide - Cargo Depot Container Management System

## What is This System?
A complete Laravel-based application for managing container operations in a cargo depot, from gate entry to release.

## Quick Setup (5 Minutes)

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Create Database
```bash
touch database/database.sqlite
```

### 4. Run Migrations & Seed Data
```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Server
```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Login Credentials
```
Admin: admin@cargodepot.com / password
Gate Officer: gate@cargodepot.com / password
Surveyor: surveyor@cargodepot.com / password
Office Staff: office@cargodepot.com / password
```

## Main Workflows

### 1️⃣ Container Arrival (Gate Entry)
**Who:** Gate Officer  
**Endpoint:** `POST /gate-entries`  
**What:** Register container with truck and driver details

```json
{
  "container_number": "MAEU1234567",
  "container_type": "40ft",
  "shipping_line_id": 1,
  "truck_id": 1,
  "driver_id": 1,
  "booking_details": "Booking ref: BK123",
  "remarks": "Container in good condition"
}
```

### 2️⃣ Container Inspection (Yard)
**Who:** Yard Surveyor  
**Endpoint:** `POST /inspections`  
**What:** Inspect container and record any damages

```json
{
  "container_id": 1,
  "surveyor_name": "John Smith",
  "condition": "damaged",
  "inspection_notes": "Minor dents on side panel",
  "action": "repair_required",
  "damages": [
    {
      "damage_code_id": 7,
      "description": "Side panel dent, 10cm diameter"
    }
  ]
}
```

### 3️⃣ EIR Generation (Office)
**Who:** Office Staff  
**Endpoint:** `POST /eir-numbers`  
**What:** Generate Equipment Interchange Receipt for good containers

```json
{
  "container_id": 1,
  "issued_by": "Office Staff Name",
  "remarks": "Container ready for storage"
}
```

### 4️⃣ Container Release (Gate B)
**Who:** Gate Officer  
**Endpoint:** `POST /releases`  
**What:** Create release order for container pickup

```json
{
  "container_id": 1,
  "release_order_number": "RO-2025-001",
  "truck_id": 2,
  "driver_id": 2,
  "release_order_details": "Approved by shipping line"
}
```

**Then validate at gate:**  
`POST /releases/{id}/validate-gate-b`

## Container Status Flow
```
📦 pending (arrival)
   ↓
🔍 inspected (surveyor checked)
   ↓
   ├─➡️ good → 📋 EIR generated → 🏪 stored → ✅ released
   │
   ├─➡️ damaged → 🔧 repaired → 📋 EIR generated → 🏪 stored → ✅ released
   │
   ├─➡️ washed → 📋 EIR generated → 🏪 stored → ✅ released
   │
   └─➡️ rejected (write-off) ❌
```

## Pre-loaded Data

### Shipping Lines (3)
- Maersk Line (MAEU)
- MSC Mediterranean Shipping Company (MSCU)
- CMA CGM (CMDU)

### Damage Codes (10)
- **D001-D002:** Door damages
- **F001-F002:** Floor damages
- **R001-R002:** Roof damages
- **S001-S003:** Side panel damages
- **C001:** Corner damages

### User Roles (4)
- Admin (full access)
- Gate Officer (gate operations)
- Surveyor (inspections)
- Office Staff (documentation)

## Testing
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit tests/Unit/ContainerTest.php
```

## Project Structure
```
app/
├── Models/              # 14 Eloquent models
├── Http/Controllers/    # 4 main controllers
└── Mail/               # Email notifications

database/
├── migrations/         # 15 database tables
└── seeders/           # Initial data

routes/
└── web.php            # All API routes

tests/
└── Unit/              # Unit tests
```

## Key Features
✅ Gate entry registration  
✅ Container inspection  
✅ Damage management with 10 codes  
✅ Automatic repair cost calculation  
✅ EIR generation  
✅ Photo documentation tracking  
✅ Storage instructions  
✅ Interchange documents  
✅ Container release validation  
✅ Role-based access control  
✅ Email notifications (structure ready)  

## Need Help?

### Documentation
- **Full README:** See `README.md`
- **API Reference:** See `API-DOCUMENTATION.md`
- **Detailed Overview:** See `IMPLEMENTATION-SUMMARY.md`
- **Production Deploy:** See `DEPLOYMENT-GUIDE.md`

### Common Commands
```bash
# Clear all caches
php artisan optimize:clear

# Fresh database
php artisan migrate:fresh --seed

# Run tests
vendor/bin/phpunit

# Check routes
php artisan route:list

# Open tinker console
php artisan tinker
```

### Example Test Data Creation
```bash
php artisan tinker
```
```php
// Create a shipping line
$line = ShippingLine::create([
    'name' => 'Test Shipping',
    'code' => 'TEST',
    'email' => 'test@shipping.com'
]);

// Create a driver
$driver = Driver::create([
    'name' => 'John Driver',
    'phone' => '+1234567890',
    'license_number' => 'LIC123456',
    'id_number' => 'ID123456'
]);

// Create a truck
$truck = Truck::create([
    'plate_number' => 'ABC123',
    'type' => 'Container Truck'
]);

exit
```

## Support
For issues or questions:
- GitHub Issues: [Repository Issues](https://github.com/onesmuskipkuna/laravel-framework/issues)
- Email: support@cargodepot.com

---

**Built with ❤️ using Laravel 11 + PHP 8.3**

🎉 **System is production-ready!**
