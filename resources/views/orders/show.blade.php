@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<header class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); min-height: 350px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">تفاصيل الطلب #{{ $order->id }}</h1>
        <p style="color: #f8f9fa; font-size: 1.2rem;">{{ $order->created_at->format('d/m/Y - h:i A') }}</p>
    </div>
</header>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success text-center mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">حالة الطلب</h4>
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
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-5 px-4 py-2">
                                {{ $statusTexts[$order->status] ?? $order->status }}
                            </span>
                        </div>

                        <div class="order-timeline mb-4">
                            <div class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'preparing', 'ready', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>تم استلام الطلب</strong>
                                    <small class="d-block text-muted">{{ $order->created_at->format('d/m/Y - h:i A') }}</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ in_array($order->status, ['confirmed', 'preparing', 'ready', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>تم تأكيد الطلب</strong>
                                    <small class="d-block text-muted">ننتظر تجهيز طلبك</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ in_array($order->status, ['preparing', 'ready', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>يتجهز الحين</strong>
                                    <small class="d-block text-muted">الشيف يطبخ طلبك</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ in_array($order->status, ['ready', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>جاهز للتوصيل</strong>
                                    <small class="d-block text-muted">طلبك معبى وجاهز</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>تم التوصيل</strong>
                                    <small class="d-block text-muted">بالعافية عليك!</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong><i class="fas fa-user text-primary me-2"></i>اسم العميل:</strong>
                                <p class="mb-0 ms-4">{{ $order->customer_name }}</p>
                            </div>
                            <div class="col-6">
                                <strong><i class="fas fa-phone text-primary me-2"></i>رقم الجوال:</strong>
                                <p class="mb-0 ms-4">{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt text-primary me-2"></i>العنوان:</strong>
                            <p class="mb-0 ms-4">{{ $order->address }}</p>
                        </div>
                        @if($order->notes)
                        <div class="mb-3">
                            <strong><i class="fas fa-sticky-note text-primary me-2"></i>ملاحظات:</strong>
                            <p class="mb-0 ms-4">{{ $order->notes }}</p>
                        </div>
                        @endif

                        <hr>

                        <h5 class="mb-3"><i class="fas fa-utensils text-primary me-2"></i>تفاصيل الطلب:</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>الطبق</th>
                                        <th class="text-center">الكمية</th>
                                        <th class="text-end">السعر</th>
                                        <th class="text-end">المجموع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->item->name }}</strong>
                                            @if($item->item->description)
                                                <br><small class="text-muted">{{ Str::limit($item->item->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($item->price, 2) }} ر.س</td>
                                        <td class="text-end"><strong>{{ number_format($item->price * $item->quantity, 2) }} ر.س</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>المجموع الكلي:</strong></td>
                                        <td class="text-end">
                                            <strong style="font-size: 1.3rem; color: var(--primary-color);">
                                                {{ number_format($order->total_amount, 2) }} ر.س
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('orders.track', ['phone' => $order->customer_phone]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>شوف طلباتي الثانية
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>العودة للرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.order-timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 30px;
    opacity: 0.4;
    transition: opacity 0.3s;
}
.timeline-item.active {
    opacity: 1;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 10px;
    bottom: -20px;
    width: 3px;
    background: #e0e0e0;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-item.active::before {
    background: var(--primary-color);
}
.timeline-icon {
    position: absolute;
    left: -30px;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}
.timeline-item.active .timeline-icon {
    background: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(139, 69, 19, 0.1);
}
.timeline-content strong {
    display: block;
    margin-bottom: 5px;
    font-size: 1.1rem;
}
</style>
@endsection
