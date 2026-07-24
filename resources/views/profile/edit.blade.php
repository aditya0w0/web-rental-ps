@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Account</p>
            <h1 class="section-title">Profile Settings</h1>
            <p class="section-copy">Kelola identitas akun, password, dan keamanan akun PlayHub.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                <x-user-sidebar />
            </aside>

            <div class="space-y-6">
                <section class="card p-6">
                    @include('profile.partials.update-profile-information-form')
                </section>

                <section class="card p-6">
                    @include('profile.partials.update-password-form')
                </section>

                <section class="card border-rose-200 p-6">
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
