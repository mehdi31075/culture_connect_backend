<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CultureConnect Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-500">
                <i class="fas fa-shield-alt text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Admin Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                CultureConnect Administration Panel
            </p>
        </div>

        <form class="mt-8 space-y-6" onsubmit="login(event)">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email Address</label>
                    <input id="email" name="email" type="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <span class="text-gray-600">
                        Contact your system administrator for access
                    </span>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Sign in
                </button>
            </div>

            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <span id="error-text"></span>
            </div>

            <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <span id="success-text"></span>
            </div>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Need help? Contact your system administrator
            </p>
        </div>
    </div>

    <script>
        // Login
        async function login(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                showError('Please fill in all fields');
                return;
            }

            try {
                const response = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Store token and redirect to admin dashboard
                    localStorage.setItem('admin_token', data.access_token);
                    localStorage.setItem('admin_user', JSON.stringify(data.user));
                    window.location.href = '/admin/dashboard';
                } else {
                    showError(data.message || 'Login failed');
                }
            } catch (error) {
                showError('Network error. Please try again.');
            }
        }

        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            const successDiv = document.getElementById('success-message');

            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            successDiv.classList.add('hidden');
        }

        // Show success message
        function showSuccess(message) {
            const successDiv = document.getElementById('success-message');
            const successText = document.getElementById('success-text');
            const errorDiv = document.getElementById('error-message');

            successText.textContent = message;
            successDiv.classList.remove('hidden');
            errorDiv.classList.add('hidden');
        }

        // Check if already logged in
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('admin_token');
            if (token) {
                window.location.href = '/admin/dashboard';
            }
        });
    </script>
</body>
</html>
