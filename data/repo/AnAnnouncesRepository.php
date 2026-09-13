<?php


/**
 *  Репозиторий Анонсов
 */
class AnAnnouncesRepository extends IsAnRepository {

    /**
     * Вставка или обновление объявления
     */
    public function upsert(
        string $uuid,
        string $applicationId,
        string $title,
        string $body,
        ?DateTimeImmutable $startAt,
        ?DateTimeImmutable $endAt,
        int $status,
    ): bool {
        $sql = "INSERT INTO " . AnDb::TABLE_ANNOUNCES . " 
                (uuid, application_id, title, body, start_at, end_at, status, created_at, updated_at)
                VALUES (:uuid, :application_id, :title, :body, :start_at, :end_at, :status, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    application_id = VALUES(application_id),
                    title = VALUES(title),
                    body = VALUES(body),
                    start_at = VALUES(start_at),
                    end_at = VALUES(end_at),
                    status = VALUES(status),
                    updated_at = NOW()";

        return $this->db->db->execute($sql, [
            ':uuid' => $uuid,
            ':application_id' => $applicationId,
            ':title' => $title,
            ':body' => $body,
            ':start_at' => $startAt?->format('Y-m-d H:i:s'),
            ':end_at' => $endAt?->format('Y-m-d H:i:s'),
            ':status' => $status,
        ]);
    }

    /**
     * Получение объявления по UUID
     */
    public function getByUuid(string $uuid): ?AnAnnounce {
        $sql = "SELECT * FROM " . AnDb::TABLE_ANNOUNCES . " WHERE uuid = :uuid LIMIT 1";
        
        $row = $this->db->db->fetch($sql, [':uuid' => $uuid]);
        
        if (!$row) {
            return null;
        }

        return new AnAnnounce(
            uuid: $row['uuid'],
            applicationId: $row['application_id'],
            title: $row['title'],
            body: $row['body'],
            startAt: $row['start_at'] ? new DateTimeImmutable($row['start_at']) : null,
            endAt: $row['end_at'] ? new DateTimeImmutable($row['end_at']) : null,
            status: AnAnnounceStatus::from((int)$row['status']),
            createdAt: new DateTimeImmutable($row['created_at']),
            updatedAt: new DateTimeImmutable($row['updated_at']),
        );
    }

    /**
     * Получение списка объявлений
     */
    public function getList(?string $applicationId = null, ?int $status = null, int $limit = 100, int $offset = 0): array {
        $conditions = [];
        $params = [];

        if ($applicationId !== null) {
            $conditions[] = 'application_id = :application_id';
            $params[':application_id'] = $applicationId;
        }

        if ($status !== null) {
            $conditions[] = 'status = :status';
            $params[':status'] = $status;
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_ANNOUNCES . " 
                {$whereClause}
                ORDER BY created_at DESC
                LIMIT :offset, :limit";

        $params[':offset'] = $offset;
        $params[':limit'] = $limit;

        $rows = $this->db->db->fetchAll($sql, $params);

        $result = [];
        foreach ($rows as $row) {
            $result[] = new AnAnnounce(
                uuid: $row['uuid'],
                applicationId: $row['application_id'],
                title: $row['title'],
                body: $row['body'],
                startAt: $row['start_at'] ? new DateTimeImmutable($row['start_at']) : null,
                endAt: $row['end_at'] ? new DateTimeImmutable($row['end_at']) : null,
                status: AnAnnounceStatus::from((int)$row['status']),
                createdAt: new DateTimeImmutable($row['created_at']),
                updatedAt: new DateTimeImmutable($row['updated_at']),
            );
        }

        return $result;
    }

}