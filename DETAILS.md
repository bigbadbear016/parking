# Smart Parking System - Production Documentation

## 1. Executive Summary
Smart Parking System is a production-ready web platform for real-time parking operations. It provides:

- A live public dashboard for occupancy visibility.
- A secured admin console for operational control.
- Centralized API services.
- Relational database persistence with full auditability.

The solution supports light/dark themes, role-based access, and operational workflows suitable for campus, hospital, and enterprise environments.

## 2. System Architecture
### 2.1 Application Layers
- Presentation Layer: `index.php`, `admin/index.php`.
- API Layer: `api.php` (JSON responses, CRUD operations).
- Data Layer: MySQL (or MariaDB) as system-of-record.

### 2.2 Runtime Topology
- Web server: Apache with PHP-FPM or mod_php.
- Database server: MySQL 8+ / MariaDB 10.6+.
- Reverse proxy and TLS termination recommended for production.

## 3. Workspace and Entry Points
- `index.php`: Public mission-control dashboard.
- `admin/index.php`: Primary admin console.
- `api.php`: Core API endpoint for slot and zone operations.
- `state.json`, `distance.json`, `distance_log.txt`: Auxiliary telemetry/state artifacts.

## 4. Data Model (Relational)
### 4.1 Core Entities
1. `zones`
- `id` (PK)
- `zone_code` (unique, uppercase)
- `name`
- `is_active`
- `created_at`, `updated_at`

2. `slots`
- `id` (PK)
- `slot_code` (unique, format like `A12`)
- `zone_id` (FK -> `zones.id`)
- `state` (`0`, `1`, `2`)
- `created_at`, `updated_at`

3. `users`
- `id` (PK)
- `username` (unique)
- `password_hash` (bcrypt/argon2)
- `role` (`admin`, `superadmin`, `operator`)
- `is_active`
- `last_login_at`

4. `audit_logs`
- `id` (PK)
- `user_id` (FK -> `users.id`)
- `action`
- `entity_type` (`slot`, `zone`, `auth`)
- `entity_id`
- `payload_json`
- `created_at`

### 4.2 State Semantics
- `0` = Available
- `1` = Occupied
- `2` = Maintenance

## 5. Authentication and Authorization
### 5.1 Authentication
- Admin access requires credential-based login against the `users` table.
- Passwords are stored as secure hashes (`password_hash` / `password_verify`).
- Sessions are server-managed with secure cookie flags (`HttpOnly`, `Secure`, `SameSite=Lax/Strict`).

### 5.2 Authorization
- Role-based access control (RBAC) governs write operations.
- Read-only operations can be publicly exposed where required.
- Destructive actions (delete zone/slot) are restricted to privileged roles.

### 5.3 Security Controls
- CSRF token validation for state-changing requests.
- Input normalization and regex validation for zone/slot formats.
- Request logging, failed login rate limiting, and lockout policy.

## 6. API Contract (`api.php`)
### 6.1 Endpoints
- `GET /api.php`: Fetch current slot state map.
- `POST /api.php`: Execute slot/zone actions.

### 6.2 POST Actions
1. `action=update`
- Parameters: `slot`, `state`
- Behavior: Updates one slot state.

2. `action=addSlot`
- Parameters: `slot`, `state`
- Behavior: Creates a new slot record.

3. `action=deleteSlot`
- Parameters: `slot`
- Behavior: Deletes an existing slot.

4. `action=renameSlot`
- Parameters: `oldSlot`, `newSlot`
- Behavior: Renames slot code while preserving state and zone mapping.

5. `action=addZone`
- Parameters: `zone`, `count`, `state`
- Behavior: Creates a new zone and its generated slots.

6. `action=renameZone`
- Parameters: `oldZone`, `newZone`
- Behavior: Renames zone prefix for matching slot codes.

7. `action=deleteZone`
- Parameters: `zone`
- Behavior: Deletes zone and associated slots.

### 6.3 Validation and Error Handling
- Slot format: `^[A-Z]+\d+$`
- Zone format: `^[A-Z]+$`
- State values: `0 | 1 | 2`
- Response codes:
  - `200` success
  - `400` validation error
  - `401/403` unauthorized/forbidden
  - `404` not found
  - `405` method not allowed
  - `409` conflict

## 7. Public Dashboard Capabilities
- Polling interval: 1 second for near real-time updates.
- Zone grouping by slot prefix (e.g., `A`, `B`, `AA`).
- Visual state cards for available/occupied/maintenance slots.
- KPI panel for free, occupied, maintenance, utilization, and availability.
- Last-update timestamp indicator.
- Theme preference persistence via `localStorage` key `parking-theme`.

## 8. Admin Console Capabilities
- Secure sign-in (database-backed users).
- Slot operations: add, update, rename, delete, save batch changes.
- Zone operations: add, rename, delete.
- Search, sorting, and collapsible zone sections.
- Modal confirmations for sensitive operations.
- Status/error handling with SweetAlert-style dialogs.

## 9. UI and Theme Standards
- Design system uses CSS custom properties for consistency.
- Theme implementation is standardized through CSS custom properties for consistent light/dark behavior.

## 10. Deployment and Operations
### 10.1 Environment Requirements
- PHP 8.1+
- Apache/Nginx
- MySQL/MariaDB
- TLS certificate for HTTPS

### 10.2 Configuration
- Store DB and app secrets in environment variables, not source files.
- Enforce production PHP settings (`display_errors=Off`, proper logging enabled).
- Configure backup policy for database and audit logs.

### 10.3 Observability
- Centralized application logs.
- API error-rate monitoring.
- DB performance monitoring for write-heavy admin operations.

## 11. Compliance and Reliability Considerations
- Maintain immutable audit trail for administrative actions.
- Apply least-privilege access for DB users and admin roles.
- Implement disaster recovery with point-in-time restore strategy.
- Add integration tests for slot/zone lifecycle and auth flows.

## 12. Future Production Enhancements
- Introduce WebSocket/SSE for push-based real-time updates.
- Add multi-site support (campus/branch segmentation).
- Add SSO integration (OIDC/SAML).
- Add analytics dashboard for occupancy trends and forecasting.
