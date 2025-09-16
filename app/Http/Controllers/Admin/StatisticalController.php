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
        // Get sample data for the chart
        $chartData = $this->getSampleData();
        return view("admin.statistical.index", compact('chartData'));
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
    
    private function getSampleData()
    {
        $sampleData = [];
        $startDate = now()->subDays(29); // Last 30 days
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $sampleData[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d/m'),
                'total_bookings' => rand(5, 25),
                'total_customers' => rand(8, 35),
                'total_revenue' => rand(1000000, 5000000),
                'rooms_occupied' => rand(10, 40),
            ];
        }
        
        return $sampleData;
    }
    
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
