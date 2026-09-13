<?php

/**
 *  Репозиторий Приложений
 */
class AnAppsRepository extends IsAnRepository {

    /**
     * Вставка или обновление приложения
     */
    public function upsert(
        string $applicationId,
        string $token,
        bool $enabled,
    ): bool {
        $sql = "INSERT INTO " . AnDb::TABLE_APPS . " 
                (application_id, token, enabled, created_at, updated_at)
                VALUES (:application_id, :token, :enabled, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    token = VALUES(token),
                    enabled = VALUES(enabled),
                    updated_at = NOW()";

        return $this->db->db->execute($sql, [
            ':application_id' => $applicationId,
            ':token' => $token,
            ':enabled' => $enabled ? 1 : 0,
        ]);
    }

    /**
     * Получение приложения по токену
     */
    public function getByToken(string $token): ?AnApp {
        $sql = "SELECT * FROM " . AnDb::TABLE_APPS . " WHERE token = :token LIMIT 1";
        
        $row = $this->db->db->fetch($sql, [':token' => $token]);
        
        if (!$row) {
            return null;
        }

        return new AnApp(
            applicationId: $row['application_id'],
            token: $row['token'],
            enabled: (bool)$row['enabled'],
            createdAt: new DateTimeImmutable($row['created_at']),
            updatedAt: new DateTimeImmutable($row['updated_at']),
        );
    }

    /**
     * Получение списка приложений
     */
    public function getList(?bool $enabled = null, int $limit = 100, int $offset = 0): array {
        $conditions = [];
        $params = [];

        if ($enabled !== null) {
            $conditions[] = 'enabled = :enabled';
            $params[':enabled'] = $enabled ? 1 : 0;
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_APPS . " 
                {$whereClause}
                ORDER BY created_at DESC
                LIMIT :offset, :limit";

        $params[':offset'] = $offset;
        $params[':limit'] = $limit;

        $rows = $this->db->db->fetchAll($sql, $params);

        $result = [];
        foreach ($rows as $row) {
            $result[] = new AnApp(
                applicationId: $row['application_id'],
                token: $row['token'],
                enabled: (bool)$row['enabled'],
                createdAt: new DateTimeImmutable($row['created_at']),
                updatedAt: new DateTimeImmutable($row['updated_at']),
            );
        }

        return $result;
    }

}

