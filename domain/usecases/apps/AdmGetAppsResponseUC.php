<?php

/**
 *  Получение списка приложений
 *
 *  **На входе в дата**:
 *  ```
 *  {}
 *  ```
 *
 * **На выходе**
 * ```
 * {
 *      "apps" : []
 * }
 * ```
 */
class AdmGetAppsResponseUC extends IsAdmAnResponseUC {
    private AnAppsRepository $repo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnAppsRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {

        $apps = $this->repo->getList();

        return new AnApiResponse(true, [
            "apps" => AnDtoMapper::appsToDto($apps),
        ]);

    }

}