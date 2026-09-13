<?php


/**
 *  Репозиторий Устройств
 */
class AnDevicesRepository extends IsAnRepository {

    /**
     * Вставка или обновление устройства
     * @throws Exception
     */
    public function upsert(
        string $applicationId,
        string $deviceFpt,
        DateTimeInterface $firstSeenAt,
        DateTimeInterface $lastSeenAt,
        int $requestCount,
    ): bool {
        $sql = "INSERT INTO " . AnDb::TABLE_DEVICES . " 
                (application_id, device_fpt, first_seen_at, last_seen_at, request_count, created_at, updated_at)
                VALUES (:application_id, :device_fpt, :first_seen_at, :last_seen_at, :request_count, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    last_seen_at = VALUES(last_seen_at),
                    request_count = request_count + VALUES(request_count),
                    updated_at = NOW()";

        $count = $this->lpdo->execute($sql, [
            ':application_id'   => $applicationId,
            ':device_fpt'       => $deviceFpt,
            ':first_seen_at'    => $this->date2Iso($firstSeenAt),
            ':last_seen_at'     => $this->date2Iso($lastSeenAt),
            ':request_count'    => $requestCount,
        ]);
        $this->throwOnDbError(); // кидаем исключение при ошибке БД
        return $count;
    }


    /**
     * Получение списка устройств
     * @throws Exception
     */
    public function getList(?string $applicationId = null, int $limit = 100, int $offset = 0): array {

        // sql
        $conditions = [];
        if ($applicationId !== null) $conditions[] = 'application_id = :application_id';
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_DEVICES . " 
                {$whereClause}
                ORDER BY last_seen_at DESC
                LIMIT :offset, :limit";

        $params = [
            ':application_id' => $applicationId,
            ':offset' => $offset,
            ':limit' => $limit,
        ];

        // Запрос
        $rows = $this->lpdo->exec2array($sql, $params);
        $this->throwOnDbError(); // кидаем исключение при ошибке БД

        return array_map([$this, 'mapToAnDevice'], $rows);
    }


    /**
     * @throws DateMalformedStringException
     */
    private function mapToAnDevice(array $row): AnDevice {
        return new AnDevice(
            applicationId:  $row['application_id'],
            deviceFpt:      $row['device_fpt'],
            firstSeenAt:    new DateTimeImmutable($row['first_seen_at']),
            lastSeenAt:     new DateTimeImmutable($row['last_seen_at']),
            requestCount:   $row['request_count'],
        );
    }


}

