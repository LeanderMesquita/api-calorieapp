<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Entry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function report(Request $request)
    {

        $lastWeek = Carbon::now()->subWeek();
        $fortNightAgo = Carbon::now()->subWeek(2);

        $caloriesSum = Entry::whereBetween('created_at', [$lastWeek, now()])->sum('calories');
        $lastCaloriesSum = Entry::whereBetween('created_at', [$fortNightAgo, $lastWeek])->sum('calories');

        $users = User::count();
        $lastWeekUsers = User::whereDate('created_at', '<=', $lastWeek)->count();

        $averageCalories = $caloriesSum / $users;
        $lastAverage = $lastCaloriesSum / $lastWeekUsers;

        $newEntrys = Entry::whereBetween('created_at', [$lastWeek, now()])->count();


        $report = new ReportResource([
            'average_calories' => $averageCalories,
            'new_entries' => $newEntrys,
            'last_average' => $lastAverage
        ]);

        return response()->json($report, 200);
    }
}
