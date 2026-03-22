<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $trips = [
            (object) [
                'from' => 'Cần Thơ',
                'to' => 'TP.HCM',
                'start_time' => '08:00',
                'status' => 'delivering',
                'fuel' => null,
                'vehicle' => (object) ['plate_number' => '51A-12345']
            ],
        ];

        return view('driver.pages.index', compact('trips'));
    }
}
