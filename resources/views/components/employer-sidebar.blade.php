<aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ Auth::user()->employer->company_name ?? 'Employer Panel' }}</h2>
    </div>
    <nav class="p-4 space-y-2">
        <a href="{{ route('employer.dashboard') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.dashboard') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span>
        </a>
        <a href="{{ route('employer.profile') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.profile*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-building w-5"></i><span class="ml-3">Company Profile</span>
        </a>
        <a href="{{ route('employer.jobs.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.jobs.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-briefcase w-5"></i><span class="ml-3">My Jobs</span>
        </a>
        <a href="{{ route('employer.applications.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.applications.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-file-alt w-5"></i><span class="ml-3">Applications</span>
        </a>
        <a href="{{ route('employer.interviews.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.interviews.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-calendar-check w-5"></i><span class="ml-3">Interviews</span>
        </a>
        <a href="{{ route('employer.letters.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('employer.letters.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-envelope-open-text w-5"></i><span class="ml-3">Offer & Joining Letters</span>
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('messages.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-comments w-5"></i><span class="ml-3">Messages</span>
        </a>
        <hr class="my-2 border-gray-200 dark:border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition">
            <i class="fas fa-globe w-5"></i><span class="ml-3">View Site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="w-full flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded transition">
                <i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span>
            </button>
        </form>
    </nav>
</aside>
