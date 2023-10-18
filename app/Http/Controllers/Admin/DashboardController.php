<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

/**
 * DashboardController
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('Admin/Dashboard', []);
    }

}
