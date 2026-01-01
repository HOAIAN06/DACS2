@extends('layouts.admin')

@section('title', 'Quản lý danh mục - HANZO')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Quản lý danh mục</h1>
        <p class="text-slate-600">Tất cả danh mục sản phẩm</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">+ Thêm danh mục</a>
</div>

{{-- Categories Table --}}
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Danh mục</th>
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Slug</th>
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Số sản phẩm</th>
                        <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $category->name }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $category->slug }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $category->products_count ?? 0 }}</td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-700 font-medium text-xs">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-xs" onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200">
            {{ $categories->links('vendor.pagination.hanzo') }}
        </div>
    @else
        <div class="p-6 text-center text-slate-600">
            Không có danh mục nào
        </div>
    @endif
</div>
@endsection
