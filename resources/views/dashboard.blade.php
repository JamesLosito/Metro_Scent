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
                    <h3 class="text-2xl font-bold mb-4">You're logged in!</h3>

                    {{-- Add custom dashboard content here --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-100 p-4 rounded shadow">
                            <h4 class="font-semibold mb-2">Featured</h4>
                            <p>Check out our new perfume collection!</p>
                            <a href="{{ route('dasboard') }}" class="text-indigo-600 hover:underline">Shop Now</a>
                        </div>
                        <div class="bg-gray-100 p-4 rounded shadow">
                            <h4 class="font-semibold mb-2">Account</h4>
                            <p>Manage your profile and orders here.</p>
                            <a href="{{ route('profile.edit') }}" class="text-indigo-600 hover:underline">Edit Profile</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
