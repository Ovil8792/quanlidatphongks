<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistical;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tổng thu nhập theo ngày/tuần/tháng/năm
        $today = now();
        $revenueDay = Statistical::whereDate('date', $today->toDateString())->sum('total_revenue');
        $yesterday = $today->copy()->subDay();
        $revenueDayPrev = Statistical::whereDate('date', $yesterday->toDateString())->sum('total_revenue');

        $startOfWeek = now()->copy()->startOfWeek();
        $endOfWeek = now()->copy()->endOfWeek();
        $revenueWeek = Statistical::whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->sum('total_revenue');
        $prevWeekStart = $startOfWeek->copy()->subWeek();
        $prevWeekEnd = $endOfWeek->copy()->subWeek();
        $revenueWeekPrev = Statistical::whereBetween('date', [$prevWeekStart->toDateString(), $prevWeekEnd->toDateString()])
            ->sum('total_revenue');

        $revenueMonth = Statistical::whereYear('date', $today->year)
            ->whereMonth('date', $today->month)
            ->sum('total_revenue');
        $lastMonth = $today->copy()->subMonth();
        $revenueMonthPrev = Statistical::whereYear('date', $lastMonth->year)
            ->whereMonth('date', $lastMonth->month)
            ->sum('total_revenue');

        $revenueYear = Statistical::whereYear('date', $today->year)
            ->sum('total_revenue');
        $revenueYearPrev = Statistical::whereYear('date', $today->copy()->subYear()->year)
            ->sum('total_revenue');

        // Tính % tăng/giảm
        $calcGrowth = function ($current, $prev) {
            if ($prev == 0) {
                if ($current == 0) return 0.0;
                return 100.0; // từ 0 lên >0 coi như +100%
            }
            return (($current - $prev) / $prev) * 100.0;
        };

        $growthDay = $calcGrowth($revenueDay, $revenueDayPrev);
        $growthWeek = $calcGrowth($revenueWeek, $revenueWeekPrev);
        $growthMonth = $calcGrowth($revenueMonth, $revenueMonthPrev);
        $growthYear = $calcGrowth($revenueYear, $revenueYearPrev);

        // Chuỗi 14 ngày gần nhất cho sparkline
        $labels14 = [];
        $values14 = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = now()->copy()->subDays($i);
            $labels14[] = $d->format('d/m');
            $values14[] = (int) Statistical::whereDate('date', $d->toDateString())->sum('total_revenue');
        }

        return view(
            "admin.statistical.index",
            compact(
                'revenueDay', 'revenueWeek', 'revenueMonth', 'revenueYear',
                'growthDay', 'growthWeek', 'growthMonth', 'growthYear',
                'labels14', 'values14'
            )
        );
    }

    public function getSampleData()
    {
        $chart_data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->copy()->subDays($i);
            $chart_data[] = [
                'date' => $date->toDateString(),
                'day' => $date->format('d/m'),
                'total_bookings' => rand(20, 100),
                'total_customers' => rand(30, 150),
                'total_revenue' => rand(500000, 2000000),
                'rooms_occupied' => rand(10, 50),
            ];
        }
        return $chart_data;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function filter(Request $request)
    {
        $data = $request->all();
        $from = $data['from'] ?? '';
        $to = $data['to'] ?? '';
        
        // If no dates provided, get sample data
        if (empty($from) || empty($to)) {
            $chart_data = $this->getSampleData();
        } else {
            // Convert date format from dd/mm/yyyy to yyyy-mm-dd
            $fromFormatted = $this->convertDateFormat($from);
            $toFormatted = $this->convertDateFormat($to);
            
            $getdata = Statistical::whereBetween('date', [$fromFormatted, $toFormatted])->orderBy('date', 'asc')->get();
            
            if ($getdata->isEmpty()) {
                // If no data found, return sample data
                $chart_data = $this->getSampleData();
            } else {
                $chart_data = [];
                foreach ($getdata as $item) {
                    $chart_data[] = [
                        'date' => $item->date,
                        'day' => date('d/m', strtotime($item->date)),
                        'total_bookings' => $item->total_bookings,
                        'total_customers' => $item->total_customers,
                        'total_revenue' => $item->total_revenue,
                        'rooms_occupied' => $item->rooms_occupied,
                    ];
                }
            }
        }
        
        return response()->json($chart_data);
    }
    
    // getSampleData() không còn sử dụng cho UI hiện tại
    
    private function convertDateFormat($dateString)
    {
        if (empty($dateString)) return '';
        $parts = explode('/', $dateString);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return $dateString;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Statistical $statistical)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statistical $statistical)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statistical $statistical)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statistical $statistical)
    {
        //
    }
}
