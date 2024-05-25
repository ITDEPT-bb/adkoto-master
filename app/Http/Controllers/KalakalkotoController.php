<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KalakalkotoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Kalakalkoto/Home', [
            //
        ]);
    }

    public function view(): Response
    {
        return Inertia::render('Kalakalkoto/Product', [
            //
        ]);
    }
}
