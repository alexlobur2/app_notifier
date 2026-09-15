# Документация по API

## Обзор

Этот документ описывает API системы Announce (An). API следует шаблону, подобному JSON-RPC, где все запросы отправляются методом POST с JSON-телом.

---

## Основная информация

- **Endpoint**: `/api/index.php`
- **Метод**: POST
- **Content-Type**: `application/json`
- **Аутентификация**: Требуется через поле `token` в теле запроса

---

## Формат запроса

Все запросы должны отправляться в формате JSON со следующей структурой:

```json
{
    "action": "<string>",   // Обязательно: Имя действия для выполнения
    "token": "<string>",    // Обязательно: Токен доступа для аутентификации
    "data": {}              // Опционально: Объект с параметрами действия
}
```

### Параметры запроса

| Параметр | Тип    | Обязательный | Описание                            |
|----------|--------|--------------|-------------------------------------|
| action   | string | Да           | Действие для выполнения             |
| token    | string | Да           | Токен доступа приложения            |
| data     | object | Нет          | Параметры, специфичные для действия |



## Формат ответа

Все ответы следуют стандартной структуре:

```json
{
    "success": true | false,
    "data": {},
    "error": null | {
        "code": <int>,
        "httpCode": <int>,
        "error": "<string>",
        "message": "<string>",
        "details": []
    }
}
```
### Поля ответа

| Поле    | Тип     | Описание                                      |
|---------|---------|-----------------------------------------------|
| success | boolean | Указывает, был ли запрос успешным             |
| data    | object  | Данные ответа (структура зависит от действия) |
| error   | object  | Детали ошибки (null если success равен true)  |


---

## Аутентификация

Аутентификация выполняется через поле `token` в теле запроса. Токен должен быть действительным и связан с активным приложением.

### Специальные токены

- **Админ-токен**: Приложения с `app_id`, совпадающим с конфигурацией `ADMIN_APP_ID`, имеют права администратора и могут выполнять действия `adm.*`.


## Коды ошибок

| Код ошибки          | HTTP код | Описание                           |
|---------------------|----------|------------------------------------|
| API_BAD_REQUEST     | 400      | Неверный формат запроса            |
| API_UNAUTHORIZED    | 401      | Неверный или отсутствующий токен   |
| API_FORBIDDEN       | 403      | Недостаточно прав                  |
| API_UNKNOWN_ACTION  | 400      | Неизвестное имя действия           |
| UNHANDLED_EXCEPTION | 500      | Внутренняя ошибка сервера          |

---

## Публичные действия API

### `get_announces`

Получение списка активных анонсов для текущего приложения.

**Запрос:**
```json
{
    "action": "get_announces",
    "token": "your_app_token",
    "data": {
        "exclude_uuids": ["uuid1", "uuid2"],  // Опционально: UUID для исключения
        "device_fpt": "device_fingerprint"    // Отпечаток устройства
    }
}
```

**Ответ:**
```json
{
    "success": true,
    "data": {
        "announces": [...]  // Объекты анонсов
    }
}
```

**Примечания:**
- Возвращает только анонсы со статусом `live`
- Автоматически обновляет информацию об устройстве, если предоставлен `device_fpt`
- Фильтрует по `app_id` текущего приложения

---

## Действия администратора API

Действия администратора требуют прав администратора (токен от админ-приложения настроенного в `AnConfig::ADMIN_APP_ID`).

<br />

### `adm.announces.list`

Получение постраничного списка анонсов с возможностями фильтрации.

**Запрос:**
```json
{
    "action": "adm.announces.list",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2"],               // Опционально: Фильтр по ID приложений
        "statuses": ["draft", "live", "archived"],  // Опционально: Фильтр по статусам
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "created_at",
            "desc": false
        }
    }
}
```

**Ответ:**
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

<br />

### `adm.announces.upsert`

Создание или обновление анонса.

**Запрос:**
```json
{
    "action": "adm.announces.upsert",
    "token": "admin_token",
    "data": {
        "announce": {
            "uuid": "optional-uuid",      // Опционально: Если не указано, будет сгенерирован
            "app_id": "app123",
            "title": "Заголовок анонса",
            "body": "Текст анонса",
            "status": "draft",            // "draft", "live" или "archived"
            "start_at": 1234567890,       // Опционально: Временная метка начала
            "end_at": 1234567890          // Опционально: Временная метка окончания
        }
    }
}
```

