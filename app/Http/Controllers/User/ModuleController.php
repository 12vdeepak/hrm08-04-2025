<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function allholidays(){
        $holidays = Holiday::orderBy('id','DESC')->get();

        $events = [];

        foreach($holidays as $holiday){
            $events[] = [
                'title' => $holiday->occasion,
                'start' => $holiday->start_date,
                'end' => date("Y-m-d",strtotime("1 day", strtotime($holiday->end_date))),
            ];
        }
        return view('User.holidays',compact('holidays','events'));
    }


}
