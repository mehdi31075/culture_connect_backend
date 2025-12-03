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
                        <a href="#dashboard" onclick="showSection('dashboard', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100 active">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#users" onclick="showSection('users', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-users mr-3"></i>
                            Users
                        </a>
                    </li>
                    <li>
                        <a href="#pavilions" onclick="showSection('pavilions', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-building mr-3"></i>
                            Pavilions
                        </a>
                    </li>
                    <li>
                        <a href="#banners" onclick="showSection('banners', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-images mr-3"></i>
                            Banners
                        </a>
                    </li>
                    <li>
                        <a href="#shops" onclick="showSection('shops', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-store mr-3"></i>
                            Shops
                        </a>
                    </li>
                    <li>
                        <a href="#products" onclick="showSection('products', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-box mr-3"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="#product-tags" onclick="showSection('product-tags', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-tags mr-3"></i>
                            Product & Food Tags
                        </a>
                    </li>
                    <li>
                        <a href="#foods" onclick="showSection('foods', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-utensils mr-3"></i>
                            Foods
                        </a>
                    </li>
                    <li>
                        <a href="#offers" onclick="showSection('offers', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-percent mr-3"></i>
                            Offers
                        </a>
                    </li>
                    <li>
                        <a href="#event-tags" onclick="showSection('event-tags', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-tag mr-3"></i>
                            Event Tags
                        </a>
                    </li>
                    <li>
                        <a href="#event-features" onclick="showSection('event-features', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-star mr-3"></i>
                            Event Features
                        </a>
                    </li>
                    <li>
                        <a href="#events" onclick="showSection('events', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-calendar mr-3"></i>
                            Events
                        </a>
                    </li>
                    <li>
                        <a href="#orders" onclick="showSection('orders', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-shopping-cart mr-3"></i>
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="#reviews" onclick="showSection('reviews', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-star mr-3"></i>
                            Reviews
                        </a>
                    </li>
                    <li>
                        <a href="#map" onclick="showSection('map', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                            <i class="fas fa-map mr-3"></i>
                            Map
                        </a>
                    </li>
                    <li>
                        <a href="#notifications" onclick="showSection('notifications', this)" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After Discount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Is Food</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tags</th>
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

            <!-- Product Tags Section (also used for Food Tags) -->
            <div id="product-tags-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Product & Food Tag Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage tags used to categorize products and foods (shared tags)</span>
                            <button onclick="showAddProductTagModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Tag
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foods</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="product-tags-table">
                                    <!-- Tags will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foods Section -->
            <div id="foods-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Food Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage food items</span>
                            <button onclick="showAddFoodModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Food
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After Discount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trending</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tags</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="foods-table">
                                    <!-- Foods will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offers Section -->
            <div id="offers-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Offer Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage offers and discounts</span>
                            <button onclick="showAddOfferModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Offer
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product/Food</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="offers-table">
                                    <!-- Offers will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Tags Section -->
            <div id="event-tags-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Event Tag Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage tags used to categorize events</span>
                            <button onclick="showAddEventTagModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Tag
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Events</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="event-tags-table">
                                    <!-- Tags will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Features Section -->
            <div id="event-features-section" class="section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Event Features</h2>
                            <span class="text-gray-600">Manage features used to describe events (e.g., outdoor, accessible)</span>
                        </div>
                        <button onclick="showAddEventFeatureModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            <i class="fas fa-plus mr-2"></i>Add Feature
                        </button>
                    </div>
                    <div id="event-features-table-container">
                        <p class="text-gray-500">Loading event features...</p>
                    </div>
                </div>
            </div>

            <!-- Events Section -->
            <div id="events-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Event Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage events</span>
                            <button onclick="showAddEventModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Event
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavilion</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendees/Capacity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After Discount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="events-table">
                                    <!-- Events will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="orders-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Order Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="mb-4">
                            <input type="text" id="orders-search" placeholder="Search by user name, email, or order ID..." class="w-full px-4 py-2 border rounded-lg mb-4">
                            <select id="orders-status-filter" class="px-4 py-2 border rounded-lg">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-table">
                                    <!-- Orders will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Food</th>
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

            <div id="events-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Event Management</h2>
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Manage events</span>
                            <button onclick="showAddEventModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                <i class="fas fa-plus"></i> Add Event
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavilion</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendees/Capacity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After Discount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="events-table">
                                    <!-- Events will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div id="map-section" class="section hidden">
                <h2 class="text-2xl font-bold mb-6">Map View</h2>
                <div class="bg-white rounded-lg shadow p-4 mb-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-sm">Pavilions</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-sm">Shops</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-500 rounded"></div>
                            <span class="text-sm">Events</span>
                        </div>
                        <button onclick="refreshMap()" class="ml-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Map
                        </button>
                    </div>
                </div>
                <div id="admin-map" class="w-full h-[calc(100vh-300px)] rounded-lg border shadow"></div>
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
                <form id="add-banner-form" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title (Optional)</label>
                            <input type="text" name="title" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
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
                            <input type="file" name="image" id="banner-image-input" accept="image/*,.svg" class="w-full border rounded px-3 py-2">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image when editing</p>
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
                    <h3 id="shop-modal-title" class="text-lg font-semibold">Add New Shop</h3>
                    <button onclick="closeAddShopModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-shop-form" onsubmit="addShop(event)" class="p-6">
                    <input type="hidden" id="shop-id" name="id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pavilion (Optional)</label>
                            <select name="pavilion_id" id="shop-pavilion-select" class="w-full border rounded px-3 py-2">
                                <option value="">None - Standalone Shop</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" name="name" id="shop-name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="shop-description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" id="shop-type" class="w-full border rounded px-3 py-2">
                                <option value="shop">Shop</option>
                                <option value="food_truck">Food Truck</option>
                                <option value="restaurant">Restaurant</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                            <input type="text" name="location_name" id="shop-location-name" placeholder="e.g., Food Court A" class="w-full border rounded px-3 py-2" maxlength="160">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="number" name="lat" id="shop-lat" step="0.000001" min="-90" max="90" placeholder="e.g., 25.2048" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="number" name="lng" id="shop-lng" step="0.000001" min="-180" max="180" placeholder="e.g., 55.2708" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddShopModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="shop-submit-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                    <h3 id="product-modal-title" class="text-lg font-semibold">Add New Product</h3>
                    <button onclick="closeAddProductModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-product-form" onsubmit="addProduct(event)" class="p-6">
                    <input type="hidden" id="product-id" name="product_id" value="">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price ({{ config('app.currency.symbol', '$') }}) *</label>
                            <input type="number" name="price" id="product-price" step="0.01" min="0" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">After Discount Price ({{ config('app.currency.symbol', '$') }}) - Optional</label>
                            <input type="number" name="discounted_price" id="product-discounted-price" step="0.01" min="0" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <div class="relative">
                                <div id="product-tags-field" class="flex flex-wrap items-center gap-2 border rounded px-3 py-2 bg-white cursor-text" onclick="focusProductTagInput()">
                                    <div id="product-selected-tags" class="flex flex-wrap gap-2"></div>
                                    <input id="product-tag-input" type="text" class="flex-1 min-w-[120px] border-0 focus:outline-none focus:ring-0 text-sm py-1" placeholder="Type to add tags..." autocomplete="off">
                                </div>
                                <div id="product-tag-suggestions" class="absolute left-0 right-0 mt-1 bg-white border rounded shadow hidden z-10 max-h-48 overflow-y-auto"></div>
                            </div>
                            <div id="product-tags-hidden-inputs"></div>
                            <p class="text-xs text-gray-500 mt-1">Type to search for an existing tag. If it doesn’t exist, press Enter to create it.</p>
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

    <!-- Product Tag Modal -->
    <div id="product-tag-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="product-tag-modal-title" class="text-lg font-semibold">Add Tag</h3>
                    <button onclick="closeProductTagModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="product-tag-form" onsubmit="saveProductTag(event)" class="p-6">
                    <input type="hidden" id="product-tag-id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" id="product-tag-name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tag Type *</label>
                            <select id="product-tag-type" required class="w-full border rounded px-3 py-2">
                                <option value="both">Both (Product & Food)</option>
                                <option value="product">Product Only</option>
                                <option value="food">Food Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeProductTagModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Save Tag
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Event Tag Modal -->
    <div id="event-tag-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="event-tag-modal-title" class="text-lg font-semibold">Add Tag</h3>
                    <button onclick="closeEventTagModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="event-tag-form" onsubmit="saveEventTag(event)" class="p-6">
                    <input type="hidden" id="event-tag-id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" id="event-tag-name" required class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEventTagModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Save Tag
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Event Feature Modal -->
    <div id="event-feature-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="event-feature-modal-title" class="text-lg font-semibold">Add New Feature</h3>
                    <button onclick="closeEventFeatureModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="event-feature-form" onsubmit="saveEventFeature(event)" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" id="event-feature-name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEventFeatureModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Save Feature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Food Modal -->
    <div id="add-food-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="food-modal-title" class="text-lg font-semibold">Add New Food</h3>
                    <button onclick="closeAddFoodModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-food-form" class="p-6 space-y-4">
                    <input type="hidden" id="food-id" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shop *</label>
                        <select id="food-shop-select" name="shop_id" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select a shop...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" id="food-name" name="name" required class="w-full px-3 py-2 border rounded-lg" maxlength="160">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="food-description" name="description" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price ({{ config('app.currency.symbol', '$') }}) *</label>
                        <input type="number" id="food-price" name="price" step="0.01" min="0" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">After Discount Price ({{ config('app.currency.symbol', '$') }}) - Optional</label>
                        <input type="number" id="food-discounted-price" name="discounted_price" step="0.01" min="0" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Images</label>
                        <input type="file" id="food-images" name="images[]" multiple accept="image/*" class="w-full px-3 py-2 border rounded-lg">
                        <div id="food-images-preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="food-is-trending" name="is_trending" class="mr-2">
                                <span class="text-sm font-medium text-gray-700">Is Trending</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trending Position</label>
                            <input type="number" id="food-trending-position" name="trending_position" min="1" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trending Score (%)</label>
                            <input type="number" id="food-trending-score" name="trending_score" step="0.01" min="0" max="100" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preparation Time</label>
                        <input type="text" id="food-preparation-time" name="preparation_time" placeholder="e.g., 15 minutes, 30-45 min" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="food-is-available" name="is_available" checked class="mr-2">
                            <span class="text-sm font-medium text-gray-700">Is Available</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <div class="relative">
                            <div id="food-tags-field" class="flex flex-wrap items-center gap-2 border rounded px-3 py-2 bg-white cursor-text" onclick="focusFoodTagInput()">
                                <div id="food-selected-tags" class="flex flex-wrap gap-2"></div>
                                <input id="food-tag-input" type="text" class="flex-1 min-w-[120px] border-0 focus:outline-none focus:ring-0 text-sm py-1" placeholder="Type to add tags..." autocomplete="off" oninput="updateFoodTagSuggestions(this.value)" onkeydown="handleFoodTagInput(event)">
                            </div>
                            <div id="food-tag-suggestions" class="absolute left-0 right-0 mt-1 bg-white border rounded shadow hidden z-10 max-h-48 overflow-y-auto"></div>
                        </div>
                        <div id="food-tags-hidden-inputs"></div>
                        <p class="text-xs text-gray-500 mt-1">Type to search for an existing tag. If it doesn't exist, press Enter to create it.</p>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeAddFoodModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Offer Modal -->
    <div id="add-offer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="offer-modal-title" class="text-lg font-semibold">Add New Offer</h3>
                    <button onclick="closeAddOfferModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-offer-form" class="p-6 space-y-4">
                    <input type="hidden" id="offer-id" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shop *</label>
                        <select id="offer-shop-select" name="shop_id" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select a shop...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product (Optional)</label>
                        <select id="offer-product-select" name="product_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">No specific product</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Food (Optional)</label>
                        <select id="offer-food-select" name="food_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">No specific food</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="offer-title" name="title" required class="w-full px-3 py-2 border rounded-lg" maxlength="255">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="offer-description" name="description" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select id="offer-discount-type" name="discount_type" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="percent">Percent (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value * <span id="offer-value-hint" class="text-gray-500 text-xs">(Percent or {{ config('app.currency.symbol', '$') }} for fixed)</span></label>
                        <input type="number" id="offer-value" name="value" step="0.01" min="0" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="offer-is-bundle" name="is_bundle" class="mr-2">
                            <span class="text-sm font-medium text-gray-700">Is Bundle</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                        <input type="datetime-local" id="offer-start-at" name="start_at" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                        <input type="datetime-local" id="offer-end-at" name="end_at" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeAddOfferModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Event Modal -->
    <div id="add-event-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 id="event-modal-title" class="text-lg font-semibold">Add New Event</h3>
                    <button onclick="closeAddEventModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-event-form" onsubmit="addEvent(event)" class="p-6">
                    <input type="hidden" name="event_id" id="event-id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="event-title" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="event-description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pavilion</label>
                            <select name="pavilion_id" id="event-pavilion-select" class="w-full border rounded px-3 py-2">
                                <option value="">Select a pavilion...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stage</label>
                            <input type="text" name="stage" id="event-stage" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
                                <input type="datetime-local" name="start_time" id="event-start-time" required class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Time *</label>
                                <input type="datetime-local" name="end_time" id="event-end-time" required class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price ({{ config('app.currency.symbol', '$') }}) - Use -1 for Free</label>
                                <input type="number" name="price" id="event-price" step="0.01" min="-1" value="-1.00" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                                <input type="number" name="capacity" id="event-capacity" min="0" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <div class="relative">
                                <div id="event-tags-field" class="flex flex-wrap items-center gap-2 border rounded px-3 py-2 bg-white cursor-text" onclick="focusEventTagInput()">
                                    <div id="event-selected-tags" class="flex flex-wrap gap-2"></div>
                                    <input id="event-tag-input" type="text" class="flex-1 min-w-[120px] border-0 focus:outline-none focus:ring-0 text-sm py-1" placeholder="Type to add tags..." autocomplete="off" oninput="updateEventTagSuggestions(this.value)" onkeydown="handleEventTagInput(event)">
                                </div>
                                <div id="event-tag-suggestions" class="absolute left-0 right-0 mt-1 bg-white border rounded shadow hidden z-10 max-h-48 overflow-y-auto"></div>
                            </div>
                            <div id="event-tags-hidden-inputs"></div>
                            <p class="text-xs text-gray-500 mt-1">Type to search for an existing tag. If it doesn't exist, press Enter to create it.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Banners (Images)</label>
                            <div id="event-banners-container" class="space-y-2">
                                <div id="event-banners-list" class="space-y-2"></div>
                                <div class="flex items-center gap-2">
                                    <input type="file" id="event-banner-upload" accept="image/*" multiple class="hidden" onchange="handleEventBannerUpload(event)">
                                    <button type="button" onclick="document.getElementById('event-banner-upload').click()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                                        <i class="fas fa-plus mr-1"></i> Add Banner Image
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Upload multiple banner images for this event</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                            <div class="relative">
                                <div id="event-features-field" class="flex flex-wrap items-center gap-2 border rounded px-3 py-2 bg-white cursor-text" onclick="focusEventFeatureInput()">
                                    <div id="event-selected-features" class="flex flex-wrap gap-2"></div>
                                    <input id="event-feature-input" type="text" class="flex-1 min-w-[120px] border-0 focus:outline-none focus:ring-0 text-sm py-1" placeholder="Type to add features..." autocomplete="off" oninput="updateEventFeatureSuggestions(this.value)" onkeydown="handleEventFeatureInput(event)">
                                </div>
                                <div id="event-feature-suggestions" class="absolute left-0 right-0 mt-1 bg-white border rounded shadow hidden z-10 max-h-48 overflow-y-auto"></div>
                            </div>
                            <div id="event-features-hidden-inputs"></div>
                            <p class="text-xs text-gray-500 mt-1">Type to search for an existing feature. If it doesn't exist, press Enter to create it.</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Save Event
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shop (Required if no product or food)</label>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Food (Optional)</label>
                            <select name="food_id" id="review-food-select" class="w-full border rounded px-3 py-2">
                                <option value="">Select a food...</option>
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
        let productTagsCache = [];
        let currentEditingProductTagId = null;
        let selectedProductTagIds = [];
        let selectedProductNewTags = [];
        // Food tag management (separate from product tags)
        let selectedFoodTagIds = [];
        let selectedFoodNewTags = [];
        let currentEditingEventId = null;
        let eventTagsCache = [];
        let currentEditingEventTagId = null;
        let selectedEventTagIds = [];
        let selectedEventNewTags = [];
        let selectedEventFeatureIds = [];
        let selectedEventNewFeatures = [];
        let eventBanners = []; // Array of banner image URLs
        let eventFeaturesCache = [];
        let currentEditingEventFeatureId = null;

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
            ensureProductTagsCache();

            const tagInput = document.getElementById('product-tag-input');
            if (tagInput) {
                tagInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' || event.key === ',') {
                        event.preventDefault();
                        handleProductTagInputCommit();
                    } else if (event.key === 'Backspace' && !tagInput.value) {
                        if (selectedProductNewTags.length) {
                            selectedProductNewTags.pop();
                            renderSelectedProductTags();
                        } else if (selectedProductTagIds.length) {
                            selectedProductTagIds.pop();
                            renderSelectedProductTags();
                        }
                        updateProductTagSuggestions('');
                    }
                });

                tagInput.addEventListener('input', function(event) {
                    updateProductTagSuggestions(event.target.value);
                });

                tagInput.addEventListener('blur', function() {
                    setTimeout(() => {
                        handleProductTagInputCommit();
                        const suggestions = document.getElementById('product-tag-suggestions');
                        if (suggestions) {
                            suggestions.classList.add('hidden');
                        }
                    }, 150);
                });
            }

            const suggestions = document.getElementById('product-tag-suggestions');
            if (suggestions) {
                suggestions.addEventListener('mousedown', function(event) {
                    const button = event.target.closest('[data-tag-id]');
                    if (!button) return;
                    event.preventDefault();
                    addExistingProductTag(button.getAttribute('data-tag-id'));
                });
            }

            const selectedWrapper = document.getElementById('product-selected-tags');
            if (selectedWrapper) {
                selectedWrapper.addEventListener('click', function(event) {
                    const removeExisting = event.target.closest('button[data-remove-existing]');
                    const removeNew = event.target.closest('button[data-remove-new]');
                    if (removeExisting) {
                        removeExistingProductTag(Number(removeExisting.getAttribute('data-remove-existing')));
                    } else if (removeNew) {
                        removeNewProductTag(removeNew.getAttribute('data-remove-new'));
                    }
                });
            }

            // Food tag input handlers are now handled by inline oninput and onkeydown handlers
        });

        // Currency configuration
        const currencyConfig = {
            symbol: '{{ config("app.currency.symbol", "$") }}',
            code: '{{ config("app.currency.code", "USD") }}',
            position: '{{ config("app.currency.position", "before") }}'
        };

        // Format price with currency
        function formatPrice(price) {
            if (price === null || price === undefined || price === '' || parseFloat(price) < 0) {
                return 'Free';
            }
            const formattedPrice = parseFloat(price).toFixed(2);
            if (currencyConfig.position === 'after') {
                return `${formattedPrice} ${currencyConfig.symbol}`;
            } else {
                return `${currencyConfig.symbol}${formattedPrice}`;
            }
        }

        // API helper function
        async function apiCall(endpoint, options = {}) {
            // Check if body is FormData - if so, don't set Content-Type (browser will set it with boundary)
            const isFormData = options.body instanceof FormData;

            const defaultOptions = {
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            };

            // Only set Content-Type for non-FormData requests
            if (!isFormData) {
                defaultOptions.headers['Content-Type'] = 'application/json';
            }

            // Merge options, but don't override headers completely - merge them
            const mergedOptions = {
                ...defaultOptions,
                ...options,
                headers: {
                    ...defaultOptions.headers,
                    ...(options.headers || {})
                }
            };

            const response = await fetch(`/api${endpoint}`, mergedOptions);

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
            document.getElementById('total-revenue').textContent = formatPrice(stats.revenue.total_revenue || 0);
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${((user.first_name || '') + ' ' + (user.last_name || '')).trim() || 'N/A'}</td>
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
        function showSection(sectionName, clickedElement) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });

            // Remove active class from all nav items
            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('active');
            });

            // Show selected section
            const section = document.getElementById(`${sectionName}-section`);
            if (section) {
                section.classList.remove('hidden');
            }

            // Add active class to clicked nav item
            if (clickedElement) {
                clickedElement.classList.add('active');
            } else {
                // Fallback: find the link by href
                const link = document.querySelector(`nav a[href="#${sectionName}"]`);
                if (link) {
                    link.classList.add('active');
                }
            }

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
                ensureProductTagsCache();
            } else if (sectionName === 'product-tags') {
                loadProductTags(true); // Force refresh to ensure tags are loaded
            } else if (sectionName === 'event-tags') {
                loadEventTags();
            } else if (sectionName === 'event-features') {
                loadEventFeatures();
            } else if (sectionName === 'events') {
                loadEvents();
            } else if (sectionName === 'foods') {
                loadFoods();
                ensureProductTagsCache();
            } else if (sectionName === 'offers') {
                loadOffers();
            } else if (sectionName === 'orders') {
                loadOrders();
            } else if (sectionName === 'reviews') {
                loadReviews();
            } else if (sectionName === 'map') {
                loadMap();
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
                const data = await apiCall(`/pavilions/${pavilionId}`);
                if (!data || !data.success) {
                    alert('Failed to load pavilion data');
                    return;
                }

                const pavilion = data.data;

                // Populate form fields
                document.getElementById('pavilion_id').value = pavilion.id || '';
                document.getElementById('pavilion_name').value = pavilion.name || '';
                document.getElementById('pavilion_description').value = pavilion.description || '';
                document.getElementById('pavilion_country').value = pavilion.country || '';
                document.getElementById('pavilion_lat').value = pavilion.lat !== null && pavilion.lat !== undefined ? pavilion.lat : '';
                document.getElementById('pavilion_lng').value = pavilion.lng !== null && pavilion.lng !== undefined ? pavilion.lng : '';
                document.getElementById('pavilion_open_hours').value = pavilion.open_hours || '';

                // Debug: Verify form values are set
                console.log('Form values after populating:', {
                    name: document.getElementById('pavilion_name').value,
                    description: document.getElementById('pavilion_description').value,
                    country: document.getElementById('pavilion_country').value,
                    lat: document.getElementById('pavilion_lat').value,
                    lng: document.getElementById('pavilion_lng').value,
                    open_hours: document.getElementById('pavilion_open_hours').value
                });

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

            // Build FormData manually to ensure all values are captured
            const formData = new FormData();
            const pavilionId = document.getElementById('pavilion_id').value;

            // Add all form fields explicitly - always add required fields
            const name = document.getElementById('pavilion_name').value;
            const description = document.getElementById('pavilion_description').value;
            const country = document.getElementById('pavilion_country').value;
            const lat = document.getElementById('pavilion_lat').value;
            const lng = document.getElementById('pavilion_lng').value;
            const openHours = document.getElementById('pavilion_open_hours').value;
            const iconFile = form.querySelector('input[name="icon"]').files[0];

            // Always add required fields (name and description are required)
            formData.append('name', name || '');
            formData.append('description', description || '');

            // Add optional fields only if they have values
            if (country) formData.append('country', country);
            if (lat) formData.append('lat', lat);
            if (lng) formData.append('lng', lng);
            if (openHours) formData.append('open_hours', openHours);
            if (iconFile) formData.append('icon', iconFile);

            const isEdit = pavilionId && pavilionId !== '';

            // Debug: Log form data to verify values are captured
            console.log('Form data before processing:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            try {
                // Use POST for both create and update (route accepts both PUT and POST)
                // Laravel has issues parsing FormData with PUT requests, so we use POST
                const url = isEdit ? `/api/admin/pavilions/${pavilionId}` : '/api/admin/pavilions';
                const method = 'POST'; // Use POST for FormData (route accepts both PUT and POST)

                // Don't set Content-Type header - let browser set it with boundary for FormData
                const headers = {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                    // Note: We intentionally don't set Content-Type - browser will set it with boundary
                };

                const response = await fetch(url, {
                    method: method,
                    headers: headers,
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
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${banner.description || ''}">${banner.description || '—'}</td>
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
            const form = document.getElementById('add-banner-form');
            form.onsubmit = addBanner;
            const imageInput = document.getElementById('banner-image-input');
            if (imageInput) {
                imageInput.required = true;
            }
            document.getElementById('add-banner-modal').classList.remove('hidden');
        }

        function closeAddBannerModal() {
            document.getElementById('add-banner-modal').classList.add('hidden');
            const form = document.getElementById('add-banner-form');
            form.reset();
            // Reset modal title and button
            document.querySelector('#add-banner-modal h3').textContent = 'Add New Banner';
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Add Banner';
            // Reset form onsubmit to original
            form.onsubmit = addBanner;
            form.removeAttribute('data-banner-id');
            // Reset image input required attribute
            const imageInput = document.getElementById('banner-image-input');
            if (imageInput) {
                imageInput.required = true;
            }
        }

        async function addBanner(event) {
            event.preventDefault();

            const form = event.target || document.getElementById('add-banner-form');
            const formData = new FormData(form);
            // Normalize checkbox value to 1/0 for backend boolean validation
            const isActiveEl = document.getElementById('banner_is_active');
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

        async function editBanner(id) {
            try {
                const data = await apiCall(`/admin/banners`);
                if (!data || !data.success) {
                    alert('Failed to load banner data');
                    return;
                }

                const banner = data.data.items.find(b => b.id === id);
                if (!banner) {
                    alert('Banner not found');
                    return;
                }

                // Populate form fields
                const form = document.getElementById('add-banner-form');
                form.querySelector('input[name="title"]').value = banner.title || '';
                form.querySelector('textarea[name="description"]').value = banner.description || '';
                form.querySelector('input[name="link"]').value = banner.link || '';
                form.querySelector('input[name="order"]').value = banner.order ?? 0;
                document.getElementById('banner_is_active').checked = banner.is_active ?? true;

                // Make image field optional for editing
                const imageInput = document.getElementById('banner-image-input');
                if (imageInput) {
                    imageInput.required = false;
                }

                // Update modal title and button
                document.querySelector('#add-banner-modal h3').textContent = 'Edit Banner';
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Update Banner';

                // Store banner ID for update
                form.dataset.bannerId = id;

                // Replace form onsubmit
                form.onsubmit = (e) => {
                    e.preventDefault();
                    updateBanner(id, e);
                };

                // Show modal
                document.getElementById('add-banner-modal').classList.remove('hidden');
            } catch (err) {
                console.error('Error loading banner:', err);
                alert('Error loading banner data');
            }
        }

        async function updateBanner(id, event) {
            event.preventDefault();

            const form = document.getElementById('add-banner-form');
            const formData = new FormData();

            // Explicitly add all form fields to ensure they're sent
            const title = form.querySelector('input[name="title"]').value;
            const description = form.querySelector('textarea[name="description"]').value;
            const link = form.querySelector('input[name="link"]').value;
            const order = form.querySelector('input[name="order"]').value;
            const imageInput = form.querySelector('input[name="image"]');
            const isActiveEl = document.getElementById('banner_is_active');

            if (title !== null && title !== undefined) formData.append('title', title);
            if (description !== null && description !== undefined) formData.append('description', description);
            if (link !== null && link !== undefined) formData.append('link', link);
            if (order !== null && order !== undefined) formData.append('order', order);
            formData.append('is_active', isActiveEl && isActiveEl.checked ? '1' : '0');

            // Only append image if a new file is selected
            if (imageInput && imageInput.files && imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            try {
                const response = await fetch(`/api/admin/banners/${id}`, {
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
                    alert('Banner updated successfully!');
                } else {
                    let msg = data.message || 'Failed to update banner';
                    if (data.errors) {
                        msg += '\n';
                        for (const k in data.errors) msg += `${k}: ${data.errors[k].join(', ')}\n`;
                    }
                    alert(msg);
                }
            } catch (err) {
                console.error('Error updating banner:', err);
                alert('Error updating banner');
            }
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
            tbody.innerHTML = shops.map(shop => {
                const locationInfo = shop.location_name
                    ? `${shop.location_name}${shop.lat && shop.lng ? ` (${parseFloat(shop.lat).toFixed(6)}, ${parseFloat(shop.lng).toFixed(6)})` : ''}`
                    : (shop.lat && shop.lng ? `(${parseFloat(shop.lat).toFixed(6)}, ${parseFloat(shop.lng).toFixed(6)})` : 'N/A');

                return `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.pavilion ? shop.pavilion.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${shop.type || 'shop'}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${shop.description || 'N/A'}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${locationInfo}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editShop(${shop.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteShop(${shop.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        async function showAddShopModal() {
            // Load pavilions for dropdown
            try {
                const data = await apiCall('/admin/pavilions?per_page=100');
                if (data && data.success) {
                    const select = document.getElementById('shop-pavilion-select');
                    select.innerHTML = '<option value="">None - Standalone Shop</option>';
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
            document.getElementById('shop-id').value = '';
            document.getElementById('shop-modal-title').textContent = 'Add New Shop';
            document.getElementById('shop-submit-btn').textContent = 'Add Shop';
        }

        async function addShop(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            const shopId = document.getElementById('shop-id').value;
            const isEdit = shopId !== '';

            // Remove id from data if creating (not editing)
            if (!isEdit) {
                delete data.id;
            }

            try {
                const url = isEdit ? `/admin/shops/${shopId}` : '/admin/shops';
                const method = isEdit ? 'PUT' : 'POST';

                const response = await apiCall(url, {
                    method: method,
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddShopModal();
                    loadShops();
                    alert(`Shop ${isEdit ? 'updated' : 'added'} successfully`);
                } else if (response && response.errors) {
                    alert(Object.values(response.errors).flat().join('\n'));
                }
            } catch (error) {
                console.error(`Error ${isEdit ? 'updating' : 'adding'} shop:`, error);
                alert(`Error ${isEdit ? 'updating' : 'adding'} shop`);
            }
        }

        async function editShop(shopId) {
            try {
                const data = await apiCall(`/admin/shops/${shopId}`);
                if (data && data.success) {
                    const shop = data.data;

                    // Load pavilions first, then populate form
                    try {
                        const pavilionData = await apiCall('/admin/pavilions?per_page=100');
                        if (pavilionData && pavilionData.success) {
                            const select = document.getElementById('shop-pavilion-select');
                            select.innerHTML = '<option value="">None - Standalone Shop</option>';
                            pavilionData.data.items.forEach(pavilion => {
                                select.innerHTML += `<option value="${pavilion.id}">${pavilion.name}</option>`;
                            });
                        }
                    } catch (error) {
                        console.error('Error loading pavilions:', error);
                    }

                    // Show modal and populate form
                    document.getElementById('add-shop-modal').classList.remove('hidden');
                    document.getElementById('shop-modal-title').textContent = 'Edit Shop';
                    document.getElementById('shop-submit-btn').textContent = 'Update Shop';
                    document.getElementById('shop-id').value = shop.id;
                    document.getElementById('shop-pavilion-select').value = shop.pavilion_id || '';
                    document.getElementById('shop-name').value = shop.name || '';
                    document.getElementById('shop-description').value = shop.description || '';
                    document.getElementById('shop-type').value = shop.type || 'shop';
                    document.getElementById('shop-location-name').value = shop.location_name || '';
                    document.getElementById('shop-lat').value = shop.lat || '';
                    document.getElementById('shop-lng').value = shop.lng || '';
                } else {
                    alert('Error loading shop');
                }
            } catch (error) {
                console.error('Error loading shop:', error);
                alert('Error loading shop');
            }
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
                    displayProducts(data.data.items || []);
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        function displayProducts(products) {
            const tbody = document.getElementById('products-table');
            if (!products.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-sm text-gray-500 text-center">No products found. Add a product to get started.</td>
                    </tr>
                `;
                return;
            }
            tbody.innerHTML = products.map(product => {
                const hasDiscount = product.discounted_price && product.discounted_price < product.price;
                const originalPrice = product.price;
                const discountedPrice = product.discounted_price;

                return `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.shop ? product.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${hasDiscount ? `<span class="line-through text-gray-400">${formatPrice(originalPrice)}</span>` : formatPrice(originalPrice)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${hasDiscount
                            ? `<span class="text-green-600 font-semibold">${formatPrice(discountedPrice)}</span>`
                            : '<span class="text-gray-400">—</span>'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full ${product.is_food ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${product.is_food ? 'Yes' : 'No'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${
                            product.tags && product.tags.length
                                ? `<div class="flex flex-wrap gap-1">${product.tags.map(tag => `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${tag.name}</span>`).join('')}</div>`
                                : '—'
                        }
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
            `;
            }).join('');
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

            // Reset form
            document.getElementById('product-modal-title').textContent = 'Add New Product';
            document.getElementById('product-id').value = '';
            document.getElementById('add-product-form').reset();
            selectedProductTagIds = [];
            selectedProductNewTags = [];
            await loadProductTagOptions([], []);
            const tagInput = document.getElementById('product-tag-input');
            if (tagInput) {
                tagInput.value = '';
            }
            updateProductTagSuggestions('');

            document.getElementById('add-product-modal').classList.remove('hidden');
        }

        function closeAddProductModal() {
            document.getElementById('add-product-modal').classList.add('hidden');
            document.getElementById('add-product-form').reset();
            document.getElementById('product-id').value = '';
            document.getElementById('product-modal-title').textContent = 'Add New Product';
            selectedProductTagIds = [];
            selectedProductNewTags = [];
            const tagInput = document.getElementById('product-tag-input');
            if (tagInput) {
                tagInput.value = '';
            }
            renderSelectedProductTags();
            updateProductTagSuggestions('');
            const suggestions = document.getElementById('product-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        async function addProduct(event) {
            event.preventDefault();
            const form = event.target;
            const productId = document.getElementById('product-id').value;
            const selectedTags = Array.from(form.querySelectorAll('input[name="tags[]"]')).map(input => Number(input.value));
            const newTags = Array.from(form.querySelectorAll('input[name="new_tags[]"]')).map(input => input.value);

            const data = {
                shop_id: form.shop_id.value,
                name: form.name.value,
                description: form.description.value || null,
                price: form.price.value,
                discounted_price: document.getElementById('product-discounted-price').value || null,
                image_url: form.image_url.value || null,
                is_food: document.getElementById('product_is_food').checked ? 1 : 0,
                tags: selectedTags,
                new_tags: newTags,
            };

            try {
                const url = productId ? `/admin/products/${productId}` : '/admin/products';
                const method = productId ? 'PUT' : 'POST';
                const response = await apiCall(url, {
                    method: method,
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddProductModal();
                    loadProducts();
                    await ensureProductTagsCache(true);
                    await loadProductTagOptions([], []);
                    alert(productId ? 'Product updated successfully' : 'Product added successfully');
                } else if (response && response.errors) {
                    const messages = Object.values(response.errors).flat().join('\n');
                    alert(messages);
                } else {
                    alert(response?.message || (productId ? 'Failed to update product' : 'Failed to add product'));
                }
            } catch (error) {
                console.error('Error saving product:', error);
                alert('Error saving product');
            }
        }

        async function editProduct(productId) {
            try {
                const data = await apiCall(`/admin/products/${productId}`);
                if (data && data.success) {
                    const product = data.data;
                    await showAddProductModal();
                    document.getElementById('product-modal-title').textContent = 'Edit Product';
                    document.getElementById('product-id').value = product.id;
                    document.getElementById('product-shop-select').value = product.shop_id;
                    document.getElementById('product-name').value = product.name || '';
                    document.getElementById('product-description').value = product.description || '';
                    document.getElementById('product-price').value = product.price || '';
                    document.getElementById('product-discounted-price').value = product.discounted_price || '';
                    document.getElementById('product-image-url').value = product.image_url || '';
                    document.getElementById('product-is-food').checked = product.is_food || false;

                    // Load and set tags
                    const existingTagIds = product.tags ? product.tags.map(t => t.id) : [];
                    await loadProductTagOptions(existingTagIds, []);
                    selectedProductTagIds = existingTagIds;
                    selectedProductNewTags = [];
                    renderSelectedProductTags();
                    updateProductTagHiddenInputs();
                } else {
                    alert('Error loading product');
                }
            } catch (error) {
                console.error('Error loading product:', error);
                alert('Error loading product');
            }
        }

        async function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                const currentExistingIds = [...selectedProductTagIds];
                const currentNewNames = [...selectedProductNewTags];
                try {
                    const data = await apiCall(`/admin/products/${productId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadProducts();
                        await loadProductTags(true);
                        await loadProductTagOptions(currentExistingIds, currentNewNames);
                        alert('Product deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting product:', error);
                    alert('Error deleting product');
                }
            }
        }

        // Food Management Functions
        async function loadFoods() {
            try {
                const data = await apiCall('/admin/foods');
                if (data && data.success) {
                    displayFoods(data.data.items || []);
                }
            } catch (error) {
                console.error('Error loading foods:', error);
            }
        }

        function displayFoods(foods) {
            const tbody = document.getElementById('foods-table');
            if (!foods.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-sm text-gray-500 text-center">No foods found. Add a food to get started.</td>
                    </tr>
                `;
                return;
            }
            tbody.innerHTML = foods.map(food => {
                const hasDiscount = food.discounted_price && food.discounted_price < food.price;
                const originalPrice = food.price;
                const discountedPrice = food.discounted_price;

                return `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${food.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${food.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${food.shop ? food.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${hasDiscount ? `<span class="line-through text-gray-400">${formatPrice(originalPrice)}</span>` : formatPrice(originalPrice)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${hasDiscount
                            ? `<span class="text-green-600 font-semibold">${formatPrice(discountedPrice)}</span>`
                            : '<span class="text-gray-400">—</span>'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full ${food.is_trending ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'}">
                            ${food.is_trending ? `#${food.trending_position || 'N/A'} (${food.trending_score || 0}%)` : 'No'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${
                            food.tags && food.tags.length
                                ? `<div class="flex flex-wrap gap-1">${food.tags.map(tag => `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${tag.name}</span>`).join('')}</div>`
                                : '—'
                        }
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editFood(${food.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteFood(${food.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        async function showAddFoodModal() {
            try {
                const data = await apiCall('/admin/shops?per_page=100');
                if (data && data.success) {
                    const select = document.getElementById('food-shop-select');
                    select.innerHTML = '<option value="">Select a shop...</option>';
                    data.data.items.forEach(shop => {
                        select.innerHTML += `<option value="${shop.id}">${shop.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading shops:', error);
            }
            await loadFoodTagOptions([], []);
            document.getElementById('food-modal-title').textContent = 'Add New Food';
            document.getElementById('food-id').value = '';
            document.getElementById('add-food-form').reset();
            document.getElementById('add-food-modal').classList.remove('hidden');
        }

        function closeAddFoodModal() {
            document.getElementById('add-food-modal').classList.add('hidden');
            document.getElementById('add-food-form').reset();
            document.getElementById('food-id').value = '';
            document.getElementById('food-modal-title').textContent = 'Add New Food';
            document.getElementById('food-images-preview').innerHTML = '';
            existingFoodImagesToRemove = [];
            selectedFoodTagIds = [];
            selectedFoodNewTags = [];
            renderSelectedFoodTags();
            updateFoodTagHiddenInputs();
            const tagInput = document.getElementById('food-tag-input');
            if (tagInput) {
                tagInput.value = '';
            }
            updateFoodTagSuggestions('');
            const suggestions = document.getElementById('food-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        // Store existing images to remove
        let existingFoodImagesToRemove = [];

        function removeFoodImage(imageUrl) {
            const preview = document.getElementById('food-images-preview');
            const imgDiv = preview.querySelector(`[data-image-url="${imageUrl}"]`);
            if (imgDiv) {
                existingFoodImagesToRemove.push(imageUrl);
                imgDiv.remove();
            }
        }

        document.getElementById('add-food-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData();
            const foodId = document.getElementById('food-id').value;

            formData.append('shop_id', document.getElementById('food-shop-select').value);
            formData.append('name', document.getElementById('food-name').value);
            formData.append('description', document.getElementById('food-description').value);
            formData.append('price', document.getElementById('food-price').value);
            formData.append('discounted_price', document.getElementById('food-discounted-price').value || '');
            // Note: views_count, likes_count, and comments_count are automatically calculated
            formData.append('is_trending', document.getElementById('food-is-trending').checked ? 1 : 0);
            formData.append('trending_position', document.getElementById('food-trending-position').value || '');
            formData.append('trending_score', document.getElementById('food-trending-score').value || '');
            formData.append('preparation_time', document.getElementById('food-preparation-time').value || '');
            formData.append('is_available', document.getElementById('food-is-available').checked ? 1 : 0);
            // Note: views_count, likes_count, and comments_count are automatically calculated

            const images = document.getElementById('food-images').files;
            for (let i = 0; i < images.length; i++) {
                formData.append('images[]', images[i]);
            }

            // Add images to remove if editing
            if (foodId && existingFoodImagesToRemove.length > 0) {
                existingFoodImagesToRemove.forEach(imageUrl => {
                    formData.append('remove_images[]', imageUrl);
                });
            }

            selectedFoodTagIds.forEach(id => formData.append('tags[]', id));
            selectedFoodNewTags.forEach(name => formData.append('new_tags[]', name));

            try {
                const url = foodId ? `/admin/foods/${foodId}` : '/admin/foods';
                const method = foodId ? 'POST' : 'POST'; // Using POST for both create and update (match route)
                const response = await apiCall(url, {
                    method: method,
                    body: formData
                });
                if (response && response.success) {
                    closeAddFoodModal();
                    loadFoods();
                    alert(foodId ? 'Food updated successfully' : 'Food saved successfully');
                } else {
                    alert(response?.message || 'Failed to save food');
                }
            } catch (error) {
                console.error('Error saving food:', error);
                alert('Error saving food');
            }
        });

        async function editFood(foodId) {
            try {
                const data = await apiCall(`/admin/foods/${foodId}`);
                if (data && data.success) {
                    const food = data.data;

                    // Load shops first
                    try {
                        const shopData = await apiCall('/admin/shops?per_page=100');
                        if (shopData && shopData.success) {
                            const select = document.getElementById('food-shop-select');
                            select.innerHTML = '<option value="">Select a shop...</option>';
                            shopData.data.items.forEach(shop => {
                                select.innerHTML += `<option value="${shop.id}">${shop.name}</option>`;
                            });
                        }
                    } catch (error) {
                        console.error('Error loading shops:', error);
                    }

                    // Show modal and populate form
                    document.getElementById('add-food-modal').classList.remove('hidden');
                    document.getElementById('food-modal-title').textContent = 'Edit Food';
                    document.getElementById('food-id').value = food.id;
                    document.getElementById('food-shop-select').value = food.shop_id;
                    document.getElementById('food-name').value = food.name || '';
                    document.getElementById('food-description').value = food.description || '';
                    document.getElementById('food-price').value = food.price || '';
                    document.getElementById('food-discounted-price').value = food.discounted_price || '';
                    document.getElementById('food-is-trending').checked = food.is_trending || false;
                    document.getElementById('food-trending-position').value = food.trending_position || '';
                    document.getElementById('food-trending-score').value = food.trending_score || '';
                    document.getElementById('food-preparation-time').value = food.preparation_time || '';
                    document.getElementById('food-is-available').checked = food.is_available !== false;

                    // Display existing images
                    const imagesPreview = document.getElementById('food-images-preview');
                    imagesPreview.innerHTML = '';
                    existingFoodImagesToRemove = [];
                    if (food.images && food.images.length > 0) {
                        food.images.forEach((imageUrl, index) => {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'relative';
                            imgDiv.setAttribute('data-image-url', imageUrl);
                            const escapedUrl = imageUrl.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            imgDiv.innerHTML = `
                                <img src="${imageUrl}" alt="Food image ${index + 1}" class="w-full h-24 object-cover rounded border">
                                <button type="button" onclick="removeFoodImage('${escapedUrl}')" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            imagesPreview.appendChild(imgDiv);
                        });
                    }

                    // Load and set tags
                    const existingTagIds = food.tags ? food.tags.map(t => t.id) : [];
                    await loadFoodTagOptions(existingTagIds, []);
                    selectedFoodTagIds = existingTagIds;
                    selectedFoodNewTags = [];
                    renderSelectedFoodTags();
                    updateFoodTagHiddenInputs();
                } else {
                    alert('Error loading food');
                }
            } catch (error) {
                console.error('Error loading food:', error);
                alert('Error loading food');
            }
        }

        async function deleteFood(foodId) {
            if (confirm('Are you sure you want to delete this food?')) {
                try {
                    const data = await apiCall(`/admin/foods/${foodId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadFoods();
                        alert('Food deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting food:', error);
                    alert('Error deleting food');
                }
            }
        }

        // Offer Management Functions
        async function loadOffers() {
            try {
                const data = await apiCall('/admin/offers');
                if (data && data.success) {
                    displayOffers(data.data.items || []);
                }
            } catch (error) {
                console.error('Error loading offers:', error);
            }
        }

        function displayOffers(offers) {
            const tbody = document.getElementById('offers-table');
            if (!offers.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-sm text-gray-500 text-center">No offers found. Add an offer to get started.</td>
                    </tr>
                `;
                return;
            }
            tbody.innerHTML = offers.map(offer => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.title}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.shop ? offer.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${offer.product ? '<span class="text-blue-600">Product: ' + offer.product.name + '</span>' : ''}
                        ${offer.food ? '<span class="text-green-600">Food: ' + offer.food.name + '</span>' : ''}
                        ${!offer.product && !offer.food ? 'N/A' : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.discount_type === 'percent' ? 'Percent' : 'Fixed'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.discount_type === 'percent' ? offer.value + '%' : formatPrice(offer.value)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.start_at ? new Date(offer.start_at).toLocaleDateString() : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${offer.end_at ? new Date(offer.end_at).toLocaleDateString() : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editOffer(${offer.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteOffer(${offer.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function showAddOfferModal() {
            try {
                const shopsData = await apiCall('/admin/shops?per_page=100');
                if (shopsData && shopsData.success) {
                    const shopSelect = document.getElementById('offer-shop-select');
                    shopSelect.innerHTML = '<option value="">Select a shop...</option>';
                    shopsData.data.items.forEach(shop => {
                        shopSelect.innerHTML += `<option value="${shop.id}">${shop.name}</option>`;
                    });
                }
                const productsData = await apiCall('/admin/products?per_page=100');
                if (productsData && productsData.success) {
                    const productSelect = document.getElementById('offer-product-select');
                    productSelect.innerHTML = '<option value="">No specific product</option>';
                    productsData.data.items.forEach(product => {
                        productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                    });
                }
                const foodsData = await apiCall('/admin/foods?per_page=100');
                if (foodsData && foodsData.success) {
                    const foodSelect = document.getElementById('offer-food-select');
                    foodSelect.innerHTML = '<option value="">No specific food</option>';
                    foodsData.data.items.forEach(food => {
                        foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading data:', error);
            }

            // Add event listener to filter foods when shop is selected
            const shopSelect = document.getElementById('offer-shop-select');
            const foodSelect = document.getElementById('offer-food-select');
            if (shopSelect && foodSelect) {
                // Remove existing listeners by cloning
                const newShopSelect = shopSelect.cloneNode(true);
                shopSelect.parentNode.replaceChild(newShopSelect, shopSelect);

                newShopSelect.addEventListener('change', async function() {
                    const shopId = this.value;
                    if (shopId) {
                        try {
                            const foodData = await apiCall(`/admin/foods?shop_id=${shopId}&per_page=100`);
                            if (foodData && foodData.success) {
                                foodSelect.innerHTML = '<option value="">No specific food</option>';
                                foodData.data.items.forEach(food => {
                                    foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                                });
                            }
                        } catch (error) {
                            console.error('Error loading foods for shop:', error);
                        }
                    } else {
                        // Reload all foods
                        try {
                            const foodsData = await apiCall('/admin/foods?per_page=100');
                            if (foodsData && foodsData.success) {
                                foodSelect.innerHTML = '<option value="">No specific food</option>';
                                foodsData.data.items.forEach(food => {
                                    foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                                });
                            }
                        } catch (error) {
                            console.error('Error loading foods:', error);
                        }
                    }
                });
            }

            document.getElementById('offer-modal-title').textContent = 'Add New Offer';
            document.getElementById('offer-id').value = '';
            document.getElementById('add-offer-form').reset();
            document.getElementById('add-offer-modal').classList.remove('hidden');
        }

        function closeAddOfferModal() {
            document.getElementById('add-offer-modal').classList.add('hidden');
            document.getElementById('add-offer-form').reset();
        }

        // Update offer value hint based on discount type
        function updateOfferValueHint() {
            const discountType = document.getElementById('offer-discount-type').value;
            const hintElement = document.getElementById('offer-value-hint');
            if (hintElement) {
                if (discountType === 'percent') {
                    hintElement.textContent = '(Percentage, e.g., 20 for 20%)';
                } else {
                    hintElement.textContent = `(Fixed amount in ${currencyConfig.symbol})`;
                }
            }
        }

        // Add event listener for discount type change
        document.addEventListener('DOMContentLoaded', function() {
            const discountTypeSelect = document.getElementById('offer-discount-type');
            if (discountTypeSelect) {
                discountTypeSelect.addEventListener('change', updateOfferValueHint);
            }
        });

        document.getElementById('add-offer-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const offerId = document.getElementById('offer-id').value;
            const data = {
                shop_id: document.getElementById('offer-shop-select').value,
                product_id: document.getElementById('offer-product-select').value || null,
                food_id: document.getElementById('offer-food-select').value || null,
                title: document.getElementById('offer-title').value,
                description: document.getElementById('offer-description').value,
                discount_type: document.getElementById('offer-discount-type').value,
                value: parseFloat(document.getElementById('offer-value').value),
                is_bundle: document.getElementById('offer-is-bundle').checked,
                start_at: document.getElementById('offer-start-at').value,
                end_at: document.getElementById('offer-end-at').value,
            };

            try {
                const url = offerId ? `/admin/offers/${offerId}` : '/admin/offers';
                const method = offerId ? 'PUT' : 'POST';
                const response = await apiCall(url, {
                    method: method,
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddOfferModal();
                    loadOffers();
                    alert('Offer saved successfully');
                } else {
                    alert(response?.message || 'Failed to save offer');
                }
            } catch (error) {
                console.error('Error saving offer:', error);
                alert('Error saving offer');
            }
        });

        async function editOffer(offerId) {
            try {
                const data = await apiCall(`/admin/offers/${offerId}`);
                if (data && data.success) {
                    const offer = data.data;
                    await showAddOfferModal();

                    // Load foods for the selected shop
                    if (offer.shop_id) {
                        try {
                            const foodData = await apiCall(`/admin/foods?shop_id=${offer.shop_id}&per_page=100`);
                            if (foodData && foodData.success) {
                                const foodSelect = document.getElementById('offer-food-select');
                                foodSelect.innerHTML = '<option value="">No specific food</option>';
                                foodData.data.items.forEach(food => {
                                    foodSelect.innerHTML += `<option value="${food.id}" ${food.id === offer.food_id ? 'selected' : ''}>${food.name}</option>`;
                                });
                            }
                        } catch (error) {
                            console.error('Error loading foods for shop:', error);
                        }
                    }

                    document.getElementById('offer-modal-title').textContent = 'Edit Offer';
                    document.getElementById('offer-id').value = offer.id;
                    document.getElementById('offer-shop-select').value = offer.shop_id;
                    document.getElementById('offer-product-select').value = offer.product_id || '';
                    document.getElementById('offer-food-select').value = offer.food_id || '';
                    document.getElementById('offer-title').value = offer.title || '';
                    document.getElementById('offer-description').value = offer.description || '';
                    document.getElementById('offer-discount-type').value = offer.discount_type || 'percent';
                    document.getElementById('offer-value').value = offer.value || '';
                    updateOfferValueHint(); // Update hint based on discount type
                    document.getElementById('offer-is-bundle').checked = offer.is_bundle || false;
                    document.getElementById('offer-start-at').value = offer.start_at ? new Date(offer.start_at).toISOString().slice(0, 16) : '';
                    document.getElementById('offer-end-at').value = offer.end_at ? new Date(offer.end_at).toISOString().slice(0, 16) : '';
                }
            } catch (error) {
                console.error('Error loading offer:', error);
                alert('Error loading offer');
            }
        }

        async function deleteOffer(offerId) {
            if (confirm('Are you sure you want to delete this offer?')) {
                try {
                    const data = await apiCall(`/admin/offers/${offerId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadOffers();
                        alert('Offer deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting offer:', error);
                    alert('Error deleting offer');
                }
            }
        }

        // Order Management Functions
        async function loadOrders() {
            try {
                const search = document.getElementById('orders-search')?.value || '';
                const status = document.getElementById('orders-status-filter')?.value || '';
                let url = '/admin/orders';
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (status) params.append('status', status);
                if (params.toString()) url += '?' + params.toString();

                const data = await apiCall(url);
                if (data && data.success) {
                    displayOrders(data.data.items || []);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            }
        }

        function displayOrders(orders) {
            const tbody = document.getElementById('orders-table');
            if (!orders.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">No orders found.</td>
                    </tr>
                `;
                return;
            }
            tbody.innerHTML = orders.map(order => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#${order.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.user ? (order.user.first_name + ' ' + order.user.last_name) : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.shop ? order.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatPrice(order.total_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full ${
                            order.status === 'paid' ? 'bg-green-100 text-green-800' :
                            order.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                            'bg-yellow-100 text-yellow-800'
                        }">
                            ${order.status || 'pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.created_at ? new Date(order.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editOrder(${order.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteOrder(${order.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function editOrder(orderId) {
            const newStatus = prompt('Enter new status (pending, paid, cancelled):');
            if (newStatus && ['pending', 'paid', 'cancelled'].includes(newStatus.toLowerCase())) {
                try {
                    const data = await apiCall(`/admin/orders/${orderId}`, {
                        method: 'PUT',
                        body: JSON.stringify({ status: newStatus.toLowerCase() }),
                    });
                    if (data && data.success) {
                        loadOrders();
                        alert('Order updated successfully');
                    }
                } catch (error) {
                    console.error('Error updating order:', error);
                    alert('Error updating order');
                }
            }
        }

        async function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order?')) {
                try {
                    const data = await apiCall(`/admin/orders/${orderId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadOrders();
                        alert('Order deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting order:', error);
                    alert('Error deleting order');
                }
            }
        }

        // Add event listeners for order search and filter
        document.addEventListener('DOMContentLoaded', function() {
            const ordersSearch = document.getElementById('orders-search');
            const ordersStatusFilter = document.getElementById('orders-status-filter');
            if (ordersSearch) {
                ordersSearch.addEventListener('input', debounce(loadOrders, 500));
            }
            if (ordersStatusFilter) {
                ordersStatusFilter.addEventListener('change', loadOrders);
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Product Tag Management Functions
        async function ensureProductTagsCache(forceRefresh = false) {
            if (!forceRefresh && productTagsCache.length) {
                return productTagsCache;
            }
            try {
                const data = await apiCall('/admin/product-tags');
                if (data && data.success) {
                    productTagsCache = data.data.items || [];
                } else {
                    productTagsCache = [];
                }
            } catch (error) {
                console.error('Error loading product tags:', error);
                productTagsCache = [];
            }
            return productTagsCache;
        }

        async function loadProductTags(forceRefresh = false) {
            try {
                const tags = await ensureProductTagsCache(forceRefresh);
                displayProductTags(tags);
            } catch (error) {
                console.error('Error loading product tags:', error);
                const tbody = document.getElementById('product-tags-table');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-red-500 text-center">Error loading tags. Please refresh the page.</td>
                        </tr>
                    `;
                }
            }
        }

        function displayProductTags(tags) {
            const tbody = document.getElementById('product-tags-table');
            if (!tags.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-gray-500 text-center">No tags found. Create the first tag to get started.</td>
                    </tr>
                `;
                return;
            }

            const getTypeLabel = (type) => {
                switch(type) {
                    case 'product': return '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Product</span>';
                    case 'food': return '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Food</span>';
                    case 'both': return '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Both</span>';
                    default: return '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Both</span>';
                }
            };

            tbody.innerHTML = tags.map(tag => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${getTypeLabel(tag.tag_type || 'both')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.products_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.foods_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="showEditProductTagModal(${tag.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteProductTag(${tag.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function loadProductTagOptions(selectedIds = [], newTagNames = []) {
            await ensureProductTagsCache();
            selectedProductTagIds = (selectedIds || []).map(id => Number(id));
            selectedProductNewTags = (newTagNames || []).filter(name => !!name);
            renderSelectedProductTags();

            const tagInput = document.getElementById('product-tag-input');
            updateProductTagSuggestions(tagInput ? tagInput.value : '');
        }

        function focusProductTagInput() {
            const input = document.getElementById('product-tag-input');
            if (input) {
                input.focus();
            }
        }

        function renderSelectedProductTags() {
            const wrapper = document.getElementById('product-selected-tags');
            const foodWrapper = document.getElementById('food-selected-tags');
            const targetWrapper = wrapper || foodWrapper;
            if (!targetWrapper) return;

            if (wrapper) wrapper.innerHTML = '';
            if (foodWrapper) foodWrapper.innerHTML = '';

            selectedProductTagIds.forEach(id => {
                const tag = productTagsCache.find(item => Number(item.id) === Number(id));
                if (!tag) return;
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium';
                chip.innerHTML = `
                    <span>${tag.name}</span>
                    <button type="button" class="text-blue-600 hover:text-blue-900 focus:outline-none" data-remove-existing="${id}" aria-label="Remove tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            selectedProductNewTags.forEach(name => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium';
                chip.innerHTML = `
                    <span>${name}</span>
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none" data-remove-new="${name}" aria-label="Remove new tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            updateProductTagHiddenInputs();
        }

        function updateProductTagHiddenInputs() {
            const hiddenContainer = document.getElementById('product-tags-hidden-inputs');
            if (!hiddenContainer) return;

            hiddenContainer.innerHTML = '';

            selectedProductTagIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tags[]';
                input.value = id;
                hiddenContainer.appendChild(input);
            });

            selectedProductNewTags.forEach(name => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'new_tags[]';
                input.value = name;
                hiddenContainer.appendChild(input);
            });
        }

        function addExistingProductTag(id) {
            const numericId = Number(id);
            if (selectedProductTagIds.includes(numericId)) {
                return;
            }

            const tag = productTagsCache.find(item => Number(item.id) === numericId);
            if (!tag) return;

            selectedProductTagIds.push(numericId);
            selectedProductNewTags = selectedProductNewTags.filter(name => name.toLowerCase() !== tag.name.toLowerCase());
            renderSelectedProductTags();
            updateProductTagSuggestions('');
            clearProductTagInput();
            const suggestions = document.getElementById('product-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function addNewProductTag(name) {
            const trimmed = name.trim();
            if (!trimmed) return;

            const existing = productTagsCache.find(tag => tag.name.toLowerCase() === trimmed.toLowerCase());
            if (existing) {
                addExistingProductTag(existing.id);
                return;
            }

            if (selectedProductNewTags.some(tagName => tagName.toLowerCase() === trimmed.toLowerCase())) {
                clearProductTagInput();
                return;
            }

            selectedProductNewTags.push(trimmed);
            renderSelectedProductTags();
            updateProductTagSuggestions('');
            clearProductTagInput();
            const suggestions = document.getElementById('product-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function removeExistingProductTag(id) {
            const numericId = Number(id);
            selectedProductTagIds = selectedProductTagIds.filter(tagId => tagId !== numericId);
            renderSelectedProductTags();
            updateProductTagSuggestions(document.getElementById('product-tag-input')?.value || '');
        }

        function removeNewProductTag(name) {
            selectedProductNewTags = selectedProductNewTags.filter(tagName => tagName !== name);
            renderSelectedProductTags();
            updateProductTagSuggestions(document.getElementById('product-tag-input')?.value || '');
        }

        function clearProductTagInput() {
            const input = document.getElementById('product-tag-input');
            if (input) {
                input.value = '';
            }
        }

        function handleProductTagInputCommit() {
            const input = document.getElementById('product-tag-input');
            if (!input) return;

            const value = input.value.trim();
            if (!value) {
                input.value = '';
                return;
            }

            addNewProductTag(value);
        }

        function updateProductTagSuggestions(searchTerm = '') {
            const suggestions = document.getElementById('product-tag-suggestions');
            if (!suggestions) return;

            const normalized = searchTerm.trim().toLowerCase();
            if (!normalized.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            const availableTags = productTagsCache.filter(tag => !selectedProductTagIds.includes(Number(tag.id)));

            let matches = [];
            matches = availableTags.filter(tag => tag.name.toLowerCase().includes(normalized)).slice(0, 10);

            if (!matches.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            suggestions.innerHTML = matches.map(tag => `
                <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50" data-tag-id="${tag.id}">
                    ${tag.name}
                </button>
            `).join('');
            suggestions.classList.remove('hidden');
        }

        function showAddProductTagModal() {
            currentEditingProductTagId = null;
            document.getElementById('product-tag-modal-title').textContent = 'Add Tag';
            document.getElementById('product-tag-form').reset();
            document.getElementById('product-tag-id').value = '';
            document.getElementById('product-tag-modal').classList.remove('hidden');
        }

        function showEditProductTagModal(tagId) {
            const tag = productTagsCache.find(t => Number(t.id) === Number(tagId));
            if (!tag) {
                alert('Tag not found');
                return;
            }
            currentEditingProductTagId = tag.id;
            document.getElementById('product-tag-modal-title').textContent = 'Edit Tag';
            document.getElementById('product-tag-id').value = tag.id;
            document.getElementById('product-tag-name').value = tag.name;
            document.getElementById('product-tag-type').value = tag.tag_type || 'both';
            document.getElementById('product-tag-modal').classList.remove('hidden');
        }

        function closeProductTagModal() {
            currentEditingProductTagId = null;
            document.getElementById('product-tag-form').reset();
            document.getElementById('product-tag-id').value = '';
            document.getElementById('product-tag-modal').classList.add('hidden');
        }

        async function saveProductTag(event) {
            event.preventDefault();
            const name = document.getElementById('product-tag-name').value.trim();
            const tagType = document.getElementById('product-tag-type').value;
            if (!name) {
                alert('Please enter a tag name');
                return;
            }

            const payload = { name, tag_type: tagType };
            const isEdit = currentEditingProductTagId !== null;
            const url = isEdit ? `/admin/product-tags/${currentEditingProductTagId}` : '/admin/product-tags';
            const currentExistingIds = [...selectedProductTagIds];
            const currentNewNames = [...selectedProductNewTags];

            try {
                const response = await apiCall(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                if (response && response.success) {
                    closeProductTagModal();
                    await ensureProductTagsCache(true);
                    await loadProductTags(true);
                    await loadProductTagOptions(currentExistingIds, currentNewNames);
                    alert(isEdit ? 'Tag updated successfully' : 'Tag created successfully');
                } else if (response && response.errors) {
                    alert(Object.values(response.errors).flat().join('\n'));
                } else {
                    alert('Failed to save tag');
                }
            } catch (error) {
                console.error('Error saving tag:', error);
                alert('Error saving tag');
            }
        }

        async function deleteProductTag(tagId) {
            if (!confirm('Are you sure you want to delete this tag?')) {
                return;
            }

            const tagIdNumber = Number(tagId);
            const remainingExistingIds = selectedProductTagIds.filter(id => id !== tagIdNumber);
            const currentNewNames = [...selectedProductNewTags];
            try {
                const response = await apiCall(`/admin/product-tags/${tagId}`, { method: 'DELETE' });
                if (response && response.success) {
                    await ensureProductTagsCache(true);
                    await loadProductTags(true);
                    await loadProductTagOptions(remainingExistingIds, currentNewNames);
                    alert('Tag deleted successfully');
                } else {
                    alert(response?.message || 'Failed to delete tag');
                }
            } catch (error) {
                console.error('Error deleting tag:', error);
                alert('Error deleting tag');
            }
        }

        // Event Tag Management Functions
        async function loadEventTags() {
            try {
                const data = await apiCall('/admin/event-tags');
                if (data && data.success) {
                    eventTagsCache = data.data.items || [];
                    displayEventTags(eventTagsCache);
                } else {
                    eventTagsCache = [];
                    displayEventTags([]);
                }
            } catch (error) {
                console.error('Error loading event tags:', error);
                eventTagsCache = [];
                displayEventTags([]);
            }
        }

        function displayEventTags(tags) {
            const tbody = document.getElementById('event-tags-table');
            if (!tbody) return;

            if (!tags.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">No tags found. Create the first tag to get started.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = tags.map(tag => `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tag.events_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="showEditEventTagModal(${tag.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteEventTag(${tag.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function showAddEventTagModal() {
            currentEditingEventTagId = null;
            document.getElementById('event-tag-modal-title').textContent = 'Add Tag';
            document.getElementById('event-tag-form').reset();
            document.getElementById('event-tag-id').value = '';
            document.getElementById('event-tag-modal').classList.remove('hidden');
        }

        function showEditEventTagModal(tagId) {
            const tag = eventTagsCache.find(t => Number(t.id) === Number(tagId));
            if (!tag) {
                alert('Tag not found');
                return;
            }
            currentEditingEventTagId = tag.id;
            document.getElementById('event-tag-modal-title').textContent = 'Edit Tag';
            document.getElementById('event-tag-id').value = tag.id;
            document.getElementById('event-tag-name').value = tag.name;
            document.getElementById('event-tag-modal').classList.remove('hidden');
        }

        function closeEventTagModal() {
            currentEditingEventTagId = null;
            document.getElementById('event-tag-form').reset();
            document.getElementById('event-tag-id').value = '';
            document.getElementById('event-tag-modal').classList.add('hidden');
        }

        async function saveEventTag(event) {
            event.preventDefault();
            const name = document.getElementById('event-tag-name').value.trim();
            if (!name) {
                alert('Please enter a tag name');
                return;
            }

            const payload = { name };
            const isEdit = currentEditingEventTagId !== null;
            const url = isEdit ? `/admin/event-tags/${currentEditingEventTagId}` : '/admin/event-tags';

            try {
                const response = await apiCall(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                if (response && response.success) {
                    closeEventTagModal();
                    await loadEventTags();
                    alert(`Tag ${isEdit ? 'updated' : 'created'} successfully`);
                } else if (response && response.errors) {
                    alert(Object.values(response.errors).flat().join('\n'));
                } else {
                    alert('Failed to save tag');
                }
            } catch (error) {
                console.error(`Error ${isEdit ? 'updating' : 'creating'} tag:`, error);
                alert(`Error ${isEdit ? 'updating' : 'creating'} tag`);
            }
        }

        async function deleteEventTag(tagId) {
            if (confirm('Are you sure you want to delete this tag? This will remove it from all events.')) {
                try {
                    const response = await apiCall(`/admin/event-tags/${tagId}`, { method: 'DELETE' });
                    if (response && response.success) {
                        await loadEventTags();
                        alert('Tag deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting tag:', error);
                    alert('Error deleting tag');
                }
            }
        }

        // Event Tag Chip Management Functions (for Event form)
        async function ensureEventTagsCache(forceRefresh = false) {
            if (!forceRefresh && eventTagsCache.length) {
                return eventTagsCache;
            }
            try {
                const data = await apiCall('/admin/event-tags');
                if (data && data.success) {
                    eventTagsCache = data.data.items || [];
                } else {
                    eventTagsCache = [];
                }
            } catch (error) {
                console.error('Error loading event tags:', error);
                eventTagsCache = [];
            }
            return eventTagsCache;
        }

        async function loadEventTagOptions(selectedIds = [], newTagNames = []) {
            await ensureEventTagsCache();
            selectedEventTagIds = (selectedIds || []).map(id => Number(id));
            selectedEventNewTags = (newTagNames || []).filter(name => !!name);
            renderSelectedEventTags();

            const tagInput = document.getElementById('event-tag-input');
            updateEventTagSuggestions(tagInput ? tagInput.value : '');
        }

        function focusEventTagInput() {
            const input = document.getElementById('event-tag-input');
            if (input) {
                input.focus();
            }
        }

        function renderSelectedEventTags() {
            const wrapper = document.getElementById('event-selected-tags');
            if (!wrapper) return;

            wrapper.innerHTML = '';

            selectedEventTagIds.forEach(id => {
                const tag = eventTagsCache.find(item => Number(item.id) === Number(id));
                if (!tag) return;
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium';
                chip.innerHTML = `
                    <span>${tag.name}</span>
                    <button type="button" class="text-blue-600 hover:text-blue-900 focus:outline-none" data-remove-existing="${id}" aria-label="Remove tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            selectedEventNewTags.forEach(name => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium';
                chip.innerHTML = `
                    <span>${name}</span>
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none" data-remove-new="${name}" aria-label="Remove new tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            updateEventTagHiddenInputs();

            // Add click handlers for remove buttons
            wrapper.querySelectorAll('[data-remove-existing]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeExistingEventTag(btn.getAttribute('data-remove-existing'));
                });
            });

            wrapper.querySelectorAll('[data-remove-new]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeNewEventTag(btn.getAttribute('data-remove-new'));
                });
            });
        }

        function updateEventTagHiddenInputs() {
            const hiddenContainer = document.getElementById('event-tags-hidden-inputs');
            if (!hiddenContainer) return;

            hiddenContainer.innerHTML = '';

            selectedEventTagIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tags[]';
                input.value = id;
                hiddenContainer.appendChild(input);
            });

            selectedEventNewTags.forEach(name => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'new_tags[]';
                input.value = name;
                hiddenContainer.appendChild(input);
            });
        }

        function addExistingEventTag(id) {
            const numericId = Number(id);
            if (selectedEventTagIds.includes(numericId)) {
                return;
            }

            const tag = eventTagsCache.find(item => Number(item.id) === numericId);
            if (!tag) return;

            selectedEventTagIds.push(numericId);
            selectedEventNewTags = selectedEventNewTags.filter(name => name.toLowerCase() !== tag.name.toLowerCase());
            renderSelectedEventTags();
            updateEventTagSuggestions('');
            clearEventTagInput();
            const suggestions = document.getElementById('event-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function addNewEventTag(name) {
            const trimmed = name.trim();
            if (!trimmed) return;

            const existing = eventTagsCache.find(tag => tag.name.toLowerCase() === trimmed.toLowerCase());
            if (existing) {
                addExistingEventTag(existing.id);
                return;
            }

            if (selectedEventNewTags.some(tagName => tagName.toLowerCase() === trimmed.toLowerCase())) {
                clearEventTagInput();
                return;
            }

            selectedEventNewTags.push(trimmed);
            renderSelectedEventTags();
            updateEventTagSuggestions('');
            clearEventTagInput();
            const suggestions = document.getElementById('event-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function removeExistingEventTag(id) {
            const numericId = Number(id);
            selectedEventTagIds = selectedEventTagIds.filter(tagId => tagId !== numericId);
            renderSelectedEventTags();
            updateEventTagSuggestions(document.getElementById('event-tag-input')?.value || '');
        }

        function removeNewEventTag(name) {
            selectedEventNewTags = selectedEventNewTags.filter(tagName => tagName !== name);
            renderSelectedEventTags();
            updateEventTagSuggestions(document.getElementById('event-tag-input')?.value || '');
        }

        function clearEventTagInput() {
            const input = document.getElementById('event-tag-input');
            if (input) {
                input.value = '';
            }
        }

        function handleEventTagInputCommit() {
            const input = document.getElementById('event-tag-input');
            if (!input) return;

            const value = input.value.trim();
            if (!value) {
                input.value = '';
                return;
            }

            addNewEventTag(value);
        }

        function updateEventTagSuggestions(searchTerm = '') {
            const suggestions = document.getElementById('event-tag-suggestions');
            if (!suggestions) return;

            const normalized = searchTerm.trim().toLowerCase();
            if (!normalized.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            const availableTags = eventTagsCache.filter(tag => !selectedEventTagIds.includes(Number(tag.id)));

            let matches = [];
            matches = availableTags.filter(tag => tag.name.toLowerCase().includes(normalized)).slice(0, 10);

            if (!matches.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            suggestions.innerHTML = matches.map(tag => `
                <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50" data-tag-id="${tag.id}">
                    ${tag.name}
                </button>
            `).join('');
            suggestions.classList.remove('hidden');

            // Add click handlers
            suggestions.querySelectorAll('[data-tag-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    addExistingEventTag(btn.getAttribute('data-tag-id'));
                });
            });
        }

        function handleEventTagInput(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                handleEventTagInputCommit();
            } else if (event.key === 'Backspace' && !event.target.value) {
                if (selectedEventNewTags.length) {
                    selectedEventNewTags.pop();
                    renderSelectedEventTags();
                } else if (selectedEventTagIds.length) {
                    selectedEventTagIds.pop();
                    renderSelectedEventTags();
                }
            }
        }

        // Food Tag Chip Management Functions (similar to Event tags)
        async function loadFoodTagOptions(selectedIds = [], newTagNames = []) {
            await ensureProductTagsCache();
            selectedFoodTagIds = (selectedIds || []).map(id => Number(id));
            selectedFoodNewTags = (newTagNames || []).filter(name => !!name);
            renderSelectedFoodTags();

            const tagInput = document.getElementById('food-tag-input');
            updateFoodTagSuggestions(tagInput ? tagInput.value : '');
        }

        function focusFoodTagInput() {
            const input = document.getElementById('food-tag-input');
            if (input) {
                input.focus();
            }
        }

        function renderSelectedFoodTags() {
            const wrapper = document.getElementById('food-selected-tags');
            if (!wrapper) return;

            wrapper.innerHTML = '';

            selectedFoodTagIds.forEach(id => {
                const tag = productTagsCache.find(item => Number(item.id) === Number(id));
                if (!tag) return;
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium';
                chip.innerHTML = `
                    <span>${tag.name}</span>
                    <button type="button" class="text-blue-600 hover:text-blue-900 focus:outline-none" data-remove-existing="${id}" aria-label="Remove tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            selectedFoodNewTags.forEach(name => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium';
                chip.innerHTML = `
                    <span>${name}</span>
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none" data-remove-new="${name}" aria-label="Remove new tag">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            updateFoodTagHiddenInputs();

            // Add click handlers for remove buttons
            wrapper.querySelectorAll('[data-remove-existing]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeExistingFoodTag(btn.getAttribute('data-remove-existing'));
                });
            });

            wrapper.querySelectorAll('[data-remove-new]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeNewFoodTag(btn.getAttribute('data-remove-new'));
                });
            });
        }

        function updateFoodTagHiddenInputs() {
            const hiddenContainer = document.getElementById('food-tags-hidden-inputs');
            if (!hiddenContainer) return;

            hiddenContainer.innerHTML = '';

            selectedFoodTagIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tags[]';
                input.value = id;
                hiddenContainer.appendChild(input);
            });

            selectedFoodNewTags.forEach(name => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'new_tags[]';
                input.value = name;
                hiddenContainer.appendChild(input);
            });
        }

        function addExistingFoodTag(id) {
            const numericId = Number(id);
            if (selectedFoodTagIds.includes(numericId)) {
                return;
            }

            const tag = productTagsCache.find(item => Number(item.id) === numericId);
            if (!tag) return;

            selectedFoodTagIds.push(numericId);
            selectedFoodNewTags = selectedFoodNewTags.filter(name => name.toLowerCase() !== tag.name.toLowerCase());
            renderSelectedFoodTags();
            updateFoodTagSuggestions('');
            clearFoodTagInput();
            const suggestions = document.getElementById('food-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function addNewFoodTag(name) {
            const trimmed = name.trim();
            if (!trimmed) return;

            const existing = productTagsCache.find(tag => tag.name.toLowerCase() === trimmed.toLowerCase());
            if (existing) {
                addExistingFoodTag(existing.id);
                return;
            }

            if (selectedFoodNewTags.some(tagName => tagName.toLowerCase() === trimmed.toLowerCase())) {
                clearFoodTagInput();
                return;
            }

            selectedFoodNewTags.push(trimmed);
            renderSelectedFoodTags();
            updateFoodTagSuggestions('');
            clearFoodTagInput();
            const suggestions = document.getElementById('food-tag-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function removeExistingFoodTag(id) {
            const numericId = Number(id);
            selectedFoodTagIds = selectedFoodTagIds.filter(tagId => tagId !== numericId);
            renderSelectedFoodTags();
            updateFoodTagSuggestions(document.getElementById('food-tag-input')?.value || '');
        }

        function removeNewFoodTag(name) {
            selectedFoodNewTags = selectedFoodNewTags.filter(tagName => tagName !== name);
            renderSelectedFoodTags();
            updateFoodTagSuggestions(document.getElementById('food-tag-input')?.value || '');
        }

        function clearFoodTagInput() {
            const input = document.getElementById('food-tag-input');
            if (input) {
                input.value = '';
            }
        }

        function handleFoodTagInputCommit() {
            const input = document.getElementById('food-tag-input');
            if (!input) return;

            const value = input.value.trim();
            if (!value) {
                input.value = '';
                return;
            }

            addNewFoodTag(value);
        }

        function updateFoodTagSuggestions(searchTerm = '') {
            const suggestions = document.getElementById('food-tag-suggestions');
            if (!suggestions) return;

            const normalized = searchTerm.trim().toLowerCase();
            if (!normalized.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            const availableTags = productTagsCache.filter(tag => !selectedFoodTagIds.includes(Number(tag.id)));

            let matches = [];
            if (normalized.length) {
                matches = availableTags.filter(tag =>
                    tag.name.toLowerCase().includes(normalized)
                ).slice(0, 10);
            }

            if (matches.length === 0) {
                const createBtn = document.createElement('button');
                createBtn.type = 'button';
                createBtn.className = 'w-full text-left px-3 py-2 text-sm hover:bg-blue-50 text-gray-600';
                createBtn.textContent = `Create "${normalized}"`;
                createBtn.addEventListener('click', () => {
                    addNewFoodTag(normalized);
                });
                suggestions.innerHTML = '';
                suggestions.appendChild(createBtn);
            } else {
                suggestions.innerHTML = matches.map(tag => `
                    <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50" data-tag-id="${tag.id}">
                        ${tag.name}
                    </button>
                `).join('');
            }
            suggestions.classList.remove('hidden');

            // Add click handlers
            suggestions.querySelectorAll('[data-tag-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    addExistingFoodTag(btn.getAttribute('data-tag-id'));
                });
            });
        }

        function handleFoodTagInput(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                handleFoodTagInputCommit();
            } else if (event.key === 'Backspace' && !event.target.value) {
                if (selectedFoodNewTags.length) {
                    selectedFoodNewTags.pop();
                    renderSelectedFoodTags();
                } else if (selectedFoodTagIds.length) {
                    selectedFoodTagIds.pop();
                    renderSelectedFoodTags();
                }
            }
        }

        // Event Banner Management Functions
        async function handleEventBannerUpload(event) {
            const files = Array.from(event.target.files);
            if (files.length === 0) return;

            for (const file of files) {
                if (!file.type.startsWith('image/')) {
                    alert(`${file.name} is not an image file`);
                    continue;
                }

                const formData = new FormData();
                formData.append('image', file);
                formData.append('event_id', currentEditingEventId || '0');

                try {
                    const response = await fetch('/api/admin/upload-event-banner', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${authToken}`
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const result = await response.json();
                        if (result.success && result.data && result.data.url) {
                            eventBanners.push(result.data.url);
                            renderEventBanners();
                        }
                    } else {
                        const error = await response.json();
                        alert(`Failed to upload ${file.name}: ${error.message || 'Upload failed'}`);
                    }
                } catch (error) {
                    console.error('Error uploading banner:', error);
                    alert(`Failed to upload ${file.name}`);
                }
            }

            // Reset input
            event.target.value = '';
        }

        function renderEventBanners() {
            const container = document.getElementById('event-banners-list');
            if (!container) return;

            container.innerHTML = '';

            eventBanners.forEach((bannerUrl, index) => {
                const bannerDiv = document.createElement('div');
                bannerDiv.className = 'flex items-center gap-2 p-2 border rounded bg-gray-50';
                bannerDiv.innerHTML = `
                    <img src="${bannerUrl}" alt="Banner ${index + 1}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 truncate">${bannerUrl}</p>
                    </div>
                    <button type="button" onclick="removeEventBanner(${index})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                `;
                container.appendChild(bannerDiv);
            });
        }

        function removeEventBanner(index) {
            if (confirm('Are you sure you want to remove this banner?')) {
                eventBanners.splice(index, 1);
                renderEventBanners();
            }
        }

        // Event Feature Chip Management Functions (for Event form)
        async function ensureEventFeaturesCache(forceRefresh = false) {
            if (!forceRefresh && eventFeaturesCache.length) {
                return eventFeaturesCache;
            }
            try {
                const data = await apiCall('/admin/event-features');
                if (data && data.success) {
                    eventFeaturesCache = data.data.items || [];
                } else {
                    eventFeaturesCache = [];
                }
            } catch (error) {
                console.error('Error loading event features:', error);
                eventFeaturesCache = [];
            }
            return eventFeaturesCache;
        }

        async function loadEventFeatureOptions(selectedIds = [], newFeatureNames = []) {
            await ensureEventFeaturesCache();
            selectedEventFeatureIds = (selectedIds || []).map(id => Number(id));
            selectedEventNewFeatures = (newFeatureNames || []).filter(name => !!name);
            renderSelectedEventFeatures();

            const featureInput = document.getElementById('event-feature-input');
            updateEventFeatureSuggestions(featureInput ? featureInput.value : '');
        }

        function focusEventFeatureInput() {
            const input = document.getElementById('event-feature-input');
            if (input) {
                input.focus();
            }
        }

        function renderSelectedEventFeatures() {
            const wrapper = document.getElementById('event-selected-features');
            if (!wrapper) return;

            wrapper.innerHTML = '';

            selectedEventFeatureIds.forEach(id => {
                const feature = eventFeaturesCache.find(item => Number(item.id) === Number(id));
                if (!feature) return;
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium';
                chip.innerHTML = `
                    <span>${feature.name}</span>
                    <button type="button" class="text-green-600 hover:text-green-900 focus:outline-none" data-remove-existing="${id}" aria-label="Remove feature">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            selectedEventNewFeatures.forEach(name => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium';
                chip.innerHTML = `
                    <span>${name}</span>
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none" data-remove-new="${name}" aria-label="Remove new feature">&times;</button>
                `;
                wrapper.appendChild(chip);
            });

            updateEventFeatureHiddenInputs();

            // Add click handlers for remove buttons
            wrapper.querySelectorAll('[data-remove-existing]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeExistingEventFeature(btn.getAttribute('data-remove-existing'));
                });
            });

            wrapper.querySelectorAll('[data-remove-new]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeNewEventFeature(btn.getAttribute('data-remove-new'));
                });
            });
        }

        function updateEventFeatureHiddenInputs() {
            const hiddenContainer = document.getElementById('event-features-hidden-inputs');
            if (!hiddenContainer) return;

            hiddenContainer.innerHTML = '';

            selectedEventFeatureIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'features[]';
                input.value = id;
                hiddenContainer.appendChild(input);
            });

            selectedEventNewFeatures.forEach(name => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'new_features[]';
                input.value = name;
                hiddenContainer.appendChild(input);
            });
        }

        function addExistingEventFeature(id) {
            const numericId = Number(id);
            if (selectedEventFeatureIds.includes(numericId)) {
                return;
            }

            const feature = eventFeaturesCache.find(item => Number(item.id) === numericId);
            if (!feature) return;

            selectedEventFeatureIds.push(numericId);
            selectedEventNewFeatures = selectedEventNewFeatures.filter(name => name.toLowerCase() !== feature.name.toLowerCase());
            renderSelectedEventFeatures();
            updateEventFeatureSuggestions('');
            clearEventFeatureInput();
            const suggestions = document.getElementById('event-feature-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function addNewEventFeature(name) {
            const trimmed = name.trim();
            if (!trimmed) return;

            const existing = eventFeaturesCache.find(feature => feature.name.toLowerCase() === trimmed.toLowerCase());
            if (existing) {
                addExistingEventFeature(existing.id);
                return;
            }

            if (selectedEventNewFeatures.some(featureName => featureName.toLowerCase() === trimmed.toLowerCase())) {
                clearEventFeatureInput();
                return;
            }

            selectedEventNewFeatures.push(trimmed);
            renderSelectedEventFeatures();
            updateEventFeatureSuggestions('');
            clearEventFeatureInput();
            const suggestions = document.getElementById('event-feature-suggestions');
            if (suggestions) {
                suggestions.classList.add('hidden');
            }
        }

        function removeExistingEventFeature(id) {
            const numericId = Number(id);
            selectedEventFeatureIds = selectedEventFeatureIds.filter(featureId => featureId !== numericId);
            renderSelectedEventFeatures();
            updateEventFeatureSuggestions(document.getElementById('event-feature-input')?.value || '');
        }

        function removeNewEventFeature(name) {
            selectedEventNewFeatures = selectedEventNewFeatures.filter(featureName => featureName !== name);
            renderSelectedEventFeatures();
            updateEventFeatureSuggestions(document.getElementById('event-feature-input')?.value || '');
        }

        function clearEventFeatureInput() {
            const input = document.getElementById('event-feature-input');
            if (input) {
                input.value = '';
            }
        }

        function handleEventFeatureInputCommit() {
            const input = document.getElementById('event-feature-input');
            if (!input) return;

            const value = input.value.trim();
            if (!value) {
                input.value = '';
                return;
            }

            addNewEventFeature(value);
        }

        function updateEventFeatureSuggestions(searchTerm = '') {
            const suggestions = document.getElementById('event-feature-suggestions');
            if (!suggestions) return;

            const normalized = searchTerm.trim().toLowerCase();
            if (!normalized.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            const availableFeatures = eventFeaturesCache.filter(feature => !selectedEventFeatureIds.includes(Number(feature.id)));

            let matches = [];
            matches = availableFeatures.filter(feature => feature.name.toLowerCase().includes(normalized)).slice(0, 10);

            if (!matches.length) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                return;
            }

            suggestions.innerHTML = matches.map(feature => `
                <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-green-50" data-feature-id="${feature.id}">
                    ${feature.name}
                </button>
            `).join('');
            suggestions.classList.remove('hidden');

            // Add click handlers
            suggestions.querySelectorAll('[data-feature-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    addExistingEventFeature(btn.getAttribute('data-feature-id'));
                });
            });
        }

        function handleEventFeatureInput(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                handleEventFeatureInputCommit();
            } else if (event.key === 'Backspace' && !event.target.value) {
                if (selectedEventNewFeatures.length) {
                    selectedEventNewFeatures.pop();
                    renderSelectedEventFeatures();
                } else if (selectedEventFeatureIds.length) {
                    selectedEventFeatureIds.pop();
                    renderSelectedEventFeatures();
                }
            }
        }

        // Event Feature Management Functions (for Event Features section)
        async function loadEventFeatures() {
            try {
                const data = await apiCall('/admin/event-features');
                if (data && data.success) {
                    eventFeaturesCache = data.data.items || [];
                    displayEventFeatures(eventFeaturesCache);
                }
            } catch (error) {
                console.error('Error loading event features:', error);
                document.getElementById('event-features-table-container').innerHTML = '<p class="text-red-500">Error loading event features</p>';
            }
        }

        function displayEventFeatures(features) {
            const container = document.getElementById('event-features-table-container');
            if (!container) return;

            if (!features || features.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No event features found. Add your first feature!</p>';
                return;
            }

            const table = `
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        ${features.map(feature => `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${feature.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${feature.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${feature.events_count || 0}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick="editEventFeature(${feature.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="deleteEventFeature(${feature.id})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            container.innerHTML = table;
        }

        function showAddEventFeatureModal() {
            currentEditingEventFeatureId = null;
            document.getElementById('event-feature-modal-title').textContent = 'Add New Feature';
            document.getElementById('event-feature-form').reset();
            document.getElementById('event-feature-modal').classList.remove('hidden');
        }

        function showEditEventFeatureModal(featureId) {
            const feature = eventFeaturesCache.find(f => f.id === featureId);
            if (!feature) {
                alert('Feature not found');
                return;
            }

            currentEditingEventFeatureId = featureId;
            document.getElementById('event-feature-modal-title').textContent = 'Edit Feature';
            document.getElementById('event-feature-name').value = feature.name;
            document.getElementById('event-feature-modal').classList.remove('hidden');
        }

        function closeEventFeatureModal() {
            document.getElementById('event-feature-modal').classList.add('hidden');
            document.getElementById('event-feature-form').reset();
            currentEditingEventFeatureId = null;
        }

        async function saveEventFeature(event) {
            event.preventDefault();
            const name = document.getElementById('event-feature-name').value.trim();
            if (!name) {
                alert('Feature name is required');
                return;
            }

            const isEdit = currentEditingEventFeatureId !== null;
            const url = isEdit ? `/admin/event-features/${currentEditingEventFeatureId}` : '/admin/event-features';

            try {
                const response = await apiCall(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    body: JSON.stringify({ name }),
                });
                if (response && response.success) {
                    closeEventFeatureModal();
                    await loadEventFeatures();
                    await ensureEventFeaturesCache(true); // Refresh cache
                    alert(`Feature ${isEdit ? 'updated' : 'added'} successfully`);
                } else if (response && response.errors) {
                    alert(Object.values(response.errors).flat().join('\n'));
                }
            } catch (error) {
                console.error(`Error ${isEdit ? 'updating' : 'adding'} feature:`, error);
                alert(`Error ${isEdit ? 'updating' : 'adding'} feature`);
            }
        }

        async function deleteEventFeature(featureId) {
            if (confirm('Are you sure you want to delete this feature? This will remove it from all events.')) {
                try {
                    const response = await apiCall(`/admin/event-features/${featureId}`, { method: 'DELETE' });
                    if (response && response.success) {
                        await loadEventFeatures();
                        await ensureEventFeaturesCache(true); // Refresh cache
                        alert('Feature deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting feature:', error);
                    alert('Error deleting feature');
                }
            }
        }

        function editEventFeature(featureId) {
            showEditEventFeatureModal(featureId);
        }

        // Review Management Functions
        async function loadEvents() {
            try {
                const data = await apiCall('/admin/events');
                if (data && data.success) {
                    displayEvents(data.data.items);
                }
            } catch (error) {
                console.error('Error loading events:', error);
            }
        }

        function displayEvents(events) {
            const tbody = document.getElementById('events-table');
            if (!tbody) return;

            if (!events || events.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-sm text-gray-500 text-center">No events found. Create the first event to get started.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = events.map(event => {
                const startTime = event.start_time ? new Date(event.start_time).toLocaleString() : 'N/A';
                const endTime = event.end_time ? new Date(event.end_time).toLocaleString() : 'N/A';
                const capacity = event.capacity || 'N/A';
                const attendees = event.confirmed_attendees_count || 0;
                const capacityDisplay = capacity !== 'N/A' ? `${attendees}/${capacity}` : attendees;
                return `
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${event.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${event.title || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${event.pavilion ? event.pavilion.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startTime}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${endTime}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${capacityDisplay}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatPrice(event.price)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="editEvent(${event.id})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteEvent(${event.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        async function showAddEventModal() {
            // Load pavilions for dropdown
            try {
                const pavilionData = await apiCall('/admin/pavilions?per_page=100');
                if (pavilionData && pavilionData.success) {
                    const pavilionSelect = document.getElementById('event-pavilion-select');
                    pavilionSelect.innerHTML = '<option value="">Select a pavilion...</option>';
                    pavilionData.data.items.forEach(pavilion => {
                        pavilionSelect.innerHTML += `<option value="${pavilion.id}">${pavilion.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading pavilions:', error);
            }

            // Load event tags for chip input
            await ensureEventTagsCache();
            await loadEventTagOptions([], []);
            await ensureEventFeaturesCache();
            await loadEventFeatureOptions([], []);
            eventBanners = [];
            renderEventBanners();

            currentEditingEventId = null;
            document.getElementById('event-modal-title').textContent = 'Add Event';
            document.getElementById('add-event-form').reset();
            document.getElementById('event-id').value = '';
            document.getElementById('add-event-modal').classList.remove('hidden');
        }

        function closeAddEventModal() {
            document.getElementById('add-event-modal').classList.add('hidden');
            document.getElementById('add-event-form').reset();
            document.getElementById('event-id').value = '';
            selectedEventTagIds = [];
            selectedEventNewTags = [];
            selectedEventFeatureIds = [];
            selectedEventNewFeatures = [];
            eventBanners = [];
            renderSelectedEventTags();
            renderSelectedEventFeatures();
            renderEventBanners();
        }

        async function addEvent(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            // Convert empty strings to null for optional fields
            if (!data.pavilion_id || data.pavilion_id === '') {
                data.pavilion_id = null;
            }
            if (!data.capacity || data.capacity === '') {
                data.capacity = null;
            } else {
                data.capacity = parseInt(data.capacity);
            }

            // Get selected tags from chip input
            data.tags = selectedEventTagIds;
            data.new_tags = selectedEventNewTags;

            // Get selected features from chip input
            data.features = selectedEventFeatureIds;
            data.new_features = selectedEventNewFeatures;

            // Get banners array
            data.banners = eventBanners;

            const isEdit = currentEditingEventId !== null;
            const url = isEdit ? `/admin/events/${currentEditingEventId}` : '/admin/events';

            try {
                const response = await apiCall(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    body: JSON.stringify(data),
                });
                if (response && response.success) {
                    closeAddEventModal();
                    loadEvents();
                    alert(`Event ${isEdit ? 'updated' : 'added'} successfully`);
                } else if (response && response.errors) {
                    alert(Object.values(response.errors).flat().join('\n'));
                }
            } catch (error) {
                console.error(`Error ${isEdit ? 'updating' : 'adding'} event:`, error);
                alert(`Error ${isEdit ? 'updating' : 'adding'} event`);
            }
        }

        async function editEvent(eventId) {
            try {
                // Load all events to find the one we need
                const eventData = await apiCall(`/admin/events?per_page=1000`);
                if (!eventData || !eventData.success) {
                    alert('Error loading events');
                    return;
                }

                const event = eventData.data.items.find(e => e.id === eventId);
                if (!event) {
                    alert('Event not found');
                    return;
                }

                // Load pavilions
                const pavilionData = await apiCall('/admin/pavilions?per_page=100');
                if (pavilionData && pavilionData.success) {
                    const pavilionSelect = document.getElementById('event-pavilion-select');
                    pavilionSelect.innerHTML = '<option value="">Select a pavilion...</option>';
                    pavilionData.data.items.forEach(pavilion => {
                        pavilionSelect.innerHTML += `<option value="${pavilion.id}" ${pavilion.id === event.pavilion_id ? 'selected' : ''}>${pavilion.name}</option>`;
                    });
                }

                // Load event tags for chip input
                await ensureEventTagsCache();
                const existingTagIds = event.tags ? event.tags.map(t => t.id) : [];
                await loadEventTagOptions(existingTagIds, []);

                // Load event features for chip input
                await ensureEventFeaturesCache();
                const existingFeatureIds = event.features ? event.features.map(f => f.id) : [];
                await loadEventFeatureOptions(existingFeatureIds, []);

                // Load banners
                eventBanners = event.banners || [];
                renderEventBanners();

                currentEditingEventId = event.id;
                document.getElementById('event-modal-title').textContent = 'Edit Event';
                document.getElementById('event-id').value = event.id;
                document.getElementById('event-title').value = event.title || '';
                document.getElementById('event-description').value = event.description || '';
                document.getElementById('event-stage').value = event.stage || '';
                document.getElementById('event-price').value = event.price !== null && event.price !== undefined ? parseFloat(event.price).toFixed(2) : '-1.00';
                document.getElementById('event-start-time').value = event.start_time ? new Date(event.start_time).toISOString().slice(0, 16) : '';
                document.getElementById('event-end-time').value = event.end_time ? new Date(event.end_time).toISOString().slice(0, 16) : '';
                document.getElementById('event-capacity').value = event.capacity || '';
                document.getElementById('add-event-modal').classList.remove('hidden');
            } catch (error) {
                console.error('Error loading event:', error);
                alert('Error loading event');
            }
        }

        async function deleteEvent(eventId) {
            if (confirm('Are you sure you want to delete this event?')) {
                try {
                    const data = await apiCall(`/admin/events/${eventId}`, { method: 'DELETE' });
                    if (data && data.success) {
                        loadEvents();
                        alert('Event deleted successfully');
                    }
                } catch (error) {
                    console.error('Error deleting event:', error);
                    alert('Error deleting event');
                }
            }
        }

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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.user ? (((review.user.first_name || '') + ' ' + (review.user.last_name || '')).trim() || 'N/A') : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.shop ? review.shop.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.product ? review.product.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${review.food ? review.food.name : 'N/A'}</td>
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
                        const name = ((user.first_name || '') + ' ' + (user.last_name || '')).trim() || user.email || 'User';
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

            // Load foods for dropdown
            try {
                const foodData = await apiCall('/admin/foods?per_page=100');
                if (foodData && foodData.success) {
                    const foodSelect = document.getElementById('review-food-select');
                    foodSelect.innerHTML = '<option value="">Select a food...</option>';
                    foodData.data.items.forEach(food => {
                        foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading foods:', error);
            }

            // Add event listener to load foods when shop is selected
            const shopSelect = document.getElementById('review-shop-select');
            const foodSelect = document.getElementById('review-food-select');
            if (shopSelect && foodSelect) {
                shopSelect.addEventListener('change', async function() {
                    const shopId = this.value;
                    if (shopId) {
                        try {
                            const foodData = await apiCall(`/admin/foods?shop_id=${shopId}&per_page=100`);
                            if (foodData && foodData.success) {
                                foodSelect.innerHTML = '<option value="">Select a food...</option>';
                                foodData.data.items.forEach(food => {
                                    foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                                });
                            }
                        } catch (error) {
                            console.error('Error loading foods for shop:', error);
                        }
                    } else {
                        // Reload all foods
                        try {
                            const foodData = await apiCall('/admin/foods?per_page=100');
                            if (foodData && foodData.success) {
                                foodSelect.innerHTML = '<option value="">Select a food...</option>';
                                foodData.data.items.forEach(food => {
                                    foodSelect.innerHTML += `<option value="${food.id}">${food.name}</option>`;
                                });
                            }
                        } catch (error) {
                            console.error('Error loading foods:', error);
                        }
                    }
                });
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
            if (!data.food_id || data.food_id === '') {
                data.food_id = null;
            }
            if (!data.shop_id || data.shop_id === '') {
                data.shop_id = null;
            }

            // Ensure at least one is provided
            if (!data.shop_id && !data.product_id && !data.food_id) {
                alert('Please select either a shop, product, or food');
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

        // Map Management Functions
        let adminMap = null;
        let mapMarkers = [];

        async function loadMap() {
            if (!adminMap) {
                // Initialize map
                adminMap = L.map('admin-map').setView([25.2048, 55.2708], 11); // Default: Dubai
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(adminMap);

                // Fix map size after initialization
                setTimeout(() => { adminMap.invalidateSize(); }, 200);
            }
            await refreshMap();
        }

        async function refreshMap() {
            if (!adminMap) {
                await loadMap();
                return;
            }

            try {
                const data = await apiCall('/admin/map/pois');
                if (data && data.success) {
                    // Clear existing markers
                    mapMarkers.forEach(marker => marker.remove());
                    mapMarkers = [];

                    const pois = data.data;
                    const bounds = [];

                    // Add pavilion markers (blue)
                    pois.pavilions.forEach(pavilion => {
                        if (pavilion.lat && pavilion.lng) {
                            const marker = L.marker([pavilion.lat, pavilion.lng], {
                                icon: L.divIcon({
                                    className: 'custom-marker',
                                    html: `<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                })
                            }).addTo(adminMap);

                            marker.bindPopup(`
                                <div style="min-width: 200px;">
                                    <h3 style="font-weight: bold; margin-bottom: 8px; color: #3B82F6;">🏛️ ${pavilion.name}</h3>
                                    <p style="margin: 4px 0; color: #666;">${pavilion.description || 'No description'}</p>
                                    <p style="margin: 4px 0; font-size: 12px; color: #999;">Type: Pavilion</p>
                                </div>
                            `);

                            mapMarkers.push(marker);
                            bounds.push([pavilion.lat, pavilion.lng]);
                        }
                    });

                    // Add shop markers (green)
                    pois.shops.forEach(shop => {
                        if (shop.lat && shop.lng) {
                            const marker = L.marker([shop.lat, shop.lng], {
                                icon: L.divIcon({
                                    className: 'custom-marker',
                                    html: `<div style="background-color: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                })
                            }).addTo(adminMap);

                            const locationInfo = shop.location_name ? `<p style="margin: 4px 0; color: #666;">📍 ${shop.location_name}</p>` : '';
                            const pavilionInfo = shop.pavilion_name ? `<p style="margin: 4px 0; font-size: 12px; color: #999;">Pavilion: ${shop.pavilion_name}</p>` : '';

                            marker.bindPopup(`
                                <div style="min-width: 200px;">
                                    <h3 style="font-weight: bold; margin-bottom: 8px; color: #10B981;">🏪 ${shop.name}</h3>
                                    ${locationInfo}
                                    <p style="margin: 4px 0; color: #666;">${shop.description || 'No description'}</p>
                                    <p style="margin: 4px 0; font-size: 12px; color: #999;">Type: ${shop.type_category || 'shop'}</p>
                                    ${pavilionInfo}
                                </div>
                            `);

                            mapMarkers.push(marker);
                            bounds.push([shop.lat, shop.lng]);
                        }
                    });

                    // Add event markers (red)
                    pois.events.forEach(event => {
                        if (event.lat && event.lng) {
                            const marker = L.marker([event.lat, event.lng], {
                                icon: L.divIcon({
                                    className: 'custom-marker',
                                    html: `<div style="background-color: #EF4444; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                })
                            }).addTo(adminMap);

                            const startTime = event.start_time ? new Date(event.start_time).toLocaleString() : 'N/A';
                            const endTime = event.end_time ? new Date(event.end_time).toLocaleString() : 'N/A';
                            const stageInfo = event.stage ? `<p style="margin: 4px 0; font-size: 12px; color: #999;">Stage: ${event.stage}</p>` : '';

                            marker.bindPopup(`
                                <div style="min-width: 200px;">
                                    <h3 style="font-weight: bold; margin-bottom: 8px; color: #EF4444;">🎭 ${event.name}</h3>
                                    <p style="margin: 4px 0; color: #666;">${event.description || 'No description'}</p>
                                    ${stageInfo}
                                    <p style="margin: 4px 0; font-size: 12px; color: #999;">Start: ${startTime}</p>
                                    <p style="margin: 4px 0; font-size: 12px; color: #999;">End: ${endTime}</p>
                                    ${event.pavilion_name ? `<p style="margin: 4px 0; font-size: 12px; color: #999;">Pavilion: ${event.pavilion_name}</p>` : ''}
                                </div>
                            `);

                            mapMarkers.push(marker);
                            bounds.push([event.lat, event.lng]);
                        }
                    });

                    // Fit map to show all markers
                    if (bounds.length > 0) {
                        adminMap.fitBounds(bounds, { padding: [50, 50] });
                    }
                }
            } catch (error) {
                console.error('Error loading map POIs:', error);
                alert('Error loading map data');
            }
        }
    </script>
</body>
</html>
