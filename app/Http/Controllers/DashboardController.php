<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\School;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch School vs Category Data (Monthly)
        $schoolCategoryData = Ticket::selectRaw('schools.name as school_name, categories.name as category_name, COUNT(*) as count')
        ->join('schools', 'tickets.school_id', '=', 'schools.id')
        ->join('categories', 'tickets.category_id', '=', 'categories.id')
        ->groupBy('schools.name', 'categories.name')
        ->get();
    
    
        // Fetch Pending Tickets Per Person (Monthly)
        $pendingTicketsData = Ticket::selectRaw('MONTH(tickets.created_at) as month, users.name as assigned_to, COUNT(*) as count')
            ->join('users', 'tickets.assigned_to', '=', 'users.id')
            ->where('tickets.status', 'open')
            ->groupByRaw('month, users.name')
            ->orderBy('month')
            ->get();

        // Fetch Resolved Tickets Per Person (Monthly)
        $resolvedTicketsData = Ticket::selectRaw('MONTH(tickets.created_at) as month, users.name as assigned_to, COUNT(*) as count')
            ->join('users', 'tickets.assigned_to', '=', 'users.id')
            ->where('tickets.status', 'closed')
            ->groupByRaw('month, users.name')
            ->orderBy('month')
            ->get();


        return view('dashboard.index', compact('schoolCategoryData', 'pendingTicketsData', 'resolvedTicketsData'));
    }

    public function filter(Request $request)
{
    try {
        $query = Ticket::query();

        if ($request->has('start_month') && $request->has('end_month')) {
            $startMonth = date('m', strtotime($request->start_month));
            $endMonth = date('m', strtotime($request->end_month));
            $query->whereRaw('MONTH(created_at) BETWEEN ? AND ?', [$startMonth, $endMonth]);
        }

        // Process Filtered Data
        $filteredData = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($filteredData);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}

}
