<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Manage Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">All Orders</h3>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-4 md:items-end">
                            <!-- Search Input -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Orders</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by order #, customer, email..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" 
                                        id="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                                <div class="flex space-x-2">
                                    <input type="date" 
                                           name="date_from" 
                                           id="date_from"
                                           value="{{ request('date_from') }}"
                                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <input type="date" 
                                           name="date_to" 
                                           id="date_to"
                                           value="{{ request('date_to') }}"
                                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Amount Range -->
                            <div>
                                <label for="amount_min" class="block text-sm font-medium text-gray-700 mb-1">Amount Range</label>
                                <div class="flex space-x-2">
                                    <input type="number" 
                                           name="amount_min" 
                                           id="amount_min"
                                           value="{{ request('amount_min') }}"
                                           placeholder="Min"
                                           step="0.01"
                                           min="0"
                                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <input type="number" 
                                           name="amount_max" 
                                           id="amount_max"
                                           value="{{ request('amount_max') }}"
                                           placeholder="Max"
                                           step="0.01"
                                           min="0"
                                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" 
                                        id="sort"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="amount_low" {{ request('sort') == 'amount_low' ? 'selected' : '' }}>Amount: Low to High</option>
                                    <option value="amount_high" {{ request('sort') == 'amount_high' ? 'selected' : '' }}>Amount: High to Low</option>
                                    <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Customer Name A-Z</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="md:col-span-2 lg:col-span-4 flex space-x-2 justify-end">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Apply Filters
                                </button>
                                <a href="{{ route('admin.orders.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Clear
                                </a>
                            </div>
                        </form>

                        <!-- Active Filters -->
                        @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to', 'amount_min', 'amount_max', 'sort']))
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-sm text-gray-600">Active filters:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Status: {{ ucfirst(request('status')) }}
                                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('date_from') || request('date_to'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Date: {{ request('date_from', 'Any') }} to {{ request('date_to', 'Any') }}
                                        <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                                @if(request('amount_min') || request('amount_max'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Amount: ${{ request('amount_min', 0) }} - ${{ request('amount_max', '∞') }}
                                        <a href="{{ request()->fullUrlWithQuery(['amount_min' => null, 'amount_max' => null]) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort') && request('sort') != 'latest')
                                    @php
                                        $sortLabels = [
                                            'oldest' => 'Oldest First',
                                            'amount_low' => 'Amount: Low to High',
                                            'amount_high' => 'Amount: High to Low',
                                            'customer_name' => 'Customer Name A-Z'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Sort: {{ $sortLabels[request('sort')] ?? request('sort') }}
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="ml-1 text-red-600 hover:text-red-800">×</a>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Orders Table -->
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left">Order #</th>
                                        <th class="py-3 px-4 border-b text-left">Customer</th>
                                        <th class="py-3 px-4 border-b text-left">Date</th>
                                        <th class="py-3 px-4 border-b text-left">Amount</th>
                                        <th class="py-3 px-4 border-b text-left">Status</th>
                                        <th class="py-3 px-4 border-b text-left">Items</th>
                                        <th class="py-3 px-4 border-b text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 border-b font-semibold">#{{ $order->order_number }}</td>
                                            <td class="py-3 px-4 border-b">
                                                <div>
                                                    <div class="font-medium">{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</div>
                                                    <div class="text-sm text-gray-600">{{ $order->customer_email ?? $order->user->email ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 border-b text-sm">{{ $order->created_at->format('M j, Y') }}</td>
                                            <td class="py-3 px-4 border-b font-semibold">${{ number_format($order->total_amount, 2) }}</td>
                                            <td class="py-3 px-4 border-b">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 border-b text-sm">{{ $order->items->count() }} items</td>
                                            <td class="py-3 px-4 border-b">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                                       class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm">
                                                        View
                                                    </a>
                                                    <a href="{{ route('admin.orders.edit', $order) }}" 
                                                       class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.orders.destroy', $order) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm"
                                                                {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📦</div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">No Orders Found</h3>
                            <p class="text-gray-600 mb-8">
                                @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                                    No orders match your current filters.
                                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 ml-1">Clear filters</a>
                                @else
                                    No orders have been placed yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>