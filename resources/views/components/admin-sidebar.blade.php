<aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Admin Panel</h2>
    </div>
    <nav class="p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span>
        </a>
        <a href="{{ route('admin.employers.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.employers.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-building w-5"></i><span class="ml-3">Employers</span>
        </a>
        <a href="{{ route('admin.candidates.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.candidates.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-users w-5"></i><span class="ml-3">Candidates</span>
        </a>
        <a href="{{ route('admin.jobs.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.jobs.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-briefcase w-5"></i><span class="ml-3">Jobs</span>
        </a>
        <a href="{{ route('admin.applications.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.applications.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-file-alt w-5"></i><span class="ml-3">Applications</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-chart-bar w-5"></i><span class="ml-3">Reports</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-cog w-5"></i><span class="ml-3">Settings</span>
        </a>
        <hr class="my-2 border-gray-200 dark:border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition">
            <i class="fas fa-globe w-5"></i><span class="ml-3">View Site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded transition">
                <i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span>
            </button>
        </form>
    </nav>
</aside>
