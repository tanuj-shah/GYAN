<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/events.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: events.php");
    exit;
}

// Fetch event manually since we don't have getEventById in generic includes yet, 
// usually getEventBySlug is there. Let's add getEventById inline or use direct query 
// if functions are not sufficient, but ideally should be in includes.
// I'll make a direct query here for simplicity or assume getEvents can filter by something.
// Actually, let's use the DB connection.
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: events.php");
    exit;
}
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Event</h1>
        <a href="events.php" class="text-blue-600 hover:text-blue-800">&larr; Back to List</a>
    </div>

    <div class="bg-white rounded shadow p-8">
        <form action="../api/admin/update_event.php" method="POST" enctype="multipart/form-data"
            x-data="{ registrationEnabled: <?php echo $event['registration_enabled'] ? 'true' : 'false'; ?> }">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Event Title</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="title" type="text" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="description" rows="5"
                    required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Event Date & Time</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="event_date" type="datetime-local"
                        value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Posted Date</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="posted_date" type="datetime-local"
                        value="<?php echo date('Y-m-d\TH:i', strtotime($event['posted_date'])); ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="location" type="text" value="<?php echo htmlspecialchars($event['location']); ?>">
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
                        name="registration_url" type="url"
                        value="<?php echo htmlspecialchars($event['registration_url'] ?? ''); ?>"
                        placeholder="https://forms.google.com/..." :required="registrationEnabled">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Registration Deadline</label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="registration_deadline" type="datetime-local"
                        value="<?php echo $event['registration_deadline'] ? date('Y-m-d\TH:i', strtotime($event['registration_deadline'])) : ''; ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Cover Image (Leave empty to keep
                    current)</label>
                <?php if ($event['image_url']): ?>
                    <div class="mb-2">
                        <img src="../<?php echo trim($event['image_url'], '/'); ?>" alt="Current Image"
                            class="h-32 object-cover rounded">
                    </div>
                <?php endif; ?>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="image" type="file" accept="image/*">
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="events.php" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</body>

</html>