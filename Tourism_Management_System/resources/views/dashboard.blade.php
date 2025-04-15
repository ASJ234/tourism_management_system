<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Auth::user()->isAdmin())
                        <div class="mb-4">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Go to Admin Dashboard
                            </a>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->full_name }}!</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Profile Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="text-lg font-medium mb-4">Your Profile</h4>
                            <div class="space-y-2">
                                <p><span class="font-medium">Username:</span> {{ Auth::user()->username }}</p>
                                <p><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                                <p><span class="font-medium">Role:</span> {{ ucfirst(Auth::user()->role) }}</p>
                                <p><span class="font-medium">Contact:</span> {{ Auth::user()->contact_number ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        <!-- Recent Activity Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="text-lg font-medium mb-4">Recent Activity</h4>
                            @if(Auth::user()->activityLogs->count() > 0)
                                <div class="space-y-2">
                                    @foreach(Auth::user()->activityLogs->take(5) as $activity)
                                        <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                                        <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-600">No recent activity</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 