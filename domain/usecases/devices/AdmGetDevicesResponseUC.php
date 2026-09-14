<?php

/**
 * Получение списка устройств.
 *
 * **На входе в дата**:
 * ```
 * {
 *      "apps_ids": [],
 *      "pagination": {
 *          "offset": 0,
 *          "limit" : 100,
 *          "order" : "field",
 *          "desc"  : false,
 *      }
 * }
 * ```
 *
 * **На выходе**
 * ```
 * {
 *      "devices" : []
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
class AdmGetDevicesResponseUC extends IsAdmAnResponseUC {
    private AnDevicesRepository $repo;

    public function __construct() {
        parent::__construct();
        $this->repo = new AnDevicesRepository();
    }


    /**
     * @throws Exception
     */
    public function execute(): AnApiResponse {
        // Начальные данные
        $appsIds = $this->assertData('apps_ids', 'array', true) ?? [];
        $pagination = AnPagination::fromArray($this->data['pagination']);

        // Получение списка устройств
        $opts = new AnDevicesRepositoryOpts(
            appsIds:    $appsIds,
            limit:      $pagination->limit,
            offset:     $pagination->offset,
            order:      $pagination->order,
            desc:       $pagination->desc
        );
        $devices = $this->repo->getList($opts);
        $pagination->total = $this->repo->count($opts);

        return new AnApiResponse(true, [
            "devices"    => AnDtoMapper::devicesToDto($devices),
            "pagination" => $pagination
        ]);

    }

}