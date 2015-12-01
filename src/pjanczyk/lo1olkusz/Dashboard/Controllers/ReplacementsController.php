<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Models\ReplacementsModel;

class ReplacementsController extends Controller
{
    public function index()
    {
        $model = new ReplacementsModel;

        $template = $this->includeTemplate('replacements_list');
        $template->replacements = $model->listAll();
        $template->render();
    }

    public function view($date, $class) {
        $model = new ReplacementsModel;
        $replacements = $model->get($class, $date);

        if ($replacements != null) {
            $template = $this->includeTemplate('replacements_view');
            $template->replacements = $replacements;
            $template->render();
        }
    }
}