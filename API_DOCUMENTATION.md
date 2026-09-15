# API Documentation

## Overview

This document describes the API for the Announce (An) system. The API follows a JSON-RPC-like pattern where all requests are sent via POST with JSON body.

## Base Information

- **Endpoint**: `/api/index.php`
- **Method**: POST
- **Content-Type**: `application/json`
- **Authentication**: Required via `token` field in request body

---

## Request Format

All requests must be sent as JSON with the following structure:

```json
{
    "action": "<string>",      // Required: Action name to execute
    "token": "<string>",       // Required: Access token for authentication
    "data": {}                 // Optional: Action-specific data object
}
```

### Request Parameters

| Parameter | Type   | Required | Description                          |
|-----------|--------|----------|--------------------------------------|
| action    | string | Yes      | The action to execute                |
| token     | string | Yes      | Application access token             |
| data      | object | No       | Action-specific parameters           |

---

## Response Format

All responses follow a standard structure:

```json
{
    "success": true/false,
    "data": {},
    "error": null | {
        "code": <int>,
        "httpCode": <int>,
        "error": "<string>",
        "message": "<string>"
    }
}
```

### Response Fields

| Field   | Type    | Description                              |
|---------|---------|------------------------------------------|
| success | boolean | Indicates if the request was successful  |
| data    | object  | Response data (structure varies by action) |
| error   | object  | Error details (null if success is true)  |

---

## Authentication

Authentication is performed via the `token` field in the request body. The token must be valid and associated with an active application.

### Special Tokens

- **Admin Token**: Applications with `app_id` matching `ADMIN_APP_ID` configuration have admin privileges and can access `adm.*` actions.

---

## Error Codes

| Error Code          | HTTP Code | Description                    |
|---------------------|-----------|--------------------------------|
| API_BAD_REQUEST     | 400       | Invalid request format         |
| API_UNAUTHORIZED    | 401       | Invalid or missing token       |
| API_FORBIDDEN       | 403       | Insufficient permissions       |
| API_UNKNOWN_ACTION  | 400       | Unknown action name            |
| UNHANDLED_EXCEPTION | 500       | Internal server error          |

---

## API Actions

### Public Actions

#### `get_announces`

Get list of active announces for the current application.

**Request:**
```json
{
    "action": "get_announces",
    "token": "your_app_token",
    "data": {
        "exclude_uuids": ["uuid1", "uuid2"],  // Optional: UUIDs to exclude
        "device_fpt": "device_fingerprint"     // Optional: Device fingerprint
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "announces": [
            {
                "app_id": "app123",
                "uuid": "announce-uuid",
                "title": "Announce Title",
                "body": "Announce body text",
                "status": "live",
                "start_at": 1234567890,
                "end_at": 1234567890,
                "created_at": 1234567890,
                "updated_at": 1234567890
            }
        ]
    }
}
```

**Notes:**
- Only returns announces with status `live`
- Automatically updates device information if `device_fpt` is provided
- Filters by current application's `app_id`

---

### Admin Actions

Admin actions require admin privileges (token from admin application).

#### Announces Management

##### `adm.announces.list`

Get paginated list of announces with filtering options.

**Request:**
```json
{
    "action": "adm.announces.list",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2"],           // Optional: Filter by app IDs
        "statuses": ["draft", "live", "archived"], // Optional: Filter by statuses
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "created_at",
            "desc": false
        }
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "announces": [...],
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "created_at",
            "desc": false,
            "total": 999
        }
    }
}
```

##### `adm.announces.upsert`

Create or update an announce.

**Request:**
```json
{
    "action": "adm.announces.upsert",
    "token": "admin_token",
    "data": {
        "announce": {
            "uuid": "optional-uuid",           // Optional: If not provided, will be generated
            "app_id": "app123",
            "title": "Announce Title",
            "body": "Announce body text",
            "status": "draft",                  // "draft", "live", or "archived"
            "start_at": 1234567890,            // Optional: Start timestamp
            "end_at": 1234567890,              // Optional: End timestamp
            "created_at": 1234567890,          // Auto-generated if not provided
            "updated_at": 1234567890           // Auto-generated if not provided
        }
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "announce": {
            "app_id": "app123",
            "uuid": "generated-or-provided-uuid",
            "title": "Announce Title",
            "body": "Announce body text",
            "status": "draft",
            "start_at": 1234567890,
            "end_at": 1234567890,
            "created_at": 1234567890,
            "updated_at": 1234567890
        }
    }
}
```

##### `adm.announces.delete`

Delete one or more announces by UUID.

**Request:**
```json
{
    "action": "adm.announces.delete",
    "token": "admin_token",
    "data": {
        "uuids": ["uuid1", "uuid2", "uuid3"]
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "removed": 3
    }
}
```

---

#### Apps Management

##### `adm.apps.list`

Get list of all applications.

