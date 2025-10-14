<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">📊 Sales Analytics</h3>

                    <!-- Date Range Filter -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h4 class="text-lg font-semibold mb-4">Report Period</h4>
                        <form action="{{ route('admin.reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                    Generate Report
                                </button>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('admin.reports.sales') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Sales Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-blue-800">Total Orders</h4>
                                <span class="text-2xl">📦</span>
                            </div>
                            <p class="text-3xl font-bold text-blue-600">{{ $salesSummary->total_orders ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-green-800">Total Revenue</h4>
                                <span class="text-2xl">💰</span>
                            </div>
                            <p class="text-3xl font-bold text-green-600">${{ number_format($salesSummary->total_revenue ?? 0, 2) }}</p>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-purple-800">Avg Order Value</h4>
                                <span class="text-2xl">📊</span>
                            </div>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format($salesSummary->average_order_value ?? 0, 2) }}</p>
                        </div>
                        
                        <div class="bg-amber-50 border border-amber-200 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-amber-800">Unique Customers</h4>
                                <span class="text-2xl">👥</span>
                            </div>
                            <p class="text-3xl font-bold text-amber-600">{{ $salesSummary->unique_customers ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Sales by Status -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold mb-4">Sales by Order Status</h4>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Order Count</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Revenue</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($salesByStatus as $status)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs 
                                                    @if($status->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($status->status == 'processing') bg-blue-100 text-blue-800
                                                    @elseif($status->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($status->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">{{ $status->count }}</td>
                                            <td class="py-3 px-4">${{ number_format($status->revenue, 2) }}</td>
                                            <td class="py-3 px-4">
                                                @php
                                                    $percentage = $salesSummary->total_revenue > 0 ? ($status->revenue / $salesSummary->total_revenue) * 100 : 0;
                                                @endphp
                                                {{ number_format($percentage, 1) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold mb-4">Top Selling Products</h4>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Product</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Units Sold</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Revenue</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Avg Price</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($topProducts as $product)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center">
                                                    @if($product->image)
                                                        <img class="h-8 w-8 rounded-full object-cover mr-3" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                                    @endif
                                                    <span class="font-medium">{{ $product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">{{ $product->units_sold }}</td>
                                            <td class="py-3 px-4">${{ number_format($product->revenue, 2) }}</td>
                                            <td class="py-3 px-4">
                                                ${{ number_format($product->units_sold > 0 ? $product->revenue / $product->units_sold : 0, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Monthly Sales Chart Data -->
                    @if($monthlySales->count() > 0)
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Monthly Sales Trend</h4>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="space-y-4">
                                    @foreach($monthlySales as $month)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                            </span>
                                            <div class="flex-1 mx-4">
                                                <div class="bg-gray-200 rounded-full h-4">
                                                    <div class="bg-green-500 h-4 rounded-full" 
                                                         style="width: {{ ($month->revenue / $monthlySales->max('revenue')) * 100 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-semibold text-gray-900">${{ number_format($month->revenue, 2) }}</span>
                                                <span class="text-xs text-gray-500 block">{{ $month->order_count }} orders</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <div class="text-4xl mb-2">📊</div>
                            <p class="text-gray-600">No sales data available for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>