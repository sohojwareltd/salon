<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Appointments', Appointment::count())
                ->description('All time appointments')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success')
                ->chart([7, 12, 15, 18, 22, 25, 30]),
            
            Stat::make('Total Revenue', '$' . number_format(Payment::where('status', 'completed')->sum('total_amount'), 2))
                ->description('Total earnings')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([2000, 2500, 3000, 3200, 3800, 4200, 4500]),
            
            Stat::make('Active Providers', Provider::where('is_active', true)->count())
                ->description('Currently available')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
            
            Stat::make('Total Customers', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('warning'),
            
            Stat::make('Pending Appointments', Appointment::where('status', 'pending')->count())
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Completed Today', Appointment::where('status', 'completed')
                ->whereDate('appointment_date', today())
                ->count())
                ->description('Services completed')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
