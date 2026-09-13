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
        ?DateTimeInterface $startAt,
        ?DateTimeInterface $endAt,
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
            ':uuid'     => $uuid,
            ':application_id' => $applicationId,
            ':title'    => $title,
            ':body'     => $body,
            ':start_at' => $this->date2Iso($startAt),
            ':end_at'   => $this->date2Iso($endAt),
            ':status'   => $status,
        ]);
    }


    /**
     *  Получение объявления по UUID
     *  @throws Exception
     */
    public function getByUuid(string $uuid): ?AnAnnounce {
        $row = $this->db->db->exec2row(
            "SELECT * FROM " . AnDb::TABLE_ANNOUNCES . " WHERE uuid = :uuid LIMIT 1",
            [ ':uuid' => $uuid ]
        );
        return !$row ? null : $this->mapToAnAnnounce($row);
    }


    /**
     *  Получение списка объявлений
     *  @throws Exception
     */
    public function getList(?string $applicationId = null, ?int $status = null, int $limit = 100, int $offset = 0): array {

        // Начальные данные
        $conditions = [];
        if ($applicationId !== null) $conditions[] = 'application_id = :application_id';
        if ($status !== null) $conditions[] = 'status = :status';
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM " . AnDb::TABLE_ANNOUNCES . " 
            {$whereClause}
            ORDER BY created_at DESC
            LIMIT :offset, :limit";

        $params = [
            ':status' => $status,
            ':application_id' => $applicationId,
            ':offset' => $offset,
            ':limit' => $limit
        ];

        // Получение данных из БД
        $rows = $this->db->db->exec2array($sql, $params);
        $this->throwOnDbError(); // кидаем исключение при ошибке

        // Формируем результат
        return array_map( function($row){ return $this->mapToAnAnnounce($row); }, $rows );
    }


    /**
     *  Маппер
     *  @throws DateMalformedStringException
     */
    private function mapToAnAnnounce(array $data): AnAnnounce {
        return new AnAnnounce(
            uuid:           $data['uuid'],
            applicationId:  $data['application_id'],
            title:          $data['title'],
            body:           $data['body'],
            startAt:        $data['start_at'] ? new DateTimeImmutable($data['start_at']) : null,
            endAt:          $data['end_at'] ? new DateTimeImmutable($data['end_at']) : null,
            status:         AnAnnounceStatus::from((int)$data['status']),
            createdAt:      new DateTimeImmutable($data['created_at']),
            updatedAt:      new DateTimeImmutable($data['updated_at']),
        );
    }

}