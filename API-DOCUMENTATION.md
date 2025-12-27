# Cargo Depot Container Management System - API Documentation

## Overview
This document describes the REST API endpoints for the Cargo Depot Container Management System.

## Base URL
```
http://localhost:8000
```

## Authentication
Currently, the API uses Laravel's session-based authentication. For production, implement token-based authentication using Laravel Sanctum or Passport.

---

## Gate Entry Management

### 1. List Gate Entries
**Endpoint:** `GET /gate-entries`

**Description:** Retrieve a paginated list of all gate entries.

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "container_id": 1,
      "truck_id": 1,
      "driver_id": 1,
      "shipping_line_id": 1,
      "entry_time": "2025-12-27T10:30:00Z",
      "remarks": "Container in good condition",
      "created_at": "2025-12-27T10:30:00Z",
      "updated_at": "2025-12-27T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 50
  }
}
```

### 2. Create Gate Entry
**Endpoint:** `POST /gate-entries`

**Description:** Register a new container at the gate.

**Request Body:**
```json
{
  "container_number": "MAEU1234567",
  "container_type": "40ft",
  "shipping_line_id": 1,
  "truck_id": 1,
  "driver_id": 1,
  "booking_details": "Booking reference: BK123456",
  "remarks": "Container arrived in good condition"
}
```

**Validation Rules:**
- `container_number`: required, string, unique
- `container_type`: required, string
- `shipping_line_id`: required, exists in shipping_lines table
- `truck_id`: required, exists in trucks table
- `driver_id`: required, exists in drivers table
- `booking_details`: optional, string
- `remarks`: optional, string

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Container registered successfully at gate",
  "data": {
    "id": 1,
    "container": {
      "id": 1,
      "container_number": "MAEU1234567",
      "type": "40ft",
      "status": "pending"
    }
  }
}
```

### 3. View Gate Entry
**Endpoint:** `GET /gate-entries/{id}`

**Description:** Retrieve details of a specific gate entry.

**Response:** `200 OK`
```json
{
  "data": {
    "id": 1,
    "container": {
      "id": 1,
      "container_number": "MAEU1234567",
      "type": "40ft"
    },
    "truck": {
      "id": 1,
      "plate_number": "ABC123"
    },
    "driver": {
      "id": 1,
      "name": "John Doe",
      "phone": "+1234567890"
    },
    "entry_time": "2025-12-27T10:30:00Z"
  }
}
```

---

## Container Inspection

### 1. List Inspections
**Endpoint:** `GET /inspections`

**Description:** Retrieve a paginated list of all container inspections.

### 2. Create Inspection
**Endpoint:** `POST /inspections`

**Description:** Record a container inspection by yard surveyor.

**Request Body:**
```json
{
  "container_id": 1,
  "surveyor_name": "Jane Smith",
  "condition": "damaged",
  "inspection_notes": "Minor dents on side panel",
  "action": "repair_required",
  "damages": [
    {
      "damage_code_id": 1,
      "description": "Dent on right side panel, 10cm diameter"
    },
    {
      "damage_code_id": 5,
      "description": "Roof shows signs of corrosion"
    }
  ]
}
```

**Validation Rules:**
- `container_id`: required, exists
- `surveyor_name`: required, string
- `condition`: required, enum (good, damaged, rejected)
- `inspection_notes`: optional, string
- `action`: optional, enum (proceed_to_eir, repair_required, wash_required, write_off)
- `damages`: optional, array
- `damages.*.damage_code_id`: required, exists
- `damages.*.description`: optional, string

**Response:** `201 Created`

### 3. View Inspection
**Endpoint:** `GET /inspections/{id}`

**Description:** Retrieve detailed inspection report with damages.

---

## EIR Management

### 1. List EIR Numbers
**Endpoint:** `GET /eir-numbers`

**Description:** Retrieve a list of all generated EIR numbers.

### 2. Generate EIR
**Endpoint:** `POST /eir-numbers`

**Description:** Generate Equipment Interchange Receipt for a container.

**Request Body:**
```json
{
  "container_id": 1,
  "issued_by": "Office Staff Name",
  "remarks": "Container in good condition, ready for storage"
}
```

**Validation Rules:**
- `container_id`: required, exists, unique (no duplicate EIRs)
- `issued_by`: required, string
- `remarks`: optional, string

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "EIR number generated successfully",
  "data": {
    "id": 1,
    "container_id": 1,
    "eir_number": "EIR-2025-000001",
    "issue_date": "2025-12-27T14:30:00Z",
    "issued_by": "Office Staff Name"
  }
}
```

### 3. View EIR Details
**Endpoint:** `GET /eir-numbers/{id}`

**Description:** Retrieve EIR details with container information.

---

## Container Release

### 1. List Releases
**Endpoint:** `GET /releases`

**Description:** Retrieve a list of all container releases.

### 2. Create Release Order
**Endpoint:** `POST /releases`

**Description:** Create a container release order.

**Request Body:**
```json
{
  "container_id": 1,
  "release_order_number": "RO-2025-001",
  "truck_id": 2,
  "driver_id": 2,
  "release_order_details": "Release approved by shipping line via email dated 2025-12-27"
}
```

**Validation Rules:**
- `container_id`: required, exists
- `release_order_number`: required, string, unique
- `truck_id`: required, exists
- `driver_id`: required, exists
- `release_order_details`: required, string

**Response:** `201 Created`

### 3. Validate at Gate B
**Endpoint:** `POST /releases/{id}/validate-gate-b`

**Description:** Validate container release at exit gate.

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Container validated and released at Gate B",
  "data": {
    "validated_at_gate_b": true,
    "gate_b_validation_time": "2025-12-27T16:45:00Z"
  }
}
```

---

## Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation errors |
| 500 | Internal Server Error |

---

## Error Response Format

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": [
      "Error description"
    ]
  }
}
```

---

## Container Status Flow

1. **pending** - Initial status when container arrives at gate
2. **inspected** - After yard surveyor inspection (if good)
3. **damaged** - If damages found during inspection
4. **repaired** - After repairs completed
5. **washed** - After washing process
6. **rejected** - Container not fit for storage (write-off)
7. **stored** - After EIR generation, ready for storage
8. **released** - After validation at Gate B

---

## Damage Codes

Standard damage codes are pre-seeded in the system:

| Code | Description | Component |
|------|-------------|-----------|
| D001 | Door hinge broken | door |
| D002 | Door lock damaged | door |
| F001 | Floor board cracked | floor |
| F002 | Floor water damage | floor |
| R001 | Roof dent | roof |
| R002 | Roof leak | roof |
| S001 | Side panel dent | side |
| S002 | Side panel rust | side |
| S003 | Side panel hole | side |
| C001 | Corner post damaged | corner |

---

## Rate Limiting

Default rate limiting:
- 60 requests per minute per user
- 1000 requests per hour per IP

---

## Webhooks (Future Enhancement)

The system can be extended to support webhooks for:
- Container arrival notifications
- Inspection completion
- EIR generation
- Container release events

---

## Additional Resources

- **Models**: See `app/Models/` for database model definitions
- **Controllers**: See `app/Http/Controllers/` for business logic
- **Migrations**: See `database/migrations/` for database schema
- **Tests**: See `tests/` for API testing examples
