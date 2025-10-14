<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Discount') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">Edit Discount: {{ $discount->code }}</h3>

                    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div class="md:col-span-2">
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Discount Code *</label>
                                <input type="text" name="code" id="code" required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase"
                                       value="{{ old('code', $discount->code) }}"
                                       placeholder="SUMMER2024">
                                @error('code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Discount Name *</label>
                                <input type="text" name="name" id="name" required 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('name', $discount->name) }}"
                                       placeholder="Summer Sale 2024">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Optional description for internal use">{{ old('description', $discount->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type and Value -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                                <select name="type" id="type" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">Discount Value *</label>
                                <input type="number" name="value" id="value" step="0.01" min="0" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('value', $discount->value) }}"
                                       placeholder="10.00">
                                <p class="text-sm text-gray-500 mt-1" id="value-help">
                                    {{ $discount->type === 'percentage' ? 'Percentage discount (e.g., 10 for 10%)' : 'Fixed amount discount (e.g., 5.00 for $5 off)' }}
                                </p>
                                @error('value')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Min Order Amount -->
                            <div>
                                <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount</label>
                                <input type="number" name="min_order_amount" id="min_order_amount" step="0.01" min="0"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('min_order_amount', $discount->min_order_amount) }}"
                                       placeholder="0.00">
                                <p class="text-sm text-gray-500 mt-1">Minimum cart total required (leave empty for no minimum)</p>
                                @error('min_order_amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Uses -->
                            <div>
                                <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-2">Maximum Uses</label>
                                <input type="number" name="max_uses" id="max_uses" min="1"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('max_uses', $discount->max_uses) }}"
                                       placeholder="Unlimited">
                                <p class="text-sm text-gray-500 mt-1">Maximum number of times this discount can be used</p>
                                @error('max_uses')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('starts_at', $discount->starts_at->format('Y-m-d\TH:i')) }}">
                                @error('starts_at')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('expires_at', $discount->expires_at->format('Y-m-d\TH:i')) }}">
                                @error('expires_at')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2 flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $discount->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Active Discount
                                </label>
                            </div>

                            <!-- Usage Stats -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2">Usage Statistics</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Times Used:</span>
                                        <span class="font-semibold">{{ $discount->used_count }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Status:</span>
                                        <span class="font-semibold {{ $discount->isValid() ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $discount->isValid() ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('admin.discounts.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            const helpText = document.getElementById('value-help');
            if (this.value === 'percentage') {
                helpText.textContent = 'Percentage discount (e.g., 10 for 10%)';
            } else {
                helpText.textContent = 'Fixed amount discount (e.g., 5.00 for $5 off)';
            }
        });
    </script>
</x-app-layout>