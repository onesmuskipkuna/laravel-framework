# Cargo Depot Container Management System

A comprehensive Laravel-based system for managing container operations in a cargo depot facility.

## Features

### 1. Gate Entry Module
- Register containers at gate entry
- Record container number, type, and shipping line
- Track truck and driver information
- Capture car plate details

### 2. Yard Surveyor Module
- Validate container condition
- Inspect containers for damages
- Record damage codes with descriptions
- Determine action required (EIR, repair, wash, or write-off)

### 3. Damage Management
- Comprehensive damage code system
- Automatic repair cost calculation based on tariffs
- Track repair status (pending, in-repair, repaired)
- Before and after repair photo documentation

### 4. Tariff Management
- Shipping line-specific repair tariffs
- Damage code-based billing
- Multi-currency support

### 5. EIR Generation
- Equipment Interchange Receipt generation
- Unique EIR number assignment
- Track issuance date and issuer

### 6. Container Storage
- Storage instruction matching with shipping company
- Track storage location
- Multiple instruction sources (email, booking, phone)

### 7. Photo Management
- Upload container photos
- Before/after repair documentation
- Photo type classification
- Automatic notification to shipping lines

### 8. Interchange Document
- Generate interchange documents for container transporters
- Track recipient details
- Automatic copy to shipping line

### 9. Container Release Module
- Container releasing order validation
- Match with driver and truck details
- Gate B validation process
- Track release date and time

## Installation

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure database in `.env` file

6. Run migrations:
```bash
php artisan migrate
```

7. Seed the database with initial data:
```bash
php artisan db:seed
```

## Default Users

After seeding, the following users are available:

- **Admin**: admin@cargodepot.com / password
- **Gate Officer**: gate@cargodepot.com / password
- **Yard Surveyor**: surveyor@cargodepot.com / password
- **Office Staff**: office@cargodepot.com / password

## Workflow

### Container Intake Process
1. **Gate Entry**: Container arrives, gate officer registers container with truck and driver details
2. **Inspection**: Yard surveyor validates container condition
3. **Damage Recording**: If damaged, record damage codes and calculate repair costs
4. **Repair/Washing**: Container goes through repair or washing process
5. **EIR Generation**: Office generates EIR for containers in good condition
6. **Storage**: Container stored according to shipping line instructions

### Container Release Process
1. **Release Order**: Shipping line sends container releasing order
2. **Order Validation**: Office staff validates release order
3. **Truck Assignment**: Match with truck and driver details
4. **Gate B Validation**: Gate officer validates at exit gate
5. **Release**: Container released to truck

## Database Schema

### Core Tables
- `users` - System users with role-based access
- `shipping_lines` - Shipping companies
- `containers` - Container master records
- `drivers` - Driver information
- `trucks` - Truck/vehicle information

### Process Tables
- `gate_entries` - Container gate-in records
- `container_inspections` - Inspection records
- `damage_codes` - Standard damage classification codes
- `container_damages` - Container-specific damage records
- `tariffs` - Shipping line specific repair tariffs
- `eir_numbers` - Equipment Interchange Receipt records
- `container_photos` - Photo documentation
- `storage_instructions` - Container storage directives
- `interchange_documents` - Interchange receipts
- `container_releases` - Container release records

## API Endpoints

### Gate Entry
- `GET /gate-entries` - List all gate entries
- `GET /gate-entries/create` - Show gate entry form
- `POST /gate-entries` - Create new gate entry
- `GET /gate-entries/{id}` - View gate entry details

### Container Inspection
- `GET /inspections` - List all inspections
- `GET /inspections/create` - Show inspection form
- `POST /inspections` - Create new inspection
- `GET /inspections/{id}` - View inspection details

### EIR Management
- `GET /eir-numbers` - List all EIR numbers
- `GET /eir-numbers/create` - Show EIR generation form
- `POST /eir-numbers` - Generate new EIR
- `GET /eir-numbers/{id}` - View EIR details

### Container Release
- `GET /releases` - List all releases
- `GET /releases/create` - Show release form
- `POST /releases` - Create new release
- `GET /releases/{id}` - View release details
- `POST /releases/{id}/validate-gate-b` - Validate at gate B

## Security Features

- Role-based access control (Admin, Gate Officer, Surveyor, Office Staff)
- Password hashing
- CSRF protection
- SQL injection prevention through Eloquent ORM
- Input validation and sanitization

## Technology Stack

- **Framework**: Laravel 11
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **PHP**: 8.1+
- **Authentication**: Laravel's built-in authentication

## License

MIT License

## Support

For support, email support@cargodepot.com
