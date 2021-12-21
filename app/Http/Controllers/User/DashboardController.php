<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LinkedAccounts;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    function index()
    {
        return view('user.dashboard', DashboardController::getDashboardData());
    }

    private static function getDashboardData()
    {
        $user = Auth::user();
        $lnkedAccountsCount = LinkedAccounts::where('user_id', $user->id)->count() ?? 0;
        $totalReports = Report::where('user_id', $user->id)->count() ?? 0;
        $accountLevel = "";

        $modulesActive = 0; /* get from dabatase later */

        switch($modulesActive)
        {
            case 1: $accountLevel = "Free";
                    break;
            case 2: $accountLevel = "Pro";
                    break;
            case 3: $accountLevel = "Enterprise";
                    break;
            default:  $accountLevel = "Jimmy";
        }

        $data = ["linkedAccountsCount"=>$lnkedAccountsCount, "totalReports"=>$totalReports, "accountLevel"=>$accountLevel];
        return $data;
    }
}
