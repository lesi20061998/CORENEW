<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        $products = \App\Models\Product::with('variants.attributeValues')->where('status', 'active')->get();
        $statuses = Order::$statuses;
        $paymentStatuses = Order::$paymentStatuses;

        return view('admin.orders.create', compact('products', 'statuses', 'paymentStatuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'street_address' => 'required|string',
            'province_name'  => 'required|string',
            'district_name'  => 'required|string',
            'ward_name'      => 'required|string',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        \DB::beginTransaction();
        try {
            $subtotal = 0;
            $shippingFee = $request->shipping_fee ?? 30000;

            $fullAddress = implode(', ', array_filter([
                $request->street_address,
                $request->ward_name,
                $request->district_name,
                $request->province_name,
            ]));

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'status'           => $request->status ?? 'pending',
                'payment_status'   => $request->payment_status ?? 'unpaid',
                'payment_method'   => $request->payment_method ?? 'cod',
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'shipping_address' => $fullAddress,
                'shipping_province' => $request->province_name,
                'shipping_district' => $request->district_name,
                'shipping_ward'     => $request->ward_name,
                'customer_note'    => $request->customer_note,
                'shipping_fee'     => $shippingFee,
                'subtotal'         => 0,
                'total'            => 0,
            ]);

            foreach ($request->items as $itemData) {
                $product = \App\Models\Product::findOrFail($itemData['product_id']);
                $variant = null;
                if (!empty($itemData['variant_id'])) {
                    $variant = \App\Models\ProductVariant::findOrFail($itemData['variant_id']);
                }

                $price = $variant ? ($variant->price ?? $product->price) : $product->price;
                $qty = $itemData['quantity'];
                $itemTotal = $price * $qty;
                $subtotal += $itemTotal;

                \App\Models\OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'variant_id'    => $variant?->id,
                    'product_name'  => $product->name,
                    'variant_label' => $variant?->label,
                    'sku'           => $variant?->sku ?? $product->sku,
                    'price'         => $price,
                    'quantity'      => $qty,
                    'total'         => $itemTotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + $shippingFee,
            ]);

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $order->status,
                'note'       => 'Đơn hàng được tạo thủ công từ quản trị.',
                'created_by' => auth()->id(),
            ]);

            \DB::commit();

            // Send Email AFTER items are saved and committed
            $order->sendOrderPlacedNotifications();

            return redirect()->route('admin.orders.index')->with('success', 'Đã tạo đơn hàng thành công.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi tạo đơn hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")
                  ->orWhere('customer_name', 'like', "%$s%")
                  ->orWhere('customer_phone', 'like', "%$s%");
            });
        }

        $orders   = $query->paginate(20)->withQueryString();
        $statuses = Order::$statuses;
        $paymentStatuses = Order::$paymentStatuses;

        return view('admin.orders.index', compact('orders', 'statuses', 'paymentStatuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'statusHistories.createdBy', 'user']);
        $statuses = Order::$statuses;
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function print(Order $order)
    {
        $order->load(['items.product', 'items.variant']);
        return view('admin.orders.print', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::$statuses)),
            'note'   => 'nullable|string|max:500',
        ]);

        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $request->status,
            'note'       => $request->note,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:' . implode(',', array_keys(Order::$paymentStatuses)),
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Đã cập nhật trạng thái thanh toán.');
    }

    public function updateNote(Request $request, Order $order)
    {
        $order->update(['admin_note' => $request->admin_note]);
        return back()->with('success', 'Đã lưu ghi chú.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã chuyển đơn hàng vào thùng rác.');
    }

    public function trash(Request $request)
    {
        $query = Order::onlyTrashed()->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%$s%")
                  ->orWhere('customer_name', 'like', "%$s%")
                  ->orWhere('customer_phone', 'like', "%$s%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();
        return view('admin.orders.trash', compact('orders'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $order->status,
            'note'       => 'Khôi phục đơn hàng từ thùng rác.',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.orders.trash')->with('success', 'Đã khôi phục đơn hàng thành công.');
    }

    public function forceDelete($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        
        // Delete items first if needed, but SoftDeletes on Order doesn't delete items normally
        // If we want to purge everything:
        $order->items()->delete();
        $order->statusHistories()->delete();
        $order->forceDelete();

        return redirect()->route('admin.orders.trash')->with('success', 'Đã xóa vĩnh viễn đơn hàng.');
    }

    public function getNewOrders(Request $request)
    {
        $afterId = (int) $request->get('after', 0);
        
        // Fetch all orders created after the 'afterId'
        $orders = Order::where('id', '>', $afterId)
            ->where('status', 'pending') // Only notify for new pending orders
            ->orderBy('id', 'asc')
            ->take(10) // Safety limit
            ->get();

        $data = $orders->map(function($order) {
            return [
                'id' => (int) $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => number_format($order->total, 0, ',', '.') . '₫',
                'time' => $order->created_at->diffForHumans(),
                'url' => route('admin.orders.show', $order->id)
            ];
        });

        return response()->json([
            'orders' => $data,
            'latest_id' => (int) (Order::max('id') ?? 0)
        ]);
    }
}
