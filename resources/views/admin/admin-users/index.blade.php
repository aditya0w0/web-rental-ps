@extends('layouts.app')

@section('title', 'Admin Employees')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-eyebrow">Owner</p>
                <h1 class="section-title">Admin employees</h1>
                <p class="section-copy">Owner-only access for adding or removing admin staff accounts.</p>
            </div>
            <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-2" aria-hidden="true"></i>
                Add admin
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
        @endif

        <section class="table-shell">
            <header class="flex items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-slate-950">Staff access</h2>
                <span class="badge bg-slate-100 text-slate-700">{{ $admins->where('role', 'admin')->count() }} admins</span>
            </header>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-950">{{ $admin->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">Created {{ $admin->created_at?->format('d M Y') }}</div>
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $admin->isOwner() ? 'badge-success' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $admin->isOwner() ? 'Owner' : 'Admin' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    @if($admin->role === 'admin' && $admin->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.admin-users.destroy', $admin) }}" onsubmit="return confirm('Delete this admin employee?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-secondary px-3 py-1.5 text-xs text-rose-700" type="submit">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-sm font-semibold text-slate-400">Locked</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
