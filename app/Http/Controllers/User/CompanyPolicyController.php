<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\CompanyPolicy;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    public function view_company_policy(){
        $company_policy = CompanyPolicy::find(1);
        return view('User.company_policy',compact('company_policy'));
    }
}
