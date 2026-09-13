<?php

namespace app_notifier\data\repo;

use app_notifier\data\db\AnDb;

/**
 *  Интерфейс репозитория
 */
abstract class IsAnRepository {
    protected AnDb $db;

    public function __construct() {
        $this->db = AnDb::instance();
    }

}

