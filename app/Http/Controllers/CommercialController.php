<?php

namespace App\Http\Controllers;

use App\Apartments\ApartmentRepository;

class CommercialController extends Controller
{
    public function __invoke(ApartmentRepository $repository)
    {
        $class = config('commercial.sources.'.config('commercial.source'));

        if (! $class || ! class_exists($class)) {
            throw new \RuntimeException("Commercial source '".config('commercial.source')."' is not installed (config/commercial.php).");
        }

        return view('pages.commercial', [
            'objects' => collect(app($class)->all()),
            'repository' => $repository,
        ]);
    }
}
