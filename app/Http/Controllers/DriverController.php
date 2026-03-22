<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $trips = [
            (object)[
                'from' => 'Cần Thơ',
                'to' => 'TP.HCM',
                'start_time' => '08:00',
                'status' => 'delivering',
                'fuel' => null,
                'vehicle' => (object)['plate_number' => '51A-12345']
            ],
        ];

        return view('client.index', compact('trips'));
    }
}
