# 🛡️ CultureConnect Admin Panel

A comprehensive admin panel for managing the CultureConnect application with a modern web interface and RESTful API.

## 🌐 **Access the Admin Panel**

-   **Login Page**: http://localhost:8000/admin/login
-   **Dashboard**: http://localhost:8000/admin/dashboard
-   **API Documentation**: http://localhost:8000/api/documentation

## 🔐 **Authentication**

The admin panel uses email/password authentication with predefined admin credentials from environment variables.

### **Predefined Admin Account**

-   **Email**: admin@cultureconnect.com
-   **Password**: admin123
-   **Status**: Staff user with admin privileges

### **Environment Configuration**

The admin user is created automatically using these environment variables:

```bash
ADMIN_EMAIL=admin@cultureconnect.com
ADMIN_PASSWORD=admin123
ADMIN_NAME=Admin User
ADMIN_PHONE=+1234567890
```

### **Login Process**

1. Visit `/admin/login`
2. Enter your email address
3. Enter your password
4. Click "Sign in"

## 📊 **Features**

### **Dashboard**

-   **Overview Statistics**: Total users, events, orders, revenue
-   **Growth Metrics**: New users and orders over time
-   **Revenue Tracking**: Total and period-based revenue
-   **Order Status Distribution**: Visual breakdown of order statuses
-   **Recent Activity**: Latest users, events, orders, and reviews
-   **Interactive Charts**: User growth and order status charts

### **User Management**

-   **View All Users**: Paginated list with search functionality
-   **User Details**: Complete user information with profile data
-   **Edit Users**: Update user information and status
-   **Delete Users**: Remove users (staff users protected)
-   **User Analytics**: Registration trends and activity patterns

### **Event Management**

-   **View Events**: List all events with pavilion information
-   **Event Analytics**: Popular events and attendance data
-   **Upcoming Events**: Schedule of future events

### **Order Management**

-   **View Orders**: All orders with user and item details
-   **Order Status**: Update order status (pending, confirmed, preparing, ready, delivered, cancelled)
-   **Order Analytics**: Revenue trends and popular products

### **Review Management**

-   **View Reviews**: All reviews with user information
-   **Delete Reviews**: Remove inappropriate reviews
-   **Review Analytics**: Rating distribution and trends

### **Notification Management**

-   **View Notifications**: System notifications
-   **Send Notifications**: Create and send notifications to users
-   **Notification Types**: Info, warning, success, error

## 🔧 **API Endpoints**

### **Admin Authentication**

-   `POST /api/admin/login` - Admin login with email/password
-   `POST /api/admin/logout` - Admin logout
-   `GET /api/admin/me` - Get current admin user info

### **Dashboard**

-   `GET /api/admin/dashboard` - Get dashboard statistics
-   `GET /api/admin/analytics/users` - User analytics
-   `GET /api/admin/analytics/orders` - Order analytics
-   `GET /api/admin/analytics/events` - Event analytics
-   `GET /api/admin/system/health` - System health metrics

### **User Management**

-   `GET /api/admin/users` - List users (with search and pagination)
-   `GET /api/admin/users/{id}` - Get user details
-   `PUT /api/admin/users/{id}` - Update user
-   `DELETE /api/admin/users/{id}` - Delete user

### **Event Management**

-   `GET /api/admin/events` - List events

### **Pavilion Management**

-   `GET /api/admin/pavilions` - List pavilions

### **Shop Management**

-   `GET /api/admin/shops` - List shops

### **Order Management**

-   `GET /api/admin/orders` - List orders
-   `PUT /api/admin/orders/{id}/status` - Update order status

### **Review Management**

-   `GET /api/admin/reviews` - List reviews
-   `DELETE /api/admin/reviews/{id}` - Delete review

### **Notification Management**

-   `GET /api/admin/notifications` - List notifications
-   `POST /api/admin/notifications` - Send notification

## 🛠️ **Technical Details**

### **Authentication & Authorization**

-   **JWT Authentication**: Uses the same JWT system as the main API
-   **Staff Middleware**: Custom middleware to check `is_staff` flag
-   **Role-based Access**: Only users with `is_staff = true` can access admin endpoints

### **Frontend Technology**

-   **Tailwind CSS**: Modern, responsive design
-   **Chart.js**: Interactive charts and graphs
-   **Font Awesome**: Icons and visual elements
-   **Vanilla JavaScript**: No framework dependencies

