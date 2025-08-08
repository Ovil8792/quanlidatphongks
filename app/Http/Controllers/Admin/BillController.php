<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\DetailedBill;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchId = $request->query('search_id');
        $query = Bill::with('user');
        if (!empty($searchId)) {
            $query->where('id', (int) $searchId);
        }
        $bills = $query->get();
        return view('admin.bill.index', compact('bills', 'searchId'));
    }
    public function updateStatus(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        $newStatus = $request->input('status');

        $bill->status = $newStatus;

        // Nếu trạng thái là 'paid' thì set ngày thanh toán = hiện tại
        if ($newStatus === 'paid' && !$bill->payment_date) {
            $bill->payment_date = now();
        }

        // Nếu bị chuyển sang trạng thái khác thì reset lại payment_date
        if ($newStatus !== 'paid') {
            $bill->payment_date = null;
        }

        $bill->save();

        return redirect()->route('admin.bills.index')->with('success', 'Cập nhật trạng thái thành công.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Bill $bill)
    {
        $bill->load(['user', 'details.room.category']); // ⚠️ Quan trọng: load nested relationships
        return view('admin.bill.detailedbill', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
