<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Models\LuckyNumbersModel;
use pjanczyk\MVC\Controller;

class LuckyNumbersController extends Controller {

    public function index() {
        $model = new LuckyNumbersModel($this->db);
        global $lns;
        $lns = $model->getAll([LuckyNumbersModel::FIELD_DATE,
            LuckyNumbersModel::FIELD_VALUE, LuckyNumbersModel::FIELD_LAST_MODIFIED]);
        include 'views/ln_list.php';
    }
}
