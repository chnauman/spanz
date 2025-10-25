# Downgrade Request System Setup

## Overview
This system allows users to request downgrades from their current paid subscription to the Basic (Free) plan. The downgrade takes effect after their current subscription expires.

## Features Implemented

### User Features
1. **Request Downgrade**: Users can request to downgrade from their current plan
2. **View Requests**: Users can view their downgrade request history
3. **Cancel Requests**: Users can cancel pending downgrade requests with SweetAlert confirmation

### Admin Features
1. **View All Requests**: Admins can see all downgrade requests
2. **Approve/Decline**: Admins can approve or decline requests with notes and SweetAlert confirmations
3. **Request Details**: Detailed view of each request with user information
4. **SweetAlert Notifications**: Beautiful popup confirmations for all actions

### Automatic Processing
1. **Cron Job**: Automatically processes approved downgrades when subscriptions expire
2. **Logging**: All actions are logged for audit purposes

## Setup Instructions

### 1. Database Migration
The migration has already been run:
```bash
php artisan migrate
```

### 2. Cron Job Setup
Add this to your crontab to run the downgrade processing command daily:

```bash
# Add to crontab (crontab -e)
0 0 * * * cd /path/to/your/project && php artisan downgrade:process >> /dev/null 2>&1
```

Or for Windows Task Scheduler:
- Create a new task
- Set trigger to daily at midnight
- Action: Start a program
- Program: `php`
- Arguments: `artisan downgrade:process`
- Start in: `C:\laragon\www\spanz`

### 3. Manual Testing
You can test the command manually:
```bash
php artisan downgrade:process
```

## How It Works

### User Flow
1. User goes to Account Settings → Update Plan
2. User clicks "Request Downgrade" button
3. User fills out the downgrade request form with reason
4. Request is submitted and marked as "pending"

### Admin Flow
1. Admin goes to Admin → Downgrade Requests
2. Admin can view all requests with user details
3. Admin can approve or decline with notes
4. Approved requests are processed automatically when subscription expires

### Automatic Processing
1. Cron job runs daily
2. Checks for approved downgrade requests
3. For each approved request, checks if user's subscription has expired
4. If expired, marks subscription as inactive (user becomes free)
5. Logs the downgrade completion

## Database Tables

### downgrade_requests
- `id`: Primary key
- `user_id`: Foreign key to users table
- `current_subscription_id`: Foreign key to subscriptions table
- `status`: 'pending', 'approved', 'declined'
- `reason`: User's reason for downgrade
- `admin_notes`: Admin's notes
- `requested_at`: When request was made
- `processed_at`: When request was processed
- `processed_by`: Admin who processed the request

## Routes Added

### User Routes
- `GET /downgrade-requests/create` - Show downgrade form
- `POST /downgrade-requests/store` - Submit downgrade request
- `GET /downgrade-requests/my-requests` - View user's requests
- `DELETE /downgrade-requests/cancel/{id}` - Cancel request

### Admin Routes
- `GET /admin/downgrade-requests` - List all requests
- `GET /admin/downgrade-requests/{id}` - View request details
- `POST /admin/downgrade-requests/{id}/approve` - Approve request
- `POST /admin/downgrade-requests/{id}/decline` - Decline request

## Files Created/Modified

### New Files
- `app/Models/DowngradeRequest.php`
- `app/Http/Controllers/DowngradeRequestController.php`
- `app/Http/Controllers/Admin/DowngradeRequestController.php`
- `app/Console/Commands/ProcessDowngradeRequests.php`
- `resources/views/account/downgrade-request.blade.php`
- `resources/views/account/my-downgrade-requests.blade.php`
- `resources/views/admin/downgrade-requests/index.blade.php`
- `resources/views/admin/downgrade-requests/show.blade.php`

### Modified Files
- `resources/views/account/plan.blade.php` - Added downgrade button
- `resources/views/admin/partials/sidebar.blade.php` - Added admin menu
- `app/Models/User.php` - Added downgradeRequests relationship
- `routes/web.php` - Added routes

## Security Considerations

1. **Authentication**: All routes require authentication
2. **Authorization**: Admin routes require admin role
3. **Validation**: All inputs are validated
4. **Logging**: All actions are logged for audit
5. **Confirmation**: Admin actions require confirmation

## Testing

### Manual Testing
1. Create a test user with an active subscription
2. Request a downgrade
3. Login as admin and approve the request
4. Wait for subscription to expire or manually set expiry date
5. Run the cron command to process the downgrade
6. Verify user is now on free plan

### Command Testing
```bash
# Test the command
php artisan downgrade:process

# Check logs
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Common Issues
1. **Cron not running**: Check crontab configuration
2. **Permission issues**: Ensure proper file permissions
3. **Database errors**: Check migration status
4. **Route not found**: Clear route cache with `php artisan route:clear`

### Logs
Check `storage/logs/laravel.log` for detailed information about:
- Downgrade request submissions
- Admin approvals/declines
- Automatic processing results
- Any errors that occur
