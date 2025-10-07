<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CultureConnect Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-600">Review management interface coming soon...</p>
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
                    displayUsers(data.data.data);
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
    </script>
</body>
</html>
