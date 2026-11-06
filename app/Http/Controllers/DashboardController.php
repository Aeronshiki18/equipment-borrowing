<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;

class DashboardController extends Controller {
    public function index(Request $request) {
        // Data para sa Chart (Bilang ng requests per status)
        $counts = BorrowRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total','status');
        $total = BorrowRequest::count();

        // Data para sa Monthly Chart (Requests per month)
        $monthly = BorrowRequest::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')->orderBy('month')->get();

        return view('dashboard', compact('counts', 'total', 'monthly'));
    }
}