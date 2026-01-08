<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track(Request $request)
    {
        // إذا المستخدم اختار طلب معين
        if ($request->has('order_id') && $request->has('phone')) {
            $order = Order::with('orderItems.item')
                ->where('id', $request->order_id)
                ->where('customer_phone', $request->phone)
                ->first();

            if (!$order) {
                return redirect()->route('orders.track')->with('error', 'ما لقينا الطلب! حاول تدخل رقم جوالك مرة ثانية.');
            }

            return view('orders.show', compact('order'));
        }

        // إذا المستخدم دخل رقم جواله بس
        if ($request->has('phone')) {
            $orders = Order::where('customer_phone', $request->phone)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                return redirect()->back()->with('error', 'ما لقينا أي طلبات لهذا الرقم يا الغالي.');
            }

            return view('orders.track', compact('orders'))->with('phone', $request->phone);
        }

        return view('orders.track');
    }
}
