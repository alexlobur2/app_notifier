<?php


/**
 *  Абстракция: Use Case ответа для админа с проверкой доступа
 */
abstract class IsAdmAnResponseUC extends IsAnResponseUC {

    /**
     * @throws AnApiException
     */
    public function __construct() {
        if(!$this->isAdmin){
            throw new AnApiException(AnApiException::API_FORBIDDEN, "Admin only");
        }
    }


}