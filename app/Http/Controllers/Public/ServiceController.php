<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('public/Services', [
            'services' => Service::active()->get(),
        ]);
    }
}
