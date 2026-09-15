<?php


/**
 *  Репозиторий Анонсов
 */
class AnAnnouncesRepository extends IsAnRepository {

    /**
     * Вставка или обновление объявления
     */
    public function upsert(AnAnnounce $item): void {
        $sql = "INSERT INTO ".AnDb::TABLE_ANNOUNCES." 
            (uuid, app_id, title, body, start_at, end_at, status, created_at, updated_at)
            VALUES (:uuid, :app_id, :title, :body, :start_at, :end_at, :status, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                app_id = VALUES(app_id),
                title = VALUES(title),
                body = VALUES(body),
                start_at = VALUES(start_at),
                end_at = VALUES(end_at),
                status = VALUES(status),
                updated_at = NOW()";

        $this->lpdo->execute($sql, [
            ':uuid'     => $item->uuid,
            ':app_id'   => $item->appId,
            ':title'    => $item->title,
            ':body'     => $item->body,
            ':start_at' => AnUtils::date2Iso($item->startAt),
            ':end_at'   => AnUtils::date2Iso($item->endAt),
            ':status'   => $item->status->value,
        ]);
    }


    /**
     *  Получение объявления по UUID
     *  @throws Exception
     */
    public function getByUuid(string $uuid): ?AnAnnounce {
        $row = $this->lpdo->exec2row(
            "SELECT * FROM ".AnDb::TABLE_ANNOUNCES." WHERE uuid = :uuid LIMIT 1",
            [ ':uuid' => $uuid ]
        );
        return !$row ? null : $this->mapToAnAnnounce($row);
    }


    /**
     *  Получение списка объявлений
     *  @throws Exception
     */
    public function getList(AnAnnouncesRepositoryOpts $opts): array {
        // sql
        $sql = "SELECT * FROM ".AnDb::TABLE_ANNOUNCES." 
            ".$opts->where("WHERE")."
            ".$opts->orderBy()."
            ".$opts->limits();

        // Получение данных из БД
        $rows = $this->lpdo->query2array($sql);

        // Формируем результат
        return array_map( function($row){ return $this->mapToAnAnnounce($row); }, $rows );
    }


    /**
     *  Подсчет количества
     *  @throws Exception
     */
    public function count(AnAnnouncesRepositoryOpts $opts): int {
        return (int) $this->lpdo->query2val(
            "SELECT COUNT(*) FROM ".AnDb::TABLE_ANNOUNCES." ".$opts->where("WHERE")
        );
    }


    /**
     *  Удаление объявлений по UUID
     *  @throws Exception
     */
    public function deleteByIds(array $uuids): int {
        $placeholders = implode(',', array_fill(0, count($uuids), '?'));
        $sql = "DELETE FROM ".AnDb::TABLE_ANNOUNCES." WHERE uuid IN ($placeholders)";
        return $this->lpdo->execute($sql, $uuids);
    }


    /**
     *  Маппер
     *  @throws DateMalformedStringException
     */
    private function mapToAnAnnounce(array $data): AnAnnounce {
        return new AnAnnounce(
            uuid:       $data['uuid'],
            appId:      $data['app_id'],
            title:      $data['title'],
            body:       $data['body'],
            status:     AnAnnounceStatus::from((int)$data['status']),
            startAt:    $data['start_at'] ? new DateTimeImmutable($data['start_at']) : null,
            endAt:      $data['end_at'] ? new DateTimeImmutable($data['end_at']) : null,
            createdAt:  new DateTimeImmutable($data['created_at']),
            updatedAt:  new DateTimeImmutable($data['updated_at']),
        );
    }

}

