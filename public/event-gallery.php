<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;
if (!$id)
    redirect('gallery.php');

$stmt = $pdo->prepare("SELECT * FROM gallery_events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event)
    redirect('gallery.php');

$pageTitle = $event['title'];
require_once __DIR__ . '/../includes/header.php';



// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM gallery_images WHERE event_id = ?");
$stmt->execute([$id]);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE event_id = ? ORDER BY is_featured DESC, order_index ASC, created_at DESC LIMIT ? OFFSET ?");
$stmt->bindParam(1, $id, PDO::PARAM_INT);
$stmt->bindParam(2, $perPage, PDO::PARAM_INT);
$stmt->bindParam(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="min-h-screen bg-white" x-data="{ 
    lightbox: false, 
    imgSrc: '', 
    imgAlt: '',
    open(src, alt) { 
        this.imgSrc = src; 
        this.imgAlt = alt; 
        this.lightbox = true; 
        document.body.style.overflow = 'hidden';
    },
    close() { 
        this.lightbox = false; 
        this.imgSrc = ''; 
        document.body.style.overflow = 'auto';
    }
}">

    <!-- Header -->
    <div class="relative bg-gray-900 py-24 px-4 text-center">
        <!-- Background Image/Overlay -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Use first image as blurred background if available -->
            <?php if (!empty($images[0])): ?>
                <img src="uploads/gallery/<?php echo $event['id']; ?>/<?php echo $images[0]['filename']; ?>"
                    class="w-full h-full object-cover opacity-30 blur-sm scale-110">
            <?php else: ?>
                <div class="w-full h-full bg-gradient-to-br from-gray-900 to-blue-900 opacity-90 start"></div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto">
            <a href="gallery.php"
                class="inline-flex items-center text-gray-300 hover:text-white mb-6 text-sm font-bold uppercase tracking-widest transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Gallery
            </a>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tight"
                style="font-family: 'Times New Roman', serif;">
                <?php echo htmlspecialchars($event['title']); ?>
            </h1>
            <p class="text-lg text-gray-200 font-light max-w-2xl mx-auto leading-relaxed">
                <?php echo nl2br(htmlspecialchars($event['description'] ?? '')); ?>
            </p>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <?php if (empty($images)): ?>
            <p class="text-center text-gray-400">No images available.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($images as $img):
                    $src = "uploads/gallery/{$event['id']}/{$img['filename']}";
                    ?>
                    <div class="group relative aspect-[4/3] bg-gray-100 rounded-xl overflow-hidden cursor-zoom-in shadow-md hover:shadow-2xl transition-all duration-300"
                        @click="open('<?php echo $src; ?>', '<?php echo htmlspecialchars(addslashes($img['alt_text'] ?? '')); ?>')">
                        <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($img['alt_text'] ?? ''); ?>"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                            loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-16 flex justify-center space-x-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?id=<?php echo $id; ?>&page=<?php echo $i; ?>"
                        class="w-10 h-10 flex items-center justify-center rounded-full font-bold transition-all <?php echo $i === $page ? 'bg-[#4169E1] text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lightbox -->
    <div x-show="lightbox" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <button @click="close()" class="absolute top-6 right-6 text-white/50 hover:text-white z-50 p-2">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-4 max-w-7xl w-full h-full flex flex-col items-center justify-center" @click.outside="close()">
            <img :src="imgSrc" class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl">
            <p x-text="imgAlt" class="mt-4 text-white/80 font-medium"></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
