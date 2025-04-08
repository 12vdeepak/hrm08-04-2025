<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function super_admin_dashboard(){
        return view('Super_Admin.dashboard');
    }
}
