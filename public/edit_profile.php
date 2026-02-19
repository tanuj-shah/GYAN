<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/profile.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getProfile($_SESSION['user_id']);
$social = json_decode($user['social_links'] ?? '{}', true);

$pageTitle = "Edit Profile";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen py-10 pt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb / Back Link -->
        <div class="mb-6">
            <a href="dashboard.php"
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-white">
                <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your public presence and professional information.</p>
            </div>

            <form action="api/profile/update.php" method="POST" enctype="multipart/form-data"
                class="divide-y divide-gray-100">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <!-- Personal Information Section -->
                <div class="px-6 md:px-8 py-8 space-y-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-white border border-red-100 text-primary flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            Personal Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Profile Photo -->
                            <div class="col-span-1 md:col-span-2 flex items-center space-x-6">
                                <div class="relative">
                                    <div
                                        class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 ring-4 ring-gray-50 shadow-inner">
                                        <?php if (!empty($user['photo_url'])): ?>
                                            <img src="<?php echo htmlspecialchars(ltrim($user['photo_url'], '/')); ?>"
                                                alt="Profile" class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                    <input type="file" name="photo" id="photo" class="hidden">
                                    <label for="photo"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                        </svg>
                                        Upload New Photo
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="name" id="name"
                                        value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="Your full name">
                                </div>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country"
                                    class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="country" id="country"
                                        value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="Your country">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="px-6 md:px-8 py-8 space-y-8 bg-white">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-white border border-gray-100 text-blue-600 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Professional Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Profession -->
                            <div>
                                <label for="profession"
                                    class="block text-sm font-medium text-gray-700 mb-1">Profession</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="profession" id="profession"
                                        value="<?php echo htmlspecialchars($user['profession'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="Job title / Profession">
                                </div>
                            </div>

                            <!-- Skills -->
                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills (comma
                                    separated)</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="skills" id="skills"
                                        value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="e.g. Graphic Design, Marketing">
                                </div>
                            </div>

                            <!-- Bio -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="bio"
                                    class="block text-sm font-medium text-gray-700 mb-1 flex justify-between">
                                    <span>Public Bio</span>
                                    <span class="text-xs text-gray-400 font-normal">Max 100 characters</span>
                                </label>
                                <textarea id="bio" name="bio" rows="4" maxlength="100"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                    placeholder="Write a short summary about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Profiles Section -->
                <div class="px-6 md:px-8 py-8 space-y-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-white border border-green-100 text-green-600 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </span>
                            Social Profiles
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Facebook -->
                            <div>
                                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook
                                    URL</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </div>
                                    <input type="url" name="facebook" id="facebook"
                                        value="<?php echo htmlspecialchars($social['facebook'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="https://facebook.com/username">
                                </div>
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn
                                    URL</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    </div>
                                    <input type="url" name="linkedin" id="linkedin"
                                        value="<?php echo htmlspecialchars($social['linkedin'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>

                            <!-- Twitter -->
                            <div>
                                <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter
                                    URL</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </div>
                                    <input type="url" name="twitter" id="twitter"
                                        value="<?php echo htmlspecialchars($social['twitter'] ?? ''); ?>"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition-colors"
                                        placeholder="https://twitter.com/username">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="px-6 md:px-8 py-8 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Make Profile Public</h4>
                                <p class="text-xs text-gray-500">Allow other community members to find you in the
                                    directory.</p>
                            </div>
                        </div>
                        <div class="flex items-center h-5">
                            <input id="is_public" name="is_public" type="checkbox" <?php echo ($user['is_public'] ?? 1) ? 'checked' : ''; ?>
                                class="focus:ring-primary h-5 w-5 text-primary border-gray-300 rounded-md transition-colors cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Footer / Action Area -->
                <div
                    class="px-6 md:px-8 py-6 bg-white border-t border-gray-100 flex items-center justify-end space-x-4">
                    <a href="dashboard.php"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-bold rounded-lg text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all transform active:scale-95">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>