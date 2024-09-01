<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\LegalCase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dashboard', ['only' => ['__invoke']]);
    }

    public function __invoke()
    {
        $countLegal = LegalCase::count();
        $countMonthNowLegal = LegalCase::whereMonth('trial_date', date('m'))->count();
        $countLegalGoing = LegalCase::whereDate('trial_date', '>', date('Y-m-d'))->count();
        return view('backEnd.dashboard.index', compact('countLegal', 'countMonthNowLegal', 'countLegalGoing'));
    }
}
