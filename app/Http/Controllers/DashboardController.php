<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