**Request:**
```json
{
    "action": "adm.apps.list",
    "token": "admin_token",
    "data": {}
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "apps": [
            {
                "app_id": "app123",
                "token": "app_token_string",
                "enabled": true,
                "created_at": 1234567890,
                "updated_at": 1234567890
            }
        ]
    }
}
```

##### `adm.apps.upsert`

Create or update an application.

**Request:**
```json
{
    "action": "adm.apps.upsert",
    "token": "admin_token",
    "data": {
        "app": {
            "app_id": "app123",
            "token": "custom_token_or_generated",
            "enabled": true,
            "created_at": 1234567890,
            "updated_at": 1234567890
        }
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "app": {
            "app_id": "app123",
            "token": "app_token_string",
            "enabled": true,
            "created_at": 1234567890,
            "updated_at": 1234567890
        }
    }
}
```

##### `adm.apps.delete`

Delete one or more applications by ID.

**Request:**
```json
{
    "action": "adm.apps.delete",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2", "app3"]
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "removed": 3
    }
}
```

---

#### Devices Management

##### `adm.devices.list`

Get paginated list of devices with filtering options.

**Request:**
```json
{
    "action": "adm.devices.list",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2"],          // Optional: Filter by app IDs
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "last_seen_at",
            "desc": true
        }
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "devices": [
            {
                "app_id": "app123",
                "device_fpt": "device_fingerprint_hash",
                "first_seen_at": 1234567890,
                "last_seen_at": 1234567890,
                "request_count": 42
            }
        ],
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "last_seen_at",
            "desc": true,
            "total": 150
        }
    }
}
```

---

## Data Models

### Announce Object

| Field      | Type    | Description                           |
|------------|---------|---------------------------------------|
| uuid       | string  | Unique identifier                     |
| app_id     | string  | Associated application ID             |
| title      | string  | Announce title                        |
| body       | string  | Announce body text                    |
| status     | string  | Status: `draft`, `live`, `archived`   |
| start_at   | int     | Start timestamp (Unix epoch)          |
| end_at     | int     | End timestamp (Unix epoch)            |
| created_at | int     | Creation timestamp (Unix epoch)       |
| updated_at | int     | Last update timestamp (Unix epoch)    |

### App Object

| Field      | Type    | Description                      |
|------------|---------|----------------------------------|
| app_id     | string  | Application unique identifier    |
| token      | string  | Access token for authentication  |
| enabled    | boolean | Application active status        |
| created_at | int     | Creation timestamp (Unix epoch)  |
| updated_at | int     | Last update timestamp (Unix epoch) |

### Device Object

| Field         | Type   | Description                          |
|---------------|--------|--------------------------------------|
| app_id        | string | Associated application ID            |
| device_fpt    | string | Device fingerprint hash              |
| first_seen_at | int    | First seen timestamp (Unix epoch)    |
| last_seen_at  | int    | Last seen timestamp (Unix epoch)     |
| request_count | int    | Total number of requests             |

### Pagination Object

| Field  | Type    | Description                    |
|--------|---------|--------------------------------|
| offset | int     | Number of items to skip        |
| limit  | int     | Maximum number of items        |
| order  | string  | Field to sort by               |
| desc   | boolean | Sort in descending order       |
| total  | int     | Total number of items (in responses) |

---

## Announce Statuses

| Status   | Value     | Description                    |
|----------|-----------|--------------------------------|
| Draft    | `draft`   | Not yet published              |
| Live     | `live`    | Currently active/visible       |
| Archived | `archived`| No longer active               |

---

## Usage Examples

### Example 1: Get Active Announces (Client App)

```bash
curl -X POST https://example.com/api/index.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "get_announces",
    "token": "my_app_token",
    "data": {
      "device_fpt": "abc123def456"
    }
  }'
```

### Example 2: Create New Announce (Admin)

```bash
curl -X POST https://example.com/api/index.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "adm.announces.upsert",
    "token": "admin_token",
    "data": {
      "announce": {
        "app_id": "my_app",
        "title": "New Feature Release",
        "body": "We released a new feature!",
        "status": "live"
      }
    }
  }'
```

### Example 3: Get Devices List (Admin)

```bash
curl -X POST https://example.com/api/index.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "adm.devices.list",
    "token": "admin_token",
    "data": {
      "apps_ids": ["my_app"],
      "pagination": {
        "offset": 0,
        "limit": 50,
        "order": "last_seen_at",
        "desc": true
      }
    }
  }'
```

---

## Notes

1. All timestamps are returned as Unix epoch integers (seconds since January 1, 1970)
2. UUIDs are automatically generated if not provided during announce creation
3. Device fingerprints are tracked automatically when calling `get_announces` with `device_fpt`
4. Admin actions require special admin token configured in `AnConfig::ADMIN_APP_ID`
5. All string comparisons for tokens use timing-safe comparison (`hash_equals`)