**Ответ:**
```json
{
    "success": true,
    "data": {
        "announce": {
            "app_id": "app123",
            "uuid": "generated-or-provided-uuid",
            "title": "Заголовок анонса",
            "body": "Текст анонса",
            "status": "draft",
            "start_at": 1234567890,
            "end_at": 1234567890,
            "created_at": 1234567890,
            "updated_at": 1234567890
        }
    }
}
```

<br />

### `adm.announces.delete`

Удаление одного или нескольких анонсов по UUID.

**Запрос:**
```json
{
    "action": "adm.announces.delete",
    "token": "admin_token",
    "data": {
        "uuids": ["uuid1", "uuid2", "uuid3"]
    }
}
```

**Ответ:**
```json
{
    "success": true,
    "data": {
        "removed": 3
    }
}
```

<br />

### `adm.apps.list`

Получение списка всех приложений.

**Запрос:**
```json
{
    "action": "adm.apps.list",
    "token": "admin_token",
    "data": {}
}
```

**Ответ:**
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

<br />

### `adm.apps.upsert`

Создание или обновление приложения.

**Запрос:**
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

**Ответ:**
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

<br />

### `adm.apps.delete`

Удаление одного или нескольких приложений по ID.

**Запрос:**
```json
{
    "action": "adm.apps.delete",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2", "app3"]
    }
}
```

**Ответ:**
```json
{
    "success": true,
    "data": {
        "removed": 3
    }
}
```

<br />

### `adm.devices.list`

Получение постраничного списка устройств с возможностями фильтрации.

**Запрос:**
```json
{
    "action": "adm.devices.list",
    "token": "admin_token",
    "data": {
        "apps_ids": ["app1", "app2"],          // Опционально: Фильтр по ID приложений
        "pagination": {
            "offset": 0,
            "limit": 100,
            "order": "last_seen_at",
            "desc": true
        }
    }
}
```

**Ответ:**
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

## Модели данных

### Объект Announce (Анонс)

| Поле       | Тип     | Описание                                |
|------------|---------|-----------------------------------------|
| uuid       | string  | Уникальный идентификатор                |
| app_id     | string  | ID связанного приложения                |
| title      | string  | Заголовок анонса                        |
| body       | string  | Текст анонса                            |
| status     | string  | Статус: `draft`, `live`, `archived`     |
| start_at   | int     | Временная метка начала (Unix epoch)     |
| end_at     | int     | Временная метка окончания (Unix epoch)  |
| created_at | int     | Временная метка создания (Unix epoch)   |
| updated_at | int     | Временная метка обновления (Unix epoch) |

### Объект App (Приложение)

| Поле       | Тип     | Описание                                |
|------------|---------|-----------------------------------------|
| app_id     | string  | Уникальный идентификатор приложения     |
| token      | string  | Токен доступа для аутентификации        |
| enabled    | boolean | Статус активности приложения            |
| created_at | int     | Временная метка создания (Unix epoch)   |
| updated_at | int     | Временная метка обновления (Unix epoch) |

### Объект Device (Устройство)

| Поле          | Тип     | Описание                                          |
|---------------|---------|---------------------------------------------------|
| app_id        | string  | ID связанного приложения                          |
| device_fpt    | string  | Хеш отпечатка устройства                          |
| first_seen_at | int     | Временная метка первого появления (Unix epoch)    |
| last_seen_at  | int     | Временная метка последнего появления (Unix epoch) |
| request_count | int     | Общее количество запросов                         |

### Объект Pagination (Пагинация)

| Поле   | Тип     | Описание                               |
|--------|---------|----------------------------------------|
| offset | int     | Количество пропускаемых элементов      |
| limit  | int     | Максимальное количество элементов      |
| order  | string  | Поле для сортировки                    |
| desc   | boolean | Сортировка по убыванию                 |
| total  | int     | Общее количество элементов (в ответах) |

---

## Статусы анонсов

| Статус   | Значение    | Описание                           |
|----------|-------------|------------------------------------|
| Draft    | `draft`     | Еще не опубликован                 |
| Live     | `live`      | В настоящее время активен/видим    |
| Archived | `archived`  | Больше не активен                  |

---


