<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set default date range if not provided
        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subMonths(3);
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Sales summary
        $salesSummary = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            ')
            ->first();

        // Sales by status
        $salesByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('status')
            ->get();

        // Top products
        $topProducts = Product::withCount(['orderItems as units_sold' => function($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(quantity), 0)'))
                    ->whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
            }])
            ->withSum(['orderItems as revenue' => function($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(total_price), 0)'))
                    ->whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
            }])
            ->orderBy('units_sold', 'desc')
            ->limit(10)
            ->get();

        // Monthly sales data for chart
        $monthlySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue
            ')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.reports.sales', compact(
            'salesSummary',
            'salesByStatus',
            'topProducts',
            'monthlySales',
            'startDate',
            'endDate',
            'period'
        ));
    }
}