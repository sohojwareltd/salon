# Guest Booking Integration - Complete Guide

## Overview
Guest booking functionality has been fully integrated into the main booking page (`book.blade.php`). Users can now book appointments without logging in, and the system will automatically create accounts and send credentials.

## Implementation Details

### 1. **Booking Page (book.blade.php)**
- Added conditional `@guest` section that displays personal information form only for non-authenticated users
- Form fields: Full Name, Email, Phone, Additional Notes (optional)
- Form appears between time slot selection and submit button
- Clean visual separation with gradient info box explaining account creation
- Validation errors displayed per field

**Location**: Between line 508-578 (after time slots section)

### 2. **Routes (routes/web.php)**
- Moved booking routes outside `auth` middleware group
- Routes now accessible to both authenticated and guest users:
  - `GET /appointments/book/{provider?}` - Booking page
  - `GET /appointments/available-slots/{provider}` - Available slots API
  - `POST /appointments` - Store appointment
  - `GET /appointments/thank-you` - Thank you page

**Lines**: 130-135

### 3. **Controller (DashboardController.php)**
- Modified `__construct()` to exclude booking methods from auth middleware
- Updated `storeAppointment()` method to handle both authenticated and guest users:
  - **Authenticated Users**: Use existing `auth()->user()->id`
  - **Guest Users**: 
    - Validate guest_name, guest_email, guest_phone fields
    - Check if email exists in database
    - Create new user with random password if email is new
    - Update existing user info if email already exists
    - Send credentials email only for new accounts
- Updated `thankYou()` method to redirect guest users to home instead of dashboard

**Key Logic**:
```php
if (!auth()->check()) {
    $rules['guest_name'] = 'required|string|max:255';
    $rules['guest_email'] = 'required|email|max:255';
    $rules['guest_phone'] = 'required|string|max:20';
}
```

### 4. **Thank You Page (thank-you.blade.php)**
- Added conditional notice for guest users showing account creation success
- Display email where credentials were sent
- Show "Login to Your Account" button for guests
- Show "View My Appointments" button for authenticated users
- Both user types see "Back to Home" button

**Added Section**: Lines 94-110

### 5. **Email Notification (GuestAppointmentCredentials.php)**
- Reused existing notification class
- Sends email with:
  - Account email
  - Temporary password
  - Appointment details
  - Login link
- Queued for asynchronous sending

## User Flow

### Guest User Flow:
1. Browse services or providers
2. Click "Book as Guest" button
3. Select provider and services
4. Choose appointment date and time
5. **Fill in personal information** (Name, Email, Phone)
6. Submit booking
7. System creates account automatically
8. Receive confirmation on thank-you page
9. Check email for login credentials
10. Login and manage appointments

### Authenticated User Flow:
1. Browse services or providers
2. Click "Book Appointment" button
3. Select provider and services
4. Choose appointment date and time
5. Submit booking (no personal info form shown)
6. Receive confirmation on thank-you page
7. View appointments in dashboard

## Validation Rules

### Guest Users (Additional Fields):
- `guest_name`: required|string|max:255
- `guest_email`: required|email|max:255
- `guest_phone`: required|string|max:20

### All Users (Existing Fields):
- `provider_id`: required|exists:providers,id
- `service_ids`: required|array|min:1
- `service_ids.*`: required|exists:services,id
- `appointment_date`: required|date|after_or_equal:today
- `start_time`: required
- `notes`: optional

## Security Features

1. **Password Generation**: 10-character random password using `Str::random(10)`
2. **Password Hashing**: All passwords hashed using `Hash::make()`
3. **Email Verification**: Credentials sent only to provided email
4. **Existing Account Check**: Prevents duplicate accounts with same email
5. **Role Assignment**: Automatically assigns 'customer' role (role_id = 3)

## Database Operations

### New User Creation:
```php
User::create([
    'name' => $validated['guest_name'],
    'email' => $validated['guest_email'],
    'phone' => $validated['guest_phone'],
    'password' => Hash::make($password),
    'role_id' => $customerRole->id ?? 3,
]);
```

### Existing User Update:
```php
$user->update([
    'name' => $validated['guest_name'],
    'phone' => $validated['guest_phone'],
]);
```

## Button Updates

All "Book as Guest" buttons redirect to the main booking route:

### 1. **Services Page** (`services/index.blade.php`)
- Line ~608
- Route: `route('guest.book', [$service->providers->first(), 'service' => $service->id])`
- **Already updated** in previous implementation

### 2. **Provider Profile Page** (`providers/show.blade.php`)
- Two locations: Lines 784 and 858
- Route: `route('guest.book', $provider)`
- **Already updated** in previous implementation

## Email Templates

### Credentials Email:
- **Subject**: "Your Appointment Booking & Account Details"
- **Content**:
  - Welcome message
  - Login credentials (email + temporary password)
  - Appointment details (service, provider, date, time)
  - Login button link
  - Password change reminder
- **Template**: `resources/views/mail/guest-appointment-credentials.blade.php`

## Testing Checklist

- [x] Guest user can see personal information form
- [x] Authenticated user does NOT see personal information form
- [x] Guest booking creates new account with random password
- [x] Existing email updates user info instead of creating duplicate
- [x] Credentials email sent only for new accounts
- [x] All notifications created (customer + provider)
- [x] Thank you page shows appropriate content for guests
- [x] Thank you page shows appropriate buttons for guests
- [x] Form validation works for all fields
- [x] Appointment created successfully for both user types

## Files Modified

1. `resources/views/pages/appointments/book.blade.php` - Added guest form section
2. `routes/web.php` - Removed auth middleware from booking routes
3. `app/Http/Controllers/DashboardController.php` - Added guest handling logic
4. `resources/views/pages/appointments/thank-you.blade.php` - Added guest notices

## Notification System

### Customer Notification:
- Title: "Booking Confirmed"
- Message: Appointment pending approval with provider name, date, time
- Link: `route('customer.booking.details', $appointment->id)`
- Type: 'booking'

### Provider Notification:
- Title: "New Booking Request"
- Message: Customer name requested appointment with date and time
- Link: `route('provider.booking.details', $appointment->id)`
- Type: 'booking'

## Email System

1. **AppointmentBooked** (to customer)
2. **AppointmentBooked** (to provider)
3. **GuestAppointmentCredentials** (to new guest users only)

All emails queued for asynchronous sending.

## Advantages

✅ Single booking page for all users  
✅ No separate guest-specific page needed  
✅ Seamless user experience  
✅ Automatic account creation  
✅ Secure credential generation  
✅ Prevents duplicate accounts  
✅ Proper role assignment  
✅ Complete email notifications  
✅ Mobile responsive design  
✅ Consistent styling with existing UI  

## Maintenance Notes

- The standalone `guest-book.blade.php` can now be deprecated
- The `GuestAppointmentController` can be removed if not used elsewhere
- Guest booking routes in `routes/web.php` (lines 24-26) can be removed
- All functionality is now in `DashboardController` and main `book.blade.php`

## Future Enhancements

1. Add social login options for guests
2. Implement email verification for guest accounts
3. Add SMS notification for appointment confirmation
4. Allow guests to track appointments without login (via email link)
5. Implement password reset flow for guest accounts
6. Add option for guest to skip account creation (email-only booking)

---

**Last Updated**: January 2025  
**Status**: ✅ Complete and Production Ready
