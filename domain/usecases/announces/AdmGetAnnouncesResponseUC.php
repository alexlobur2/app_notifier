<?php

/**
 *  Получение списка анонсов.
 *
 *  **На входе в дата**:
 *  ```
 *  {
 *      "apps_ids" : []     // список Id приложений для сужения фильтра
 *      "statuses" : [],
 *      "pagination": {
 *          "offset": 0,
 *          "limit" : 100,
 *          "order" : "field",
 *          "desc"  : false,s
 *      }
 *  }
 *  ```
 *
 * **На выходе**
 * ```
 * {
 *      "announces" : []
 *      "pagination": {
 *          "offset": 0,
 *          "limit" : 100,
 *          "order" : "field",
 *          "desc"  : false,
 *          "total" : 999
 *      }
 * }
 * ```
 */
class AdmGetAnnouncesResponseUC extends IsAdmAnResponseUC {
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
        $appsIds = $this->assertData('apps_ids', 'array', true) ?? [];
        $statuses = $this->assertData('statuses', 'array', true) ?? [];
        $pagination = AnPagination::fromArray($this->data['pagination']??null);

        // Получение списка
        $opts = new AnAnnouncesRepositoryOpts(
            appsIds:    $appsIds,
            statuses:   $statuses,
            limit:      $pagination->limit,
            offset:     $pagination->offset,
            order:      $pagination->order,
            desc:       $pagination->desc
        );
        $announces = $this->repo->getList($opts);
        $pagination->total = $this->repo->count($opts);

        return new AnApiResponse(true, [
            "announces" => AnDtoMapper::announcesToDto($announces),
            "pagination" => $pagination
        ]);

    }

}