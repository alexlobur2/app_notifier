<?php

/**
 *  Репозиторий Приложений
 */
class AnAppsRepository extends IsAnRepository {

    /**
     *  Вставка или обновление приложения
     *  @throws Exception
     */
    public function upsert(string $appId, string $token, bool $enabled): void {
        $sql = "INSERT INTO ".AnDb::TABLE_APPS." 
            (app_id, token, enabled, created_at, updated_at)
            VALUES (:app_id, :token, :enabled, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                token = VALUES(token),
                enabled = VALUES(enabled),
                updated_at = NOW()";
        $this->lpdo->execute($sql, [
            ':app_id'   => $appId,
            ':token'    => $token,
            ':enabled'  => $enabled ? 1 : 0,
        ]);
    }


    /**
     *  Получение приложения по app_id
     *  @throws Exception
     */
    public function getByAppId(string $appId): ?AnApp {
        $row = $this->lpdo->exec2row(
            "SELECT * FROM ".AnDb::TABLE_APPS." WHERE app_id = :app_id LIMIT 1",
            [':app_id' => $appId]
        );
        return !$row ? null : $this->mapToAnApp($row);
    }


    /**
     *  Получение приложения по токену
     *  @throws Exception
     */
    public function getByToken(string $token): ?AnApp {
        $row = $this->lpdo->exec2row(
            "SELECT * FROM ".AnDb::TABLE_APPS." WHERE token = :token LIMIT 1",
            [':token' => $token]
        );
        return !$row ? null : $this->mapToAnApp($row);
    }


    /**
     * Получение списка приложений
     * @throws Exception
     */
    public function getList(): array {
        $rows = $this->lpdo->query2array("SELECT * FROM ".AnDb::TABLE_APPS);
        return array_map(fn($row) => $this->mapToAnApp($row), $rows);
    }


    /**
     *  Удаление приложений по appId
     *  @throws Exception
     */
    public function deleteByIds(array $appIds): int {
        $placeholders = implode(',', array_fill(0, count($appIds), '?'));
        $sql = "DELETE FROM ".AnDb::TABLE_APPS." WHERE app_id IN ($placeholders)";
        return $this->lpdo->execute($sql, $appIds);
    }


    /**
     * @throws DateMalformedStringException
     */
    private function mapToAnApp(array $row): AnApp {
        return new AnApp(
            appId:      $row['app_id'],
            token:      $row['token'],
            enabled:    (bool)$row['enabled'],
            createdAt:  new DateTimeImmutable($row['created_at']),
            updatedAt:  new DateTimeImmutable($row['updated_at']),
        );
    }

}

