<?php

namespace pjanczyk\lo1olkusz\Dashboard;

use pjanczyk\lo1olkusz\LuckyNumbersTable;

class LuckyNumbersController extends Controller {

    public function index() {
        $model = new LuckyNumbersTable($this->db);
        global $lns;
        $lns = $model->getAll([LuckyNumbersTable::FIELD_DATE,
            LuckyNumbersTable::FIELD_VALUE, LuckyNumbersTable::FIELD_LAST_MODIFIED]);
        include 'views/ln_list.php';
    }
}
