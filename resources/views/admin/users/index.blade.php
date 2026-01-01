@extends('layouts.admin')

@section('title', 'Quản lý khách hàng - HANZO')

@section('content')
<div class="hanzo-container py-8 px-3">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Quản lý khách hàng</h1>
            <p class="text-slate-600">Danh sách tất cả khách hàng</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
    </div>

    {{-- Search Form --}}
    <div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
        <form action="{{ route('admin.users.search') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="q" placeholder="Tìm theo tên, email, số điện thoại..." value="{{ request('q') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900">
            </div>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                Tìm kiếm
            </button>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Tên</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Email</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Số điện thoại</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Ngày tạo</th>
                            <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $user->phone ?? '-' }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">Chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @else
            <div class="p-12 text-center text-slate-600">
                Không tìm thấy khách hàng nào.
            </div>
        @endif
    </div>
</div>
@endsection
