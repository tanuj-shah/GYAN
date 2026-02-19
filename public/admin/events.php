<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/events.php';

$statusFilter = isset($_GET['status']) ? $_GET['status'] : null;
$regFilter = isset($_GET['registration']) ? ($_GET['registration'] === 'enabled' ? 1 : ($_GET['registration'] === 'disabled' ? 0 : null)) : null;

$events = getEvents(50, 0, $statusFilter, $regFilter);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Events Management</h1>
    <div>
        <form method="GET" class="flex space-x-2">
            <select name="status" class="border rounded px-2 py-1" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="upcoming" <?php echo $statusFilter === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                <option value="past" <?php echo $statusFilter === 'past' ? 'selected' : ''; ?>>Past</option>
            </select>
            <select name="registration" class="border rounded px-2 py-1" onchange="this.form.submit()">
                <option value="">All Registration</option>
                <option value="enabled" <?php echo isset($_GET['registration']) && $_GET['registration'] === 'enabled' ? 'selected' : ''; ?>>Enabled</option>
                <option value="disabled" <?php echo isset($_GET['registration']) && $_GET['registration'] === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
            </select>
            <a href="events.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">Reset</a>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- Create Event Form -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Add New Event</h2>
        <form action="../api/admin/create_event.php" method="POST" enctype="multipart/form-data"
            x-data="{ registrationEnabled: false }">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Event Title</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="title" type="text" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="description" rows="3" required></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Event Date & Time</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="event_date" type="datetime-local" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Posted Date</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="posted_date" type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="location" type="text">
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="registration_enabled" class="form-checkbox h-5 w-5 text-blue-600"
                        x-model="registrationEnabled">
                    <span class="ml-2 text-gray-700 font-bold">Enable Registration</span>
                </label>
            </div>

            <div x-show="registrationEnabled" class="mb-4 p-4 bg-gray-50 rounded border border-gray-200">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Registration URL <span
                            class="text-red-500">*</span></label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="registration_url" type="url" placeholder="https://forms.google.com/..."
                        :required="registrationEnabled">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Registration Deadline</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="registration_deadline" type="datetime-local">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Cover Image</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="image" type="file" accept="image/*">
            </div>

            <div class="flex items-center justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    type="submit">
                    Create Event
                </button>
            </div>
        </form>
    </div>

    <!-- Events List -->
    <div class="bg-white rounded shadow p-6 overflow-y-auto" style="max-height: 800px;">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Events List</h2>
        <?php if (empty($events)): ?>
            <p class="text-gray-500 italic">No events found matching your criteria.</p>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($events as $event): ?>
                    <li class="py-4 hover:bg-gray-50 transition duration-150 ease-in-out px-2 rounded">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <span class="mr-2"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></span>

                                    <?php if ($event['event_status'] === 'upcoming'): ?>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mr-2">Upcoming</span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 mr-2">Past</span>
                                    <?php endif; ?>

                                    <?php if ($event['registration_enabled']): ?>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Reg
                                            On</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="events-edit.php?id=<?php echo $event['id']; ?>"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                <a href="../event.php?slug=<?php echo $event['slug']; ?>" target="_blank"
                                    class="text-gray-600 hover:text-gray-900 text-sm font-medium">View</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>

</div>
</div>
</body>

</html>