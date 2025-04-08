<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceData implements FromView
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('pdfs.employee-monthly-attendance-record', ['data' => $this->data]);
    }
}
