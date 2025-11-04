<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CultureConnect Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">CultureConnect Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="admin-name" class="text-gray-600"></span>
                    <button onclick="logout()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-white shadow-lg h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#dashboard" onclick="showSection('dashboard')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100 active">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#users" onclick="showSection('users')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-users mr-3"></i>
                            Users
                        </a>
                    </li>
                    <li>
                        <a href="#pavilions" onclick="showSection('pavilions')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-building mr-3"></i>
                            Pavilions
                        </a>
                    </li>
                    <li>
                        <a href="#banners" onclick="showSection('banners')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-images mr-3"></i>
                            Banners
                        </a>
                    </li>
                    <li>
                        <a href="#shops" onclick="showSection('shops')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-store mr-3"></i>
                            Shops
                        </a>
                    </li>
                    <li>
                        <a href="#products" onclick="showSection('products')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-box mr-3"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="#events" onclick="showSection('events')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-calendar mr-3"></i>
                            Events
                        </a>
                    </li>
                    <li>
                        <a href="#orders" onclick="showSection('orders')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-shopping-cart mr-3"></i>
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="#reviews" onclick="showSection('reviews')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-star mr-3"></i>
                            Reviews
                        </a>
                    </li>
                    <li>
                        <a href="#notifications" onclick="showSection('notifications')" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-bell mr-3"></i>
                            Notifications
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="section">
                <h2 class="text-2xl font-bold mb-6">Dashboard Overview</h2>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Users</p>
                                <p id="total-users" class="text-2xl font-bold">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Events</p>
                                <p id="total-events" class="text-2xl font-bold">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <i class="fas fa-shopping-cart text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Orders</p>
                                <p id="total-orders" class="text-2xl font-bold">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Revenue</p>
                                <p id="total-revenue" class="text-2xl font-bold">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">User Growth</h3>
                        <canvas id="userChart"></canvas>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Order Status</h3>
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div id="users-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">User Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <input type="text" id="user-search" placeholder="Search users..." class="border rounded px-3 py-2 w-64">
                            <button onclick="loadUsers()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <i class="fas fa-refresh"></i> Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table">
                                    <!-- Users will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pavilions Section -->
            <div id="pavilions-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Pavilion Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <input type="text" id="pavilion-search" placeholder="Search pavilions..." class="border rounded px-3 py-2 w-64">
                            <button onclick="showAddPavilionModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Pavilion
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shops</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pavilions-table">
                                    <!-- Pavilions will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banners Section -->
            <div id="banners-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Banner Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage home banners (click opens link)</span>
                            <button onclick="showAddBannerModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Banner
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="banners-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shops Section -->
            <div id="shops-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Shop Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage shops in pavilions</span>
                            <button onclick="showAddShopModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Shop
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavilion</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="shops-table">
                                    <!-- Shops will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div id="products-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Product Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage products in shops</span>
                            <button onclick="showAddProductModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Product
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Is Food</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table">
                                    <!-- Products will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other sections will be similar -->
            <div id="events-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Event Management</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-600">Event management interface coming soon...</p>
                </div>
            </div>

            <div id="orders-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Order Management</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-600">Order management interface coming soon...</p>
                </div>
            </div>

            <div id="reviews-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Review Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage product and shop reviews</span>
                            <button onclick="showAddReviewModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Review
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reviews-table">
                                    <!-- Reviews will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="notifications-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Notification Management</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-600">Notification management interface coming soon...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Pavilion Modal -->
    <div id="add-pavilion-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="pavilion-modal-title" class="text-lg font-semibold">Add New Pavilion</h3>
                    <button onclick="closeAddPavilionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-pavilion-form" onsubmit="savePavilion(event)" class="p-6">
                    <input type="hidden" name="pavilion_id" id="pavilion_id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="pavilion_name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="pavilion_description" required rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" id="pavilion_country" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude (Optional)</label>
                                <input type="number" name="lat" id="pavilion_lat" step="any" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude (Optional)</label>
                                <input type="number" name="lng" id="pavilion_lng" step="any" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Pick on Map (Optional)</label>
                                <button type="button" onclick="useMyLocation()" class="text-sm text-blue-600 hover:underline">Use my location</button>
                            </div>
                            <div id="pavilion-map" class="w-full h-64 rounded border"></div>
                            <p class="text-xs text-gray-500 mt-1">Click on the map to set latitude and longitude.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Open Hours (Optional)</label>
                            <input type="text" name="open_hours" id="pavilion_open_hours" placeholder="9:00 AM - 10:00 PM" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                            <input type="file" name="icon" accept="image/*,.svg" class="w-full border rounded px-3 py-2">
                            <div id="pavilion-current-icon" class="mt-2 hidden">
                                <p class="text-xs text-gray-500 mb-1">Current icon:</p>
                                <img id="pavilion-icon-preview" src="" alt="Current icon" class="w-16 h-16 object-contain">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddPavilionModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="pavilion-submit-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Pavilion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Banner Modal -->
    <div id="add-banner-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold">Add New Banner</h3>
                    <button onclick="closeAddBannerModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-banner-form" onsubmit="addBanner(event)" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title (Optional)</label>
                            <input type="text" name="title" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link (Optional)</label>
                            <input type="url" name="link" placeholder="https://example.com" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                <input type="number" name="order" min="0" value="0" class="w-full border rounded px-3 py-2">
                            </div>
                            <div class="flex items-center mt-6">
                                <input type="checkbox" name="is_active" id="banner_is_active" class="mr-2" checked>
                                <label for="banner_is_active" class="text-sm text-gray-700">Active</label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <input type="file" name="image" accept="image/*,.svg" required class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddBannerModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Shop Modal -->
    <div id="add-shop-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold">Add New Shop</h3>
                    <button onclick="closeAddShopModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-shop-form" onsubmit="addShop(event)" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pavilion *</label>
                            <select name="pavilion_id" id="shop-pavilion-select" required class="w-full border rounded px-3 py-2">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full border rounded px-3 py-2">
                                <option value="shop">Shop</option>
                                <option value="food_truck">Food Truck</option>
                                <option value="restaurant">Restaurant</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddShopModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Shop
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="add-product-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold">Add New Product</h3>
                    <button onclick="closeAddProductModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-product-form" onsubmit="addProduct(event)" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shop *</label>
                            <select name="shop_id" id="product-shop-select" required class="w-full border rounded px-3 py-2">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <input type="number" name="price" step="0.01" min="0" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                            <input type="url" name="image_url" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_food" id="product_is_food" class="mr-2">
                            <label for="product_is_food" class="text-sm text-gray-700">Is Food</label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddProductModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Review Modal -->
    <div id="add-review-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold">Add New Review</h3>
                    <button onclick="closeAddReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-review-form" onsubmit="addReview(event)" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User *</label>
                            <select name="user_id" id="review-user-select" required class="w-full border rounded px-3 py-2">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shop (Required if no product)</label>
                            <select name="shop_id" id="review-shop-select" class="w-full border rounded px-3 py-2">
                                <option value="">Select a shop...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product (Optional)</label>
                            <select name="product_id" id="review-product-select" class="w-full border rounded px-3 py-2">
                                <option value="">Select a product...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating *</label>
                            <select name="rating" required class="w-full border rounded px-3 py-2">
                                <option value="">Select rating...</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                            <textarea name="comment" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddReviewModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let authToken = '';
        let currentUser = null;

        // Initialize the admin panel
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            authToken = token;
            loadDashboard();
        });

        // API helper function
        async function apiCall(endpoint, options = {}) {
            const defaultOptions = {
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };

            const response = await fetch(`/api${endpoint}`, { ...defaultOptions, ...options });

            if (response.status === 401) {
                localStorage.removeItem('admin_token');
                window.location.href = '/admin/login';
                return null;
            }

            return response.json();
        }

        // Load dashboard data
        async function loadDashboard() {
            try {
                const data = await apiCall('/admin/dashboard');
                if (data && data.success) {
                    updateDashboardStats(data.data);
                    createCharts(data.data);
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }

        // Update dashboard statistics
        function updateDashboardStats(stats) {
            document.getElementById('total-users').textContent = stats.overview.total_users;
            document.getElementById('total-events').textContent = stats.overview.total_events;
            document.getElementById('total-orders').textContent = stats.overview.total_orders;
            document.getElementById('total-revenue').textContent = `$${stats.revenue.total_revenue || 0}`;
        }

        // Create charts
        function createCharts(data) {
            // User growth chart
            const userCtx = document.getElementById('userChart').getContext('2d');
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: data.recent_activity.recent_users.map(user => new Date(user.created_at).toLocaleDateString()),
                    datasets: [{
                        label: 'New Users',
                        data: data.recent_activity.recent_users.map((_, index) => index + 1),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Order status chart
            const orderCtx = document.getElementById('orderChart').getContext('2d');
            new Chart(orderCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data.orders_by_status),
                    datasets: [{
                        data: Object.values(data.orders_by_status),
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#8B5CF6',
                            '#6B7280'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        // Load users
        async function loadUsers() {
            try {
                const data = await apiCall('/admin/users');
                if (data && data.success) {
                    displayUsers(data.data.items);
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        // Display users in table
        function displayUsers(users) {
            const tbody = document.getElementById('users-table');
            tbody.innerHTML = users.map(user => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.name || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.phone || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full ${user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${user.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editUser(${user.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Show section
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });

            // Remove active class from all nav items
            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('active');
            });

            // Show selected section
            document.getElementById(`${sectionName}-section`).classList.remove('hidden');

            // Add active class to clicked nav item
            event.target.classList.add('active');

            // Load section data
            if (sectionName === 'users') {
                loadUsers();
            } else if (sectionName === 'pavilions') {
                loadPavilions();
            } else if (sectionName === 'banners') {
                loadBanners();
            } else if (sectionName === 'shops') {
                loadShops();
            } else if (sectionName === 'products') {
                loadProducts();
            } else if (sectionName === 'reviews') {
                loadReviews();
            }
        }

        // Logout
        function logout() {
            localStorage.removeItem('admin_token');
            window.location.href = '/admin/login';
        }

        // Edit user
        function editUser(userId) {
            // Implementation for editing user
            alert(`Edit user ${userId} - Feature coming soon!`);
        }

        // Delete user
        async function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                try {
                    const data = await apiCall(`/admin/users/${userId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadUsers();
                        alert('User deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    alert('Error deleting user');
                }
            }
        }

        // Pavilion Management Functions
        async function loadPavilions() {
            try {
                const data = await apiCall('/admin/pavilions');
                if (data && data.success) {
                    displayPavilions(data.data.items);
                }
            } catch (error) {
                console.error('Error loading pavilions:', error);
            }
        }

        function displayPavilions(pavilions) {
            const tbody = document.getElementById('pavilions-table');
            tbody.innerHTML = pavilions.map(pavilion => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pavilion.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pavilion.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pavilion.country || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pavilion.shops_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${pavilion.icon ? `<img src="${pavilion.icon}" alt="Icon" class="w-8 h-8 rounded">` : 'No Icon'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editPavilion(${pavilion.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePavilion(${pavilion.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function showAddPavilionModal() {
            // Reset form for add mode
            document.getElementById('pavilion_id').value = '';
            document.getElementById('pavilion-modal-title').textContent = 'Add New Pavilion';
            document.getElementById('pavilion-submit-btn').textContent = 'Add Pavilion';
            document.getElementById('pavilion-current-icon').classList.add('hidden');

            document.getElementById('add-pavilion-modal').classList.remove('hidden');
            document.getElementById('add-pavilion-form').reset();

            // Reset map
            if (pavilionMap) {
                pavilionMap.remove();
                pavilionMap = null;
                pavilionMarker = null;
            }

            setTimeout(() => {
                initPavilionMap();
            }, 100);
        }

        function closeAddPavilionModal() {
            document.getElementById('add-pavilion-modal').classList.add('hidden');
            document.getElementById('add-pavilion-form').reset();
            document.getElementById('pavilion_id').value = '';
            document.getElementById('pavilion-current-icon').classList.add('hidden');

            // Reset map
            if (pavilionMap) {
                pavilionMap.remove();
                pavilionMap = null;
                pavilionMarker = null;
            }
        }

        async function editPavilion(pavilionId) {
            try {
                // Fetch pavilion data
                const data = await apiCall(`/api/pavilions/${pavilionId}`);
                if (!data || !data.success) {
                    alert('Failed to load pavilion data');
                    return;
                }

                const pavilion = data.data;

                // Populate form
                document.getElementById('pavilion_id').value = pavilion.id;
                document.getElementById('pavilion_name').value = pavilion.name || '';
                document.getElementById('pavilion_description').value = pavilion.description || '';
                document.getElementById('pavilion_country').value = pavilion.country || '';
                document.getElementById('pavilion_lat').value = pavilion.lat || '';
                document.getElementById('pavilion_lng').value = pavilion.lng || '';
                document.getElementById('pavilion_open_hours').value = pavilion.open_hours || '';

                // Update modal title and button
                document.getElementById('pavilion-modal-title').textContent = 'Edit Pavilion';
                document.getElementById('pavilion-submit-btn').textContent = 'Update Pavilion';

                // Show current icon if exists
                if (pavilion.icon) {
                    document.getElementById('pavilion-icon-preview').src = pavilion.icon;
                    document.getElementById('pavilion-current-icon').classList.remove('hidden');
                } else {
                    document.getElementById('pavilion-current-icon').classList.add('hidden');
                }

                // Show modal
                document.getElementById('add-pavilion-modal').classList.remove('hidden');

                // Reset map
                if (pavilionMap) {
                    pavilionMap.remove();
                    pavilionMap = null;
                    pavilionMarker = null;
                }

                // Initialize map with pavilion location
                setTimeout(() => {
                    initPavilionMap();
                    if (pavilion.lat && pavilion.lng) {
                        setPavilionLatLng(parseFloat(pavilion.lat), parseFloat(pavilion.lng), true);
                        pavilionMap.setView([parseFloat(pavilion.lat), parseFloat(pavilion.lng)], 13);
                    }
                }, 100);
            } catch (error) {
                console.error('Error loading pavilion:', error);
                alert('Error loading pavilion data');
            }
        }

        async function savePavilion(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const pavilionId = formData.get('pavilion_id');

            const isEdit = pavilionId && pavilionId !== '';

            // Remove pavilion_id from formData for PUT requests (it's in the URL)
            if (isEdit) {
                formData.delete('pavilion_id');
            }

            try {
                const url = isEdit ? `/api/admin/pavilions/${pavilionId}` : '/api/admin/pavilions';
                const method = isEdit ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    closeAddPavilionModal();
                    loadPavilions();
                    alert(isEdit ? 'Pavilion updated successfully!' : 'Pavilion added successfully!');
                } else {
                    console.error('Validation errors:', data.errors);
                    console.error('Debug info:', data.debug);

                    let errorMessage = data.message || (isEdit ? 'Failed to update pavilion' : 'Failed to add pavilion');
                    if (data.errors) {
                        errorMessage += '\n\nValidation errors:\n';
                        for (const field in data.errors) {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        }
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error saving pavilion:', error);
                alert(isEdit ? 'Error updating pavilion' : 'Error adding pavilion');
            }
        }

        // Leaflet map for Pavilion picker
        let pavilionMap = null;
        let pavilionMarker = null;

        function initPavilionMap() {
            const mapEl = document.getElementById('pavilion-map');
            if (!mapEl) return;
            // Avoid re-initializing
            if (pavilionMap) {
                setTimeout(() => { pavilionMap.invalidateSize(); }, 200);
                return;
            }

            pavilionMap = L.map('pavilion-map').setView([25.2048, 55.2708], 11); // Default: Dubai
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(pavilionMap);

            pavilionMap.on('click', function (e) {
                setPavilionLatLng(e.latlng.lat, e.latlng.lng);
            });

            // If form has existing values, show marker
            const latInput = document.querySelector('input[name="lat"]');
            const lngInput = document.querySelector('input[name="lng"]');
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                setPavilionLatLng(lat, lng, true);
                pavilionMap.setView([lat, lng], 13);
            }

            setTimeout(() => { pavilionMap.invalidateSize(); }, 200);
        }

        function setPavilionLatLng(lat, lng, skipCenter) {
            const latInput = document.querySelector('input[name="lat"]');
            const lngInput = document.querySelector('input[name="lng"]');
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            if (!pavilionMarker) {
                pavilionMarker = L.marker([lat, lng], { draggable: true }).addTo(pavilionMap);
                pavilionMarker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    latInput.value = pos.lat.toFixed(6);
                    lngInput.value = pos.lng.toFixed(6);
                });
            } else {
                pavilionMarker.setLatLng([lat, lng]);
            }
            if (!skipCenter) pavilionMap.setView([lat, lng], pavilionMap.getZoom());
        }

        function useMyLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                initPavilionMap();
                setPavilionLatLng(lat, lng);
            }, function () {
                alert('Unable to retrieve your location');
            });
        }

        function editPavilion(pavilionId) {
            alert(`Edit pavilion ${pavilionId} - Feature coming soon!`);
        }

        async function deletePavilion(pavilionId) {
            if (confirm('Are you sure you want to delete this pavilion?')) {
                try {
                    const data = await apiCall(`/admin/pavilions/${pavilionId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadPavilions();
                        alert('Pavilion deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting pavilion:', error);
                    alert('Error deleting pavilion');
                }
            }
        }

        // Banner Management Functions
        async function loadBanners() {
            try {
                const data = await apiCall('/admin/banners');
                if (data && data.success) {
                    displayBanners(data.data.items);
                }
            } catch (error) {
                console.error('Error loading banners:', error);
            }
        }

        function displayBanners(banners) {
            const tbody = document.getElementById('banners-table');
            tbody.innerHTML = banners.map(banner => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${banner.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${banner.title || '—'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${banner.order ?? 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full ${banner.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${banner.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${banner.image ? `<img src="${banner.image}" alt="Banner" class="w-16 h-10 object-cover rounded">` : 'No Image'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">${banner.link ? `<a href="${banner.link}" target="_blank">${banner.link}</a>` : '—'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editBanner(${banner.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteBanner(${banner.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function showAddBannerModal() {
            document.getElementById('add-banner-modal').classList.remove('hidden');
        }

        function closeAddBannerModal() {
            document.getElementById('add-banner-modal').classList.add('hidden');
            document.getElementById('add-banner-form').reset();
        }

        async function addBanner(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            // Normalize checkbox value to 1/0 for backend boolean validation
            const isActiveEl = document.querySelector('input[name="is_active"]');
            formData.set('is_active', isActiveEl && isActiveEl.checked ? '1' : '0');

            try {
                const response = await fetch('/api/admin/banners', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    closeAddBannerModal();
                    loadBanners();
                    alert('Banner added successfully!');
                } else {
                    let msg = data.message || 'Failed to add banner';
                    if (data.errors) {
                        msg += '\n';
                        for (const k in data.errors) msg += `${k}: ${data.errors[k].join(', ')}\n`;
                    }
                    alert(msg);
                }
            } catch (err) {
                console.error('Error adding banner:', err);
                alert('Error adding banner');
            }
        }

        function editBanner(id) {
            alert(`Edit banner ${id} - Feature coming soon!`);
        }

        async function deleteBanner(id) {
            if (!confirm('Are you sure you want to delete this banner?')) return;
            try {
                const data = await apiCall(`/admin/banners/${id}`, { method: 'DELETE' });
                if (data && data.success) {
                    loadBanners();
                    alert('Banner deleted successfully');
                }
            } catch (err) {
                console.error('Error deleting banner:', err);
                alert('Error deleting banner');
            }
        }

        // Shop Management Functions
        async function loadShops() {
            try {
                const data = await apiCall('/admin/shops');
                if (data && data.success) {
                    displayShops(data.data.items);
                }
            } catch (error) {
                console.error('Error loading shops:', error);
            }
        }

        function displayShops(shops) {
            const tbody = document.getElementById('shops-table');
            tbody.innerHTML = shops.map(shop => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.pavilion ? shop.pavilion.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.type || 'shop'}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${shop.description || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editShop(${shop.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteShop(${shop.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function showAddShopModal() {
            // Load pavilions for dropdown
            try {
                const data = await apiCall('/admin/pavilions?per_page=100');
                if (data && data.success) {
                    const select = document.getElementById('shop-pavilion-select');
                    select.innerHTML = '<option value="">Select a pavilion...</option>';
                    data.data.items.forEach(pavilion => {
                        select.innerHTML += `<option value="${pavilion.id}">${pavilion.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading pavilions:', error);
            }
            document.getElementById('add-shop-modal').classList.remove('hidden');
        }

        function closeAddShopModal() {
            document.getElementById('add-shop-modal').classList.add('hidden');
            document.getElementById('add-shop-form').reset();
        }

        async function addShop(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await apiCall('/admin/shops', {
                    method: 'POST',
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddShopModal();
                    loadShops();
                    alert('Shop added successfully');
                }
            } catch (error) {
                console.error('Error adding shop:', error);
                alert('Error adding shop');
            }
        }

        function editShop(shopId) {
            alert(`Edit shop ${shopId} - Feature coming soon!`);
        }

        async function deleteShop(shopId) {
            if (confirm('Are you sure you want to delete this shop?')) {
                try {
                    const data = await apiCall(`/admin/shops/${shopId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadShops();
                        alert('Shop deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting shop:', error);
                    alert('Error deleting shop');
                }
            }
        }

        // Product Management Functions
        async function loadProducts() {
            try {
                const data = await apiCall('/admin/products');
                if (data && data.success) {
                    displayProducts(data.data.items);
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        function displayProducts(products) {
            const tbody = document.getElementById('products-table');
            tbody.innerHTML = products.map(product => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.shop ? product.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${parseFloat(product.price).toFixed(2)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full ${product.is_food ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${product.is_food ? 'Yes' : 'No'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editProduct(${product.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function showAddProductModal() {
            // Load shops for dropdown
            try {
                const data = await apiCall('/admin/shops?per_page=100');
                if (data && data.success) {
                    const select = document.getElementById('product-shop-select');
                    select.innerHTML = '<option value="">Select a shop...</option>';
                    data.data.items.forEach(shop => {
                        select.innerHTML += `<option value="${shop.id}">${shop.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading shops:', error);
            }
            document.getElementById('add-product-modal').classList.remove('hidden');
        }

        function closeAddProductModal() {
            document.getElementById('add-product-modal').classList.add('hidden');
            document.getElementById('add-product-form').reset();
        }

        async function addProduct(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);
            data.is_food = document.getElementById('product_is_food').checked ? 1 : 0;

            try {
                const response = await apiCall('/admin/products', {
                    method: 'POST',
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddProductModal();
                    loadProducts();
                    alert('Product added successfully');
                }
            } catch (error) {
                console.error('Error adding product:', error);
                alert('Error adding product');
            }
        }

        function editProduct(productId) {
            alert(`Edit product ${productId} - Feature coming soon!`);
        }

        async function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                try {
                    const data = await apiCall(`/admin/products/${productId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadProducts();
                        alert('Product deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting product:', error);
                    alert('Error deleting product');
                }
            }
        }

        // Review Management Functions
        async function loadReviews() {
            try {
                const data = await apiCall('/admin/reviews');
                if (data && data.success) {
                    displayReviews(data.data.items);
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
            }
        }

        function displayReviews(reviews) {
            const tbody = document.getElementById('reviews-table');
            tbody.innerHTML = reviews.map(review => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.user ? (review.user.first_name || review.user.name || 'N/A') : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.shop ? review.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.product ? review.product.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${'⭐'.repeat(review.rating)}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${review.comment || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editReview(${review.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteReview(${review.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function showAddReviewModal() {
            // Load users for dropdown
            try {
                const userData = await apiCall('/admin/users?per_page=100');
                if (userData && userData.success) {
                    const userSelect = document.getElementById('review-user-select');
                    userSelect.innerHTML = '<option value="">Select a user...</option>';
                    userData.data.items.forEach(user => {
                        const name = user.first_name || user.name || user.email || 'User';
                        userSelect.innerHTML += `<option value="${user.id}">${name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }

            // Load shops for dropdown
            try {
                const shopData = await apiCall('/admin/shops?per_page=100');
                if (shopData && shopData.success) {
                    const shopSelect = document.getElementById('review-shop-select');
                    shopSelect.innerHTML = '<option value="">Select a shop...</option>';
                    shopData.data.items.forEach(shop => {
                        shopSelect.innerHTML += `<option value="${shop.id}">${shop.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading shops:', error);
            }

            // Load products for dropdown
            try {
                const productData = await apiCall('/admin/products?per_page=100');
                if (productData && productData.success) {
                    const productSelect = document.getElementById('review-product-select');
                    productSelect.innerHTML = '<option value="">Select a product...</option>';
                    productData.data.items.forEach(product => {
                        productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }

            document.getElementById('add-review-modal').classList.remove('hidden');
        }

        function closeAddReviewModal() {
            document.getElementById('add-review-modal').classList.add('hidden');
            document.getElementById('add-review-form').reset();
        }

        async function addReview(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            // Convert empty strings to null for optional fields
            if (!data.product_id || data.product_id === '') {
                data.product_id = null;
            }
            if (!data.shop_id || data.shop_id === '') {
                data.shop_id = null;
            }

            // Ensure at least one is provided
            if (!data.shop_id && !data.product_id) {
                alert('Please select either a shop or a product');
                return;
            }

            try {
                const response = await apiCall('/admin/reviews', {
                    method: 'POST',
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddReviewModal();
                    loadReviews();
                    alert('Review added successfully');
                }
            } catch (error) {
                console.error('Error adding review:', error);
                alert('Error adding review');
            }
        }

        function editReview(reviewId) {
            alert(`Edit review ${reviewId} - Feature coming soon!`);
        }

        async function deleteReview(reviewId) {
            if (confirm('Are you sure you want to delete this review?')) {
                try {
                    const data = await apiCall(`/admin/reviews/${reviewId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadReviews();
                        alert('Review deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting review:', error);
                    alert('Error deleting review');
                }
            }
        }
    </script>
</body>
</html>
