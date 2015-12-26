<?php

namespace pjanczyk\lo1olkusz\Controller\Dashboard;

use pjanczyk\Framework\Auth;
use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Model\ReplacementsRepository;

class ReplacementController extends Controller
{
    public function __construct()
    {
        Auth::requireSSL();
    }

    public function index()
    {
        $repo = new ReplacementsRepository;

        $template = $this->includeTemplate('dashboard/replacements_list');

        $replacements = $repo->listAll();

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
            $template = $this->includeTemplate('dashboard/replacements_view');
            $template->replacements = $replacements;
            $template->render();
        }
    }
}