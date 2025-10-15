<div class="w-64 bg-white dark:bg-gray-800 shadow flex flex-col">
    <div class="p-6 text-2xl font-bold text-gray-800 dark:text-white">
        Admin
    </div>
    <nav class="flex-1 px-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Products</a>
        <a href="{{ route('admin.categories.index') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Categories</a>
        <a href="{{ route('admin.subcategories.index') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Subcategories</a>
        <a href="{{ route('admin.orders.index') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Orders</a>
        <a href="{{ route('admin.discounts.index') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Discounts</a>
        <a href="{{ route('admin.reports.sales') }}" class="block py-2 px-4 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Reports</a>
    </nav>
</div>
