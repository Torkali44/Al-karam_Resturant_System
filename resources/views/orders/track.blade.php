@extends('layouts.app')

@section('title', 'تتبع طلبك')

@section('content')
<header class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); min-height: 400px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">تتبع طلباتك</h1>
        <p style="color: #f8f9fa; font-size: 1.3rem;">ادخل رقم جوالك وشوف كل طلباتك</p>
    </div>
</header>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(!isset($orders))
                    <!-- نموذج إدخال رقم الجوال -->
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                <h4>ادخل رقم جوالك</h4>
                                <p class="text-muted">عشان نجيب لك كل طلباتك</p>
                            </div>
                            <form action="{{ route('orders.track') }}" method="GET">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الجوال <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-lg text-center" id="phone" name="phone" 
                                           placeholder="05XXXXXXXX" value="{{ request('phone') }}" required
                                           pattern="^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$">
                                    <small class="text-muted">رقم الجوال اللي استخدمته في الطلب</small>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i> شوف طلباتي
                                </button>
                            </form>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger mt-3 text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif
                @else
                    <!-- عرض قائمة الطلبات -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">
                                    <i class="fas fa-list-ul text-primary me-2"></i>
                                    طلباتك ({{ $orders->count() }})
                                </h4>
                                <a href="{{ route('orders.track') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i> رقم جديد
                                </a>
                            </div>

                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'preparing' => 'primary',
                                    'ready' => 'success',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusTexts = [
                                    'pending' => 'قيد الانتظار',
                                    'confirmed' => 'تم التأكيد',
                                    'preparing' => 'يتجهز الحين',
                                    'ready' => 'جاهز للتوصيل',
                                    'delivered' => 'تم التوصيل',
                                    'cancelled' => 'ملغي'
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'confirmed' => 'check-circle',
                                    'preparing' => 'fire',
                                    'ready' => 'box',
                                    'delivered' => 'check-double',
                                    'cancelled' => 'times-circle'
                                ];
                            @endphp

                            <div class="orders-list">
                                @foreach($orders as $order)
                                    <div class="order-card mb-3 p-3 border rounded" style="transition: all 0.3s; cursor: pointer;" 
                                         onclick="window.location='{{ route('orders.track', ['order_id' => $order->id, 'phone' => request('phone')]) }}'">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 text-center">
                                                <div class="order-number">
                                                    <strong style="font-size: 1.5rem; color: var(--primary-color);">#{{ $order->id }}</strong>
                                                    <small class="d-block text-muted">رقم الطلب</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="order-date">
                                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                    <strong>{{ $order->created_at->format('d/m/Y') }}</strong>
                                                    <small class="d-block text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="order-total">
                                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                    <strong>{{ number_format($order->total_amount, 2) }} ر.س</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6 px-3 py-2">
                                                    <i class="fas fa-{{ $statusIcons[$order->status] ?? 'question' }} me-1"></i>
                                                    {{ $statusTexts[$order->status] ?? $order->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-top" style="font-size: 0.9rem;">
                                            <i class="fas fa-utensils text-muted me-2"></i>
                                            <span class="text-muted">{{ $order->orderItems->count() }} صنف</span>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-user text-muted me-2"></i>
                                            <span class="text-muted">{{ $order->customer_name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    اضغط على أي طلب لمشاهدة التفاصيل الكاملة
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.order-card {
    background: white;
    transition: all 0.3s ease;
}
.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: var(--primary-color) !important;
}
</style>
@endsection
