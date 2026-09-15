<?php
declare(strict_types=1);

/**
 *  Абстракция: Use Case ответа
 */
abstract class IsAnResponseUC {

    protected AnContext $context{
        get => AnContext::instance();
    }

    protected AnApp $app{
        get => AnContext::instance()->app;
    }

    protected bool $isAdmin{
        get => AnContext::instance()->isAdmin;
    }

    protected array $data{
        get => AnContext::instance()->request->data;
    }


    /**
     *  Запуск
     *  @return AnApiResponse
     */
    public abstract function execute(): AnApiResponse;


    /**
     *  Получение значения из data
     *
     *  @param string $name
     *  @param "string"|"num" $type
     *  @param bool $allowNull
     *  @return mixed
     *  @throws AnApiException
     */
    protected function assertData(string $name, string $type, bool $allowNull = false): mixed {
        $value = $this->data[$name]??null;
        // Проверка на null
        if(is_null($value)){
            if($allowNull) return null;
            throw new AnApiException(AnApiException::API_BAD_REQUEST, "Null or Empty parameter: <$type> $name");
        }
        // Проверка типов
        $type = trim(strtolower($type));
        if(
            ($type == "string" and !is_string($value)) ||
            ($type == "float" and !is_float($value) and !is_int($value)) ||
            ($type == "int" and !is_int($value)) ||
            ($type == "bool" and !is_bool($value)) ||
            ($type == "array" and !is_array($value)) ||
            ($type == "announce_status" and AnAnnounceStatus::tryFrom($value)==null)
        ){
            throw new AnApiException(AnApiException::API_BAD_REQUEST, "Wrong parameter: <$type> $name = $value");
        }
        return $value;
    }

}
