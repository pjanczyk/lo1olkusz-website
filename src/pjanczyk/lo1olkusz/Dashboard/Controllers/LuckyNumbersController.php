<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Model\LuckyNumbersModel;
use pjanczyk\MVC\Controller;

class LuckyNumbersController extends Controller
{

    public function index()
    {
        $model = new LuckyNumbersModel($this->db);

        $template = $this->includeTemplate('ln_list');
        $template->lns = $model->getAll([LuckyNumbersModel::FIELD_DATE,
            LuckyNumbersModel::FIELD_VALUE, LuckyNumbersModel::FIELD_LAST_MODIFIED]);
        $template->render();
    }
}
