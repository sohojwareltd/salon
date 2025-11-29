<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->name('contact.store');

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// Providers
Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');

// Authentication Routes
Auth::routes(['verify' => true]);

// Test Login Route (Remove in production)
Route::get('/test/login-as-user/{id}', function ($id) {
    if (!app()->environment('local')) {
        abort(404);
    }
    
    $user = \App\Models\User::findOrFail($id);
    Auth::login($user);
    
    $roleName = $user->getRoleName();
    $redirectTo = match($roleName) {
        Role::ADMIN => '/admin',
        Role::PROVIDER => '/provider-dashboard',
        Role::CUSTOMER => '/customer-dashboard',
        default => '/dashboard',
    };
    
    return redirect($redirectTo)->with('success', "Logged in as {$user->name}");
})->name('test.login');

// Email Testing Routes (Remove in production)
if (app()->environment('local')) {
    require __DIR__.'/email-test.php';
}

// Provider Dashboard Routes
Route::middleware(['auth', 'role:provider'])->prefix('provider-dashboard')->name('provider.')->group(function () {
    Route::get('/', [App\Http\Controllers\Provider\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [App\Http\Controllers\Provider\DashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/booking/{appointment}/details', [App\Http\Controllers\Provider\DashboardController::class, 'bookingDetails'])->name('booking.details');
    Route::post('/bookings/{appointment}/status', [App\Http\Controllers\Provider\DashboardController::class, 'updateStatus'])->name('bookings.update-status');
    
    // Services CRUD
    Route::get('/services', [App\Http\Controllers\Provider\DashboardController::class, 'servicesIndex'])->name('services.index');
    Route::get('/services/create', [App\Http\Controllers\Provider\DashboardController::class, 'servicesCreate'])->name('services.create');
    Route::post('/services', [App\Http\Controllers\Provider\DashboardController::class, 'servicesStore'])->name('services.store');
    Route::get('/services/{service}/edit', [App\Http\Controllers\Provider\DashboardController::class, 'servicesEdit'])->name('services.edit');
    Route::put('/services/{service}', [App\Http\Controllers\Provider\DashboardController::class, 'servicesUpdate'])->name('services.update');
    Route::delete('/services/{service}', [App\Http\Controllers\Provider\DashboardController::class, 'servicesDestroy'])->name('services.destroy');
    
    Route::get('/wallet', [App\Http\Controllers\Provider\DashboardController::class, 'wallet'])->name('wallet.index');
    Route::get('/reviews', [App\Http\Controllers\Provider\DashboardController::class, 'reviews'])->name('reviews.index');
    
    // Notification Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/latest', [App\Http\Controllers\NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    
    Route::get('/profile', [App\Http\Controllers\Provider\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Provider\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/info', [App\Http\Controllers\Provider\DashboardController::class, 'updateProfileInfo'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\Provider\DashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Provider\DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::put('/settings/social', [App\Http\Controllers\Provider\DashboardController::class, 'updateSocial'])->name('settings.update-social');
    Route::put('/settings/notifications', [App\Http\Controllers\Provider\DashboardController::class, 'updateNotifications'])->name('settings.notifications');
});

// PayPal Callback Routes (outside auth middleware for PayPal redirect)
Route::get('/payment/paypal/success', [App\Http\Controllers\Customer\PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
Route::get('/payment/paypal/cancel', [App\Http\Controllers\Customer\PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');

// Customer Dashboard Routes
Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer-dashboard')->name('customer.')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [App\Http\Controllers\Customer\DashboardController::class, 'bookings'])->name('bookings');
    Route::get('/booking/{appointment}/details', [App\Http\Controllers\Customer\DashboardController::class, 'bookingDetails'])->name('booking.details');
    Route::get('/payments', [App\Http\Controllers\Customer\DashboardController::class, 'payments'])->name('payments');
    
    // Payment Routes
    Route::get('/payment/{appointment}', [App\Http\Controllers\Customer\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{appointment}/checkout', [App\Http\Controllers\Customer\PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::post('/payment/{appointment}/paypal', [App\Http\Controllers\Customer\PaymentController::class, 'createPayPalOrder'])->name('payment.paypal');
    Route::get('/payment/{appointment}/success', [App\Http\Controllers\Customer\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{appointment}/cancel', [App\Http\Controllers\Customer\PaymentController::class, 'cancel'])->name('payment.cancel');
    
    Route::get('/review/{appointment}', [App\Http\Controllers\Customer\DashboardController::class, 'review'])->name('review');
    Route::post('/review/{appointment}', [App\Http\Controllers\Customer\DashboardController::class, 'storeReview'])->name('review.store');
    
    // Notification Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/latest', [App\Http\Controllers\NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    
    Route::get('/profile', [App\Http\Controllers\Customer\DashboardController::class, 'profile'])->name('profile');
    Route::get('/settings', [App\Http\Controllers\Customer\DashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Customer\DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::put('/password', [App\Http\Controllers\Customer\DashboardController::class, 'updatePassword'])->name('password.update');
    Route::put('/notifications', [App\Http\Controllers\Customer\DashboardController::class, 'updateNotifications'])->name('notifications.update');
    Route::put('/privacy', [App\Http\Controllers\Customer\DashboardController::class, 'updatePrivacy'])->name('privacy.update');
    Route::delete('/account', [App\Http\Controllers\Customer\DashboardController::class, 'deleteAccount'])->name('account.delete');
});

// Legacy dashboard route (will redirect based on role)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments/book/{provider?}', [DashboardController::class, 'bookingPage'])->name('appointments.book');
    Route::get('/appointments/available-slots/{provider}', [DashboardController::class, 'availableSlots'])->name('appointments.available-slots');
    Route::post('/appointments', [DashboardController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/thank-you', [DashboardController::class, 'thankYou'])->name('appointments.thank-you');
});


Route::get('test-logout', function () {
    Auth::logout();
    return redirect()->route('home');
});

// Dynamic Pages
Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// FAQs
Route::get('/faqs', [App\Http\Controllers\FaqController::class, 'index'])->name('faqs.index');
