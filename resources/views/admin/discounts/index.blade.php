<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Discounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Discount Codes</h3>
                        <a href="{{ route('admin.discounts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ➕ Create Discount
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Code</th>
                                    <th class="py-2 px-4 border-b">Name</th>
                                    <th class="py-2 px-4 border-b">Type</th>
                                    <th class="py-2 px-4 border-b">Value</th>
                                    <th class="py-2 px-4 border-b">Uses</th>
                                    <th class="py-2 px-4 border-b">Status</th>
                                    <th class="py-2 px-4 border-b">Valid Until</th>
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discounts as $discount)
                                    <tr>
                                        <td class="py-2 px-4 border-b font-mono">{{ $discount->code }}</td>
                                        <td class="py-2 px-4 border-b">{{ $discount->name }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded-full text-xs 
                                                {{ $discount->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($discount->type) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            @if($discount->type === 'percentage')
                                                {{ $discount->value }}%
                                            @else
                                                ${{ number_format($discount->value, 2) }}
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            {{ $discount->used_count }} 
                                            @if($discount->max_uses)
                                                / {{ $discount->max_uses }}
                                            @else
                                                / ∞
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded-full text-xs 
                                                {{ $discount->isValid() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $discount->isValid() ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ $discount->expires_at->format('M j, Y') }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this discount?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm">
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

                    <div class="mt-4">
                        {{ $discounts->links() }}
                    </div>

                    @if($discounts->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No discounts created yet.</p>
                            <a href="{{ route('admin.discounts.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Create your first discount code →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>