<?php

/**
 *  Репозиторий Приложений
 */
class AnAppsRepository extends IsAnRepository {

    /**
     * Вставка или обновление приложения
     * @throws Exception
     */
    public function upsert( string $applicationId, string $token, bool $enabled ): bool {
        $sql = "INSERT INTO " . AnDb::TABLE_APPS . " 
                (application_id, token, enabled, created_at, updated_at)
                VALUES (:application_id, :token, :enabled, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    token = VALUES(token),
                    enabled = VALUES(enabled),
                    updated_at = NOW()";

        $result = $this->lpdo->execute($sql, [
            ':application_id' => $applicationId,
            ':token' => $token,
            ':enabled' => $enabled ? 1 : 0,
        ]);
        $this->throwOnDbError(); // исключение при ошибке
        return $result;
    }


    /**
     * Получение приложения по токену
     * @throws Exception
     */
    public function getByToken(string $token): ?AnApp {
        $row = $this->lpdo->exec2val(
            "SELECT * FROM " . AnDb::TABLE_APPS . " WHERE token = :token LIMIT 1",
            [':token' => $token]
        );
        $this->throwOnDbError(); // исключение при ошибке
        return !$row ? null : $this->mapToAnApp($row);
    }


    /**
     * Получение списка приложений
     * @throws Exception
     */
    public function getList(?bool $enabled = null, int $limit = 100, int $offset = 0): array {

        // построение запроса
        $conditions = [];
        if ($enabled !== null) $conditions[] = 'enabled = :enabled';
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_APPS . " 
                {$whereClause}
                ORDER BY created_at DESC
                LIMIT :offset, :limit";

        $params = [
            ':enabled' => is_null($enabled) ? null : ($enabled ? 1 : 0),
            ':offset' => $offset,
            ':limit' => $limit,
        ];

        // запрос к БД
        $rows = $this->lpdo->exec2array($sql, $params);
        $this->throwOnDbError(); // исключение при ошибке

        return array_map( function ($row) { return $this->mapToAnApp($row); }, $rows);
    }


    /**
     * @throws DateMalformedStringException
     */
    private function mapToAnApp(array $row): AnApp {
        return new AnApp(
            applicationId:  $row['application_id'],
            token:          $row['token'],
            enabled:        (bool)$row['enabled'],
            createdAt:      new DateTimeImmutable($row['created_at']),
            updatedAt:      new DateTimeImmutable($row['updated_at']),
        );
    }

}

