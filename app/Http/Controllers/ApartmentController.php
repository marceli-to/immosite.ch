<?php

namespace App\Http\Controllers;

use App\Apartments\ApartmentRepository;

class ApartmentController extends Controller
{
    public function __invoke(ApartmentRepository $repository)
    {
        $apartments = $repository->all();

        return view('pages.apartments', [
            'groups' => $repository->groups($apartments),
            'filters' => $repository->filterOptions($apartments),
            'repository' => $repository,
        ]);
    }
}
