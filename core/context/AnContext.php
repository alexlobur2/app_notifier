<?php

/**
 *  Текущий контекст
 */
class AnContext {
    private static self $_instance;
    public static function instance(): self { return self::$_instance ??= new self(); }
    private function __construct() {}


    /** Текущий запрос */
    public readonly AnApiRequest $request;

    /** Текущее приложение */
    public readonly AnApp $app;

    /** Является ли админом */
    public readonly bool $isAdmin;


    /**
     *  Инициализация
     *  @throws AnApiException
     */
    public function init(AnApiRequest $request): self {
        $this->request = $request;

        // получение текущего приложения
        $app = new GetAppByTokenUC()->execute($request->token);
        if(is_null($app)) throw new AnApiException(AnApiException::API_UNAUTHORIZED); // не авторизован
        $this->app = $app;

        // Является ли админом
        $this->isAdmin = hash_equals(AnConfig::ADMIN_APP_ID, $this->app->appId);

        return self::$_instance;
    }

}
