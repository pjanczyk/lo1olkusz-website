<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Model\ReplacementsRepository;

class ReplacementsController extends Controller
{
    public function index()
    {
        $this->page(0);
    }

    public function page($page)
    {
        $model = new ReplacementsRepository;

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
        $model = new ReplacementsRepository;
        $replacements = $model->getByClassAndDate($class, $date);

        if ($replacements != null) {
            $template = $this->includeTemplate('replacements_view');
            $template->replacements = $replacements;
            $template->render();
        }
    }
}