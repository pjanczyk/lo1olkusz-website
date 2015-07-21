<?php

namespace pjanczyk\lo1olkusz\Dashboard\Pages;

use pjanczyk\framework\Page;
use pjanczyk\lo1olkusz\Model\LuckyNumbersModel;

class LuckyNumbersPage extends Page
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
