<aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Candidate</h2>
    </div>
    <nav class="p-4 space-y-2">
        <a href="{{ route('candidate.dashboard') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.dashboard') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span>
        </a>
        <a href="{{ route('candidate.profile') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.profile*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-user w-5"></i><span class="ml-3">My Profile</span>
        </a>
        <a href="{{ route('candidate.jobs.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.jobs.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-search w-5"></i><span class="ml-3">Browse Jobs</span>
        </a>
        <a href="{{ route('candidate.applications.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.applications.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-file-alt w-5"></i><span class="ml-3">Applications</span>
        </a>
        <a href="{{ route('candidate.letters.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.letters.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-envelope-open-text w-5"></i><span class="ml-3">My Letters</span>
        </a>
        <a href="{{ route('candidate.saved-jobs.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.saved-jobs.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-bookmark w-5"></i><span class="ml-3">Saved Jobs</span>
        </a>
        <a href="{{ route('candidate.resumes.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('candidate.resumes.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-file-upload w-5"></i><span class="ml-3">Resumes</span>
        </a>
        <a href="{{ route('messages.index') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 rounded transition {{ request()->routeIs('messages.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-comments w-5"></i><span class="ml-3">Messages</span>
        </a>
        <hr class="my-2 border-gray-200 dark:border-gray-700">
        <a href="{{ route('home') }}"
            class="flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition">
            <i class="fas fa-globe w-5"></i><span class="ml-3">View Site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit"
                class="w-full flex items-center p-3 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded transition">
                <i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span>
            </button>
        </form>
    </nav>
</aside>
