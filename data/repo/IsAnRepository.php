<?php

/**
 *  Интерфейс репозитория
 */
abstract class IsAnRepository {
    protected AnDb $db;

    public function __construct() {
        $this->db = AnDb::instance();
    }

}

