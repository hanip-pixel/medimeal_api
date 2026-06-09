<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $orderCode = 'ORD' . time() . rand(100, 999);
        
        $id = DB::table('orders')->insertGetId([
            'order_code' => $orderCode,
            'user_id' => $request->user_id,
            'package_id' => $request->package_id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'delivery_address' => $request->address,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(), 
        ]);

        return response()->json([
            'success' => true, 
            'order_code' => $orderCode 
        ]);
    }

    public function myOrders(Request $request)
    {
        // Sementara ambil user_id dari parameter
        $userId = $request->query('user_id', 4);
        $orders = DB::table('orders')->where('user_id', $userId)->get();
        return response()->json($orders);
    }

    public function show($orderCode)
    {
        $order = DB::table('orders')->where('order_code', $orderCode)->first();
        return response()->json($order ?? ['status' => 'pending']);
    }

    public function updateStatus(Request $request, $orderCode)
    {
        DB::table('orders')->where('order_code', $orderCode)->update([
            'status' => $request->status
        ]);
        
        // Trigger WebSocket event
        // event(new OrderStatusUpdated($orderCode, $request->status));
        
        return response()->json(['success' => true]);
    }
}