# Smart Parking System ERD

## Overview
This ERD models a production-ready Smart Parking platform with secure authentication, operational control, and auditing.

## Mermaid ERD
```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar username UK
        varchar email UK
        varchar password_hash
        varchar role
        boolean is_active
        datetime last_login_at
        datetime created_at
        datetime updated_at
    }

    ZONES {
        bigint id PK
        varchar zone_code UK
        varchar name
        boolean is_active
        datetime created_at
        datetime updated_at
    }

    SLOTS {
        bigint id PK
        bigint zone_id FK
        varchar slot_code UK
        tinyint state
        boolean is_active
        datetime created_at
        datetime updated_at
    }

    SLOT_STATE_HISTORY {
        bigint id PK
        bigint slot_id FK
        tinyint old_state
        tinyint new_state
        bigint changed_by_user_id FK
        datetime changed_at
        varchar source
        text note
    }

    AUDIT_LOGS {
        bigint id PK
        bigint user_id FK
        varchar action
        varchar entity_type
        bigint entity_id
        json payload_json
        varchar ip_address
        varchar user_agent
        datetime created_at
    }

    SESSIONS {
        bigint id PK
        bigint user_id FK
        varchar session_token UK
        datetime issued_at
        datetime expires_at
        datetime revoked_at
        varchar ip_address
        varchar user_agent
    }

    USERS ||--o{ SESSIONS : owns
    USERS ||--o{ AUDIT_LOGS : writes
    USERS ||--o{ SLOT_STATE_HISTORY : changes

    ZONES ||--o{ SLOTS : contains
    SLOTS ||--o{ SLOT_STATE_HISTORY : tracks
```

## Relationship Notes
- One zone has many slots.
- One slot has many state history records.
- One user can create many audit log entries.
- One user can own many active/inactive sessions.
- Slot state transitions should always append to history for traceability.

## Constraints and Validation
- `zones.zone_code` unique, uppercase format (example: `A`, `B`, `AA`).
- `slots.slot_code` unique, uppercase format (example: `A1`, `B12`).
- `slots.state` constraint: `0` available, `1` occupied, `2` maintenance.
- Foreign keys should be indexed for query performance.

## Recommended Indexes
- `users(username)` unique
- `users(email)` unique
- `zones(zone_code)` unique
- `slots(slot_code)` unique
- `slots(zone_id, state)`
- `slot_state_history(slot_id, changed_at desc)`
- `audit_logs(user_id, created_at desc)`
- `sessions(user_id, expires_at)`
