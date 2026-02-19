<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/profile.php';

$query = sanitize($_GET['q'] ?? '');
$country = sanitize($_GET['country'] ?? '');
$profession = sanitize($_GET['profession'] ?? '');

$members = searchMembers($query, $country, $profession);

$pageTitle = "Community Directory";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen">
    <!-- Premium Hero Section -->
    <div class="relative bg-white pt-24 pb-16 md:pb-32 overflow-hidden">
        <!-- Decorative Framing Flags -->
        <div class="absolute inset-0 pointer-events-none hidden md:block select-none overflow-hidden"
            aria-hidden="true">
            <img src="img/flag.webp" alt=""
                class="absolute left-[-2rem] lg:left-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-50 object-contain"
                loading="lazy">
            <img src="img/flag.webp" alt=""
                class="absolute right-[-2rem] lg:right-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-40 object-contain scale-x-[-1]"
                loading="lazy">
        </div>
        <div class="absolute inset-0 z-0">
            <!-- Mountain Backdrop -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="img/mountain.webp" alt=""
                    class="absolute inset-0 w-full h-[130%] object-cover object-top opacity-20 -translate-y-12">
            </div>
            <div class="absolute inset-0 bg-white opacity-20">
            </div>
            <div class="absolute inset-x-0 bottom-0 h-24"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1
                class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl uppercase tracking-widest">
                Global Network</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Community Directory</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                Connect, collaborate, and grow with Nepali professionals and youth from every corner of the world.
            </p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-20">
        <!-- Sophisticated Search & Filter Bar -->
        <div class="max-w-4xl mx-auto mb-16">
            <form action="" method="GET"
                class="bg-white p-2 rounded-2xl shadow-premium border border-gray-100 flex flex-col md:flex-row items-stretch md:items-center space-y-2 md:space-y-0 md:space-x-2">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="q" id="q" value="<?php echo $query; ?>" placeholder="Name or skills..."
                        class="block w-full pl-11 pr-4 py-4 border-none focus:ring-0 rounded-xl text-gray-900 placeholder-gray-400 sm:text-sm">
                </div>
                <div class="h-8 w-px bg-gray-100 hidden md:block"></div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" name="country" id="country" value="<?php echo $country; ?>" placeholder="Country"
                        class="block w-full pl-10 pr-4 py-4 border-none focus:ring-0 rounded-xl text-gray-900 placeholder-gray-400 sm:text-sm">
                </div>
                <div class="h-8 w-px bg-gray-100 hidden md:block"></div>
                <button type="submit"
                    class="bg-primary hover:bg-red-700 text-white font-bold py-4 px-8 rounded-xl transition-all transform active:scale-95 shadow-glow flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Directory Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-12 md:pb-20">
    <?php if (count($members) > 0): ?>
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($members as $member):
                $social = json_decode($member['social_links'] ?? '{}', true);
                ?>
                <div
                    class="group bg-white rounded-[2rem] py-4 px-6 shadow-sm border-[3px] border-[#8B0000] transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 flex flex-col items-center text-center">
                    <div class="relative mb-2">
                        <div
                            class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-gray-50 shadow-inner group-hover:ring-primary/20 transition-all duration-500">
                            <?php if (!empty($member['photo_url'])): ?>
                                <img class="h-full w-full object-cover"
                                    src="<?php echo htmlspecialchars(ltrim($member['photo_url'], '/')); ?>" alt="">
                            <?php else: ?>
                                <div class="h-full w-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <span
                                        class="text-xl font-bold"><?php echo strtoupper(substr($member['full_name'], 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-primary transition-colors">
                        <?php echo htmlspecialchars($member['full_name']); ?>
                    </h3>
                    <p class="text-sm font-semibold text-primary/80 uppercase tracking-widest mb-2">
                        <?php echo htmlspecialchars($member['profession'] ?: 'Community Member'); ?>
                    </p>

                    <?php if (!empty($member['bio'])): ?>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-3 leading-relaxed">
                            <?php echo htmlspecialchars(mb_strimwidth($member['bio'], 0, 100, "...")); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Skills -->
                    <div class="flex flex-wrap justify-center gap-2 mb-3">
                        <?php
                        $skills = !empty($member['skills']) ? explode(',', $member['skills']) : [];
                        foreach (array_slice($skills, 0, 3) as $skill):
                            ?>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-red-100 text-primary">
                                <?php echo htmlspecialchars(trim($skill)); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-auto w-full pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center text-gray-400 text-xs font-medium">
                            <svg class="h-4 w-4 mr-1 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <?php echo htmlspecialchars($member['country'] ?: 'Global'); ?>
                        </div>

                        <!-- Social Links -->
                        <div class="flex space-x-3">
                            <?php if (!empty($social['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($social['linkedin']); ?>" target="_blank"
                                    class="text-gray-400 hover:text-[#0077b5] transition-colors">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($social['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($social['facebook']); ?>" target="_blank"
                                    class="text-gray-400 hover:text-[#1877f2] transition-colors">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 py-20 text-center">
            <svg class="mx-auto h-20 w-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-4 text-xl font-bold text-gray-900">No members found</h3>
            <p class="mt-2 text-gray-500 max-w-sm mx-auto">We couldn't find any community members matching your current
                filters. Try broadening your search.</p>
            <div class="mt-8">
                <a href="directory.php" class="text-primary font-bold hover:underline">Clear all filters</a>
            </div>
        </div>
    <?php endif; ?>
</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>