@extends('layouts.admin')

@section('title', 'Sửa danh mục - HANZO')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Sửa danh mục</h1>
        <p class="text-slate-600">{{ $category->name }}</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Tên danh mục</label>
            <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" value="{{ $category->name }}">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Slug</label>
            <input type="text" name="slug" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" value="{{ $category->slug }}">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Banner URL</label>
            <input type="text" name="banner_url" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" value="{{ $category->banner_url }}" placeholder="URL ảnh banner danh mục">
            @if($category->banner_url)
                <div class="mt-3">
                    <img src="{{ $category->banner_url }}" alt="Banner preview" class="h-32 rounded-lg border border-slate-200 object-cover">
                </div>
            @endif
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">Cập nhật</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-slate-300 text-slate-900 rounded-lg font-medium hover:bg-slate-50 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
