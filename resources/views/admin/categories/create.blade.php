@extends('layouts.admin')

@section('title', 'Thêm danh mục - HANZO')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Thêm danh mục mới</h1>
        <p class="text-slate-600">Tạo danh mục cho sản phẩm</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Tên danh mục</label>
            <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="Nhập tên danh mục">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Slug</label>
            <input type="text" name="slug" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="Slug (tự động tạo nếu để trống)">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Banner URL</label>
            <input type="text" name="banner_url" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="URL ảnh banner danh mục">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">Thêm danh mục</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-slate-300 text-slate-900 rounded-lg font-medium hover:bg-slate-50 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
