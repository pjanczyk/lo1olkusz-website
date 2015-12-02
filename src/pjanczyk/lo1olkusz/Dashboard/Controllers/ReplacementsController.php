<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Models\ReplacementsModel;

class ReplacementsController extends Controller
{
    public function index()
    {
        $this->page(0);
    }

    public function page($page)
    {
        $model = new ReplacementsModel;

        //$count = $model->count();

        $template = $this->includeTemplate('replacements_list');

        $replacements = $model->listAll();

        $transposed = [];

        foreach ($replacements as $r) {
            if (!isset($transposed[$r->date])) {
                $transposed[$r->date] = [];
            }

            $transposed[$r->date][] = $r;
        }

        $template->transposed = $transposed;
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