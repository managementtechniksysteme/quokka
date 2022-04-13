<?php

namespace App\Http\Controllers;

use App\Models\Accounting;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $a = Accounting::create([
            'employee_id' => 2,
            'project_id' => 1,
            'service_id' => 6,
            'service_provided_on' => '2022-04-13',
            'service_provided_started_at' => '08:00',
            'service_provided_ended_at' => '09:30',
            'amount' => 1.5,
            'comment' => 'Programmierung Quokka'
        ]);

        return view('home');
    }
}
