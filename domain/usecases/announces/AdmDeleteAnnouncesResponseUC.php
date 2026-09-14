<?php

/**
 *  Удаление анонсов
 *
 * **На входе в дата**:
 * ```
 * {
 *      "uuids" : []     // список UUID анонсов для удаления
 * }
 * ```
 *
 * **На выходе**
 * ```
 * {
 *      "removed": 99,
 * }
 * ```
 */
class AdmDeleteAnnouncesResponseUC extends IsAdmAnResponseUC {
    private AnAnnouncesRepository $repo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnAnnouncesRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {
        // Начальные данные
        $announceIds = $this->assertData('uuids', 'array');

        // Удаление анонсов
        $removed = $this->repo->deleteByUuids(array_unique($announceIds));

        return new AnApiResponse(true, [
            "removed" => $removed,
        ]);
    }

}