### **Backend Architecture**

-   **Laravel Controllers**: Organized admin controllers
-   **Middleware**: Custom admin middleware for authorization
-   **API Routes**: RESTful API endpoints
-   **Swagger Documentation**: Complete API documentation

## 📱 **Responsive Design**

The admin panel is fully responsive and works on:

-   **Desktop**: Full-featured dashboard
-   **Tablet**: Optimized layout
-   **Mobile**: Touch-friendly interface

## 🔒 **Security Features**

-   **JWT Token Authentication**: Secure token-based authentication
-   **Staff-only Access**: Only authorized staff can access admin features
-   **CSRF Protection**: Laravel's built-in CSRF protection
-   **Input Validation**: Comprehensive validation on all inputs
-   **SQL Injection Protection**: Laravel's Eloquent ORM protection

## 🚀 **Getting Started**

### **1. Access the Admin Panel**

```bash
# Visit the login page
http://localhost:8000/admin/login
```

### **2. Login with Admin Account**

-   Use the test admin account: `+1234567891`
-   Request OTP and enter the code
-   You'll be redirected to the dashboard

### **3. Explore Features**

-   **Dashboard**: View overview statistics and charts
-   **Users**: Manage user accounts
-   **Events**: View and manage events
-   **Orders**: Track and update order statuses
-   **Reviews**: Moderate user reviews
-   **Notifications**: Send system notifications

## 📊 **Dashboard Metrics**

### **Overview Cards**

-   **Total Users**: All registered users
-   **Active Users**: Users with `is_active = true`
-   **Total Events**: All events in the system
-   **Total Orders**: All orders placed
-   **Total Revenue**: Sum of delivered orders

### **Growth Metrics**

-   **New Users This Week**: Users registered in the last 7 days
-   **New Users This Month**: Users registered in the last 30 days
-   **New Orders This Week**: Orders placed in the last 7 days
-   **New Orders This Month**: Orders placed in the last 30 days

### **Charts**

-   **User Growth Chart**: Line chart showing user registration trends
-   **Order Status Chart**: Doughnut chart showing order status distribution

## 🔧 **Customization**

### **Adding New Features**

1. **Create Controller Method**: Add new methods to `AdminController`
2. **Add Route**: Register the route in `routes/admin.php`
3. **Update Frontend**: Add UI elements in the dashboard
4. **Update Swagger**: Add API documentation

### **Styling**

-   **Tailwind CSS**: Modify classes in the Blade templates
-   **Custom CSS**: Add custom styles in the `<head>` section
-   **Icons**: Use Font Awesome icons throughout

## 🐛 **Troubleshooting**

### **Common Issues**

1. **"Unauthorized. Admin access required"**

    - Ensure the user has `is_staff = true`
    - Check JWT token is valid

2. **"Failed to fetch" errors**

    - Check if the API server is running
    - Verify CORS settings
    - Check network connectivity

3. **Login not working**
    - Ensure OTP is requested first
    - Check if the user exists in the database
    - Verify the user has staff privileges

### **Debug Mode**

Enable debug mode in `.env`:

```bash
APP_DEBUG=true
LOG_LEVEL=debug
```

## 📈 **Performance**

-   **Lazy Loading**: Data is loaded on demand
-   **Pagination**: Large datasets are paginated
-   **Caching**: Consider implementing Redis caching for statistics
-   **Database Optimization**: Use indexes for frequently queried fields

## 🔄 **Updates & Maintenance**

### **Regular Tasks**

-   **Monitor System Health**: Check the system health endpoint
-   **Review User Activity**: Monitor user registration and activity
-   **Update Order Statuses**: Keep order statuses current
-   **Moderate Reviews**: Review and moderate user reviews

### **Backup**

-   **Database Backups**: Regular database backups
-   **User Data**: Ensure user data is properly backed up
-   **Configuration**: Backup admin panel configuration

## 🎯 **Future Enhancements**

-   **Real-time Notifications**: WebSocket integration
-   **Advanced Analytics**: More detailed reporting
-   **Bulk Operations**: Bulk user and order management
-   **Export Features**: Export data to CSV/Excel
-   **Audit Logs**: Track admin actions
-   **Multi-language Support**: Internationalization

Your admin panel is now ready for use! 🎉
