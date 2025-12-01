@extends('layouts.app')

@section('title', 'Payment for Order #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Payment</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <p class="mb-2"><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p class="mb-4"><strong>Total Amount:</strong> <span class="text-2xl font-bold text-purple-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>

                <h3 class="text-lg font-semibold mt-6 mb-2">Payment Instructions</h3>
                <p>Silakan transfer total ke rekening berikut:</p>
                <ul class="list-disc list-inside mt-2 bg-gray-50 p-4 rounded-md">
                    <li><strong>Bank BRI:</strong> 006801105708509 (a/n Andhika)</li>
                    <li class="mt-1"><strong>Bank BCA:</strong> 2381490019 (a/n Andhika)</li>
                </ul>
                <p class="mt-4 text-sm text-gray-600">After making the payment, please upload the proof of transfer using the form on the right.</p>
            </div>
        </div>
        <div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Upload Payment Proof</h2>
                <form action="{{ route('orders.payment.process', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700">Payment Proof Image</label>
                        <input type="file" name="payment_proof" id="payment_proof" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB.</p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-purple-700 transition-colors duration-300">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
