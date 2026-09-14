<?php

/**
 *  Удаление приложений
 *
 * **На входе в дата**:
 * ```
 * {
 *      "apps_ids" : []     // список Id анонсов для удаления
 * }
 * ```
 *
 * **На выходе**
 * ```
 * {
 *      "removed": 2,
 * }
 * ```
 */
class AdmDeleteAppsResponseUC extends IsAdmAnResponseUC {
    private AnAppsRepository $repo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnAppsRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {
        // Начальные данные
        $ids = $this->assertData('apps_ids', 'array');

        // Удаление
        $removed = $this->repo->deleteByIds(array_unique($ids));

        return new AnApiResponse(true, [
            "removed" => $removed,
        ]);
    }

}
