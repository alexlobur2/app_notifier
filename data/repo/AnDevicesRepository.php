<?php

/**
 *  Репозиторий Устройств
 */
class AnDevicesRepository extends IsAnRepository {

    /**
     * Вставка или обновление устройства
     */
    public function upsert(
        string $applicationId,
        string $deviceFpt,
        DateTimeImmutable $firstSeenAt,
        DateTimeImmutable $lastSeenAt,
        int $requestCount,
    ): bool {
        $sql = "INSERT INTO " . AnDb::TABLE_DEVICES . " 
                (application_id, device_fpt, first_seen_at, last_seen_at, request_count, created_at, updated_at)
                VALUES (:application_id, :device_fpt, :first_seen_at, :last_seen_at, :request_count, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    last_seen_at = VALUES(last_seen_at),
                    request_count = request_count + VALUES(request_count),
                    updated_at = NOW()";

        return $this->db->db->execute($sql, [
            ':application_id' => $applicationId,
            ':device_fpt' => $deviceFpt,
            ':first_seen_at' => $firstSeenAt->format('Y-m-d H:i:s'),
            ':last_seen_at' => $lastSeenAt->format('Y-m-d H:i:s'),
            ':request_count' => $requestCount,
        ]);
    }

    /**
     * Получение списка устройств
     */
    public function getList(?string $applicationId = null, int $limit = 100, int $offset = 0): array {
        $conditions = [];
        $params = [];

        if ($applicationId !== null) {
            $conditions[] = 'application_id = :application_id';
            $params[':application_id'] = $applicationId;
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_DEVICES . " 
                {$whereClause}
                ORDER BY last_seen_at DESC
                LIMIT :offset, :limit";

        $params[':offset'] = $offset;
        $params[':limit'] = $limit;

        $rows = $this->db->db->fetchAll($sql, $params);

        $result = [];
        foreach ($rows as $row) {
            $result[] = new AnDevice(
                applicationId: $row['application_id'],
                deviceFpt: $row['device_fpt'],
                firstSeenAt: new DateTimeImmutable($row['first_seen_at']),
                lastSeenAt: new DateTimeImmutable($row['last_seen_at']),
                requestCount: (int)$row['request_count'],
            );
        }

        return $result;
    }

}

