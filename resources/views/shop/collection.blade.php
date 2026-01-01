@extends('layouts.app')

@section('content')
    <div class="hanzo-container mx-auto py-10">
        {{-- Tiêu đề bộ sưu tập --}}
        <h1 class="text-3xl font-bold mb-6">
            {{ $collectionName }}
        </h1>

        {{-- Nếu muốn có mô tả ngắn --}}
        <p class="text-slate-600 mb-8">
            Khám phá các sản phẩm trong bộ sưu tập {{ $collectionName }} của HANZO.
        </p>

        {{-- Grid sản phẩm --}}
        @if($products->count())
            <div class="grid grid-cols-4 gap-6">
                @foreach($products as $product)
                    {{-- dùng lại component card nếu bạn có --}}
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links('vendor.pagination.hanzo') }}
            </div>
        @else
            <p>Chưa có sản phẩm nào trong bộ sưu tập này.</p>
        @endif
    </div>
@endsection
