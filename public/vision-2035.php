<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/vision2035.php';

if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to access this page.');
    redirect('login.php');
}

$pageTitle = "Vision 2035";
require_once __DIR__ . '/../includes/header.php';

// Get user's proposals
$userProposals = getUserProposals($_SESSION['user_id']);
?>

<div class="min-h-screen pt-24 pb-12 md:pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb / Back Link -->
        <div class="mb-12">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="dashboard.php"
                            class="text-sm font-bold text-gray-500 hover:text-primary transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-bold text-gray-400 md:ml-2">Vision 2035</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-10 md:mb-16" data-aos="fade-down">
            <div class="inline-block mb-6 md:mb-8">
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-3xl p-6 flex items-center justify-center shadow-xl mx-auto">
                    <img src="img/Vision_2035_logo.png" alt="Vision 2035" class="w-full h-full object-contain">
                </div>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter mb-4 md:mb-6">Vision
                2035</h1>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Contribute your innovative ideas and solutions to shape Nepal's future. Whether you're addressing policy
                challenges, local issues, or proposing technical innovations, your expertise can drive meaningful change
                for our communities.
            </p>
        </div>

        <!-- Flash Messages -->
        <?php
        $success = getFlashMessage('success');
        $error = getFlashMessage('error');
        if ($success): ?>
            <div class="bg-white border-l-4 border-green-500 p-6 rounded-2xl mb-8 shadow-sm" data-aos="fade-up">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800 font-medium"><?php echo htmlspecialchars($success); ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-white border-l-4 border-red-500 p-6 rounded-2xl mb-8 shadow-sm" data-aos="fade-up">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-red-800 font-medium"><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Proposal Submission Form -->
        <form
            class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden mb-12 p-6 md:p-10"
            data-aos="fade-up" action="api/vision2035/submit_proposal.php" method="POST" enctype="multipart/form-data"
            x-data="proposalForm()" onsubmit="return handleSubmit(event)">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="redirect" value="1">

            <!-- Title -->
            <div class="mb-8">
                <label for="title" class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Proposal Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" required maxlength="255"
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium"
                    placeholder="Enter a clear, concise title for your proposal">
            </div>

            <!-- Category -->
            <div class="mb-8">
                <label for="category" class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="category" name="category" required x-model="category"
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium">
                    <option value="">Select a category</option>
                    <option value="Policy Recommendations">Policy Recommendations</option>
                    <option value="Local issue/grievance">Local issue/grievance</option>
                    <option value="Technical solution">Technical solution</option>
                    <option value="Visionary Concept">Visionary Concept</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Other Category (conditional) -->
            <div class="mb-8" x-show="category === 'Other'" x-cloak>
                <label for="other_category"
                    class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Specify Category <span class="text-red-500">*</span>
                </label>
                <input type="text" id="other_category" name="other_category" maxlength="255"
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium"
                    placeholder="Please specify your category">
            </div>

            <!-- Delegation -->
            <div class="mb-8">
                <label for="delegation" class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Target Delegation <span class="text-red-500">*</span>
                </label>
                <select id="delegation" name="delegation" required x-model="delegation"
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium">
                    <option value="">Select target delegation</option>
                    <option value="Ministry of Finance">Ministry of Finance</option>
                    <option value="National Planning Commission">National Planning Commission</option>
                    <option value="Municipality">Municipality</option>
                    <option value="Local Level">Local Level</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Other Delegation (conditional) -->
            <div class="mb-8" x-show="delegation === 'Other'" x-cloak>
                <label for="other_delegation"
                    class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Specify Delegation <span class="text-red-500">*</span>
                </label>
                <input type="text" id="other_delegation" name="other_delegation" maxlength="255"
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium"
                    placeholder="Please specify the target delegation">
            </div>

            <!-- Priority -->
            <div class="mb-8">
                <label class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Priority Level <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="Low" required class="peer sr-only">
                        <div
                            class="px-6 py-4 border-2 border-gray-200 rounded-2xl text-center font-bold transition-all peer-checked:border-green-500 peer-checked:bg-white peer-checked:text-green-700">
                            Low
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="Medium" required class="peer sr-only">
                        <div
                            class="px-6 py-4 border-2 border-gray-200 rounded-2xl text-center font-bold transition-all peer-checked:border-yellow-500 peer-checked:bg-white peer-checked:text-yellow-700">
                            Medium
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="priority" value="High" required class="peer sr-only">
                        <div
                            class="px-6 py-4 border-2 border-gray-200 rounded-2xl text-center font-bold transition-all peer-checked:border-red-500 peer-checked:bg-white peer-checked:text-red-700">
                            High
                        </div>
                    </label>
                </div>
            </div>

            <!-- Proposal Text -->
            <div class="mb-8">
                <label for="proposal_text" class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Your Proposal <span class="text-red-500">*</span>
                    <span class="text-xs font-normal text-gray-500 normal-case tracking-normal ml-2">(Max 600
                        words)</span>
                </label>

                <!-- Rich Text Formatting Toolbar -->
                <div class="border-2 border-gray-200 rounded-t-2xl bg-white px-4 py-2 flex gap-2">
                    <button type="button" onclick="formatText('bold')"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-bold text-sm"
                        title="Bold">
                        <strong>B</strong>
                    </button>
                    <button type="button" onclick="formatText('italic')"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors italic text-sm"
                        title="Italic">
                        <em>I</em>
                    </button>
                    <button type="button" onclick="formatText('underline')"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors underline text-sm"
                        title="Underline">
                        <u>U</u>
                    </button>
                </div>

                <!-- Rich Text Editor (contenteditable) -->
                <div id="proposalEditor" contenteditable="true"
                    class="min-h-[300px] px-6 py-4 border-2 border-t-0 border-gray-200 rounded-b-2xl focus:border-primary focus:outline-none transition-colors font-medium resize-y overflow-y-auto"
                    placeholder="Describe your proposal in detail. Include the problem, your solution, expected impact, and implementation strategy."
                    style="empty:before { content: attr(placeholder); color: #9CA3AF; }"></div>

                <!-- Hidden textarea to submit the data -->
                <textarea id="proposal_text" name="proposal_text" class="hidden"></textarea>

                <!-- Word Counter -->
                <div class="mt-2 flex justify-between items-center text-sm">
                    <span id="wordCount" class="text-gray-500">0 / 600 words</span>
                    <span id="wordCountError" class="text-red-600 font-medium hidden">Exceeds 600 word limit!</span>
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-8">
                <label class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">
                    Supporting Documents (Optional)
                </label>
                <div
                    class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-primary transition-colors">
                    <input type="file" name="files[]" multiple accept=".pdf,.xls,.xlsx,.csv,.png,.jpg,.jpeg"
                        class="hidden" id="fileInput" @change="handleFiles($event)">
                    <label for="fileInput" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-gray-600 font-medium mb-2">Click to upload files</p>
                        <p class="text-xs text-gray-400">PDF, Excel, CSV, or Images (Max 10MB each)</p>
                    </label>
                </div>

                <!-- Selected Files Display -->
                <div x-show="files.length > 0" class="mt-4 space-y-2" x-cloak>
                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700" x-text="file.name"></span>
                                <span class="text-xs text-gray-400" x-text="formatFileSize(file.size)"></span>
                            </div>
                            <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-primary hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest px-12 py-4 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Submit Proposal
                </button>
            </div>
        </form>

        <!-- User's Proposals List -->
        <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="p-6 md:p-10 border-b border-gray-50">
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Your Proposals</h2>
                <p class="text-gray-500 mt-2">Track the status of your submissions</p>
            </div>

            <div class="p-6 md:p-10">
                <?php if (empty($userProposals)): ?>
                    <div class="text-center py-20">
                        <div
                            class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100">
                            <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">No proposals yet</h4>
                        <p class="text-gray-400 text-sm max-w-xs mx-auto">Submit your first proposal above to start
                            contributing to Vision 2035.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <th class="pb-6">Title</th>
                                    <th class="pb-6">Submitted</th>
                                    <th class="pb-6">Priority</th>
                                    <th class="pb-6">Status</th>
                                    <th class="pb-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php foreach ($userProposals as $proposal): ?>
                                    <tr class="group">
                                        <td class="py-6">
                                            <div class="font-black text-gray-900 group-hover:text-primary transition-colors">
                                                <?php echo htmlspecialchars($proposal['title']); ?>
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                                <?php echo htmlspecialchars($proposal['category']); ?>
                                            </div>
                                        </td>
                                        <td class="py-6">
                                            <span class="text-xs font-bold text-gray-500 uppercase">
                                                <?php echo date('M d, Y', strtotime($proposal['submitted_at'])); ?>
                                            </span>
                                        </td>
                                        <td class="py-6">
                                            <?php
                                            $priorityClasses = [
                                                'Low' => 'bg-white border border-green-100 text-green-600',
                                                'Medium' => 'bg-white border border-yellow-100 text-yellow-600',
                                                'High' => 'bg-white border border-red-100 text-red-600'
                                            ];
                                            $pClass = $priorityClasses[$proposal['priority']] ?? 'bg-white border border-gray-100 text-gray-600';
                                            ?>
                                            <span
                                                class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $pClass; ?>">
                                                <?php echo htmlspecialchars($proposal['priority']); ?>
                                            </span>
                                        </td>
                                        <td class="py-6">
                                            <?php
                                            $statusClasses = [
                                                'Submitted' => 'bg-white border border-blue-100 text-blue-600',
                                                'Under review' => 'bg-white border border-purple-100 text-purple-600',
                                                'Rejected' => 'bg-white border border-red-100 text-red-600',
                                                'Check Your Mail' => 'bg-white border border-green-100 text-green-600'
                                            ];
                                            $sClass = $statusClasses[$proposal['status']] ?? 'bg-white border border-gray-100 text-gray-600';
                                            ?>
                                            <span
                                                class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $sClass; ?>">
                                                <?php echo htmlspecialchars($proposal['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-6 text-right">
                                            <a href="#" onclick="viewProposal(<?php echo $proposal['id']; ?>); return false;"
                                                class="text-xs font-black text-primary uppercase tracking-widest hover:underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Alpine.js Component for File Upload -->
<script>
    function proposalForm() {
        return {
            category: '',
            delegation: '',
            files: [],
            wordCount: 0,
            maxWords: 600,

            handleFiles(event) {
                const newFiles = Array.from(event.target.files);
                const maxSize = 10 * 1024 * 1024; // 10MB

                for (let file of newFiles) {
                    if (file.size > maxSize) {
                        alert(`File "${file.name}" exceeds 10MB limit.`);
                        continue;
                    }
                    this.files.push(file);
                }
            },

            removeFile(index) {
                this.files.splice(index, 1);
                // Reset file input
                document.getElementById('fileInput').value = '';
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }
        }
    }

    // Rich Text Editor Functions
    function formatText(command) {
        document.execCommand(command, false, null);
        document.getElementById('proposalEditor').focus();
    }

    function updateWordCount() {
        const editor = document.getElementById('proposalEditor');
        const text = editor.innerText || editor.textContent;
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const count = text.trim().length === 0 ? 0 : words.length;

        document.getElementById('wordCount').textContent = `${count} / 600 words`;

        const errorSpan = document.getElementById('wordCountError');
        if (count > 600) {
            errorSpan.classList.remove('hidden');
            document.getElementById('wordCount').classList.add('text-red-600', 'font-bold');
        } else {
            errorSpan.classList.add('hidden');
            document.getElementById('wordCount').classList.remove('text-red-600', 'font-bold');
        }

        return count;
    }

    function handlePaste(event) {
        event.preventDefault();

        // Get plain text from clipboard
        const text = (event.clipboardData || window.clipboardData).getData('text/plain');

        // Insert as plain text
        document.execCommand('insertText', false, text);

        // Update word count
        updateWordCount();
    }

    function handleSubmit(event) {
        const editor = document.getElementById('proposalEditor');
        const hiddenTextarea = document.getElementById('proposal_text');
        const wordCount = updateWordCount();

        // Check word count
        if (wordCount > 600) {
            event.preventDefault();
            alert('Your proposal exceeds the 600-word limit. Please shorten your text before submitting.');
            return false;
        }

        if (wordCount === 0) {
            event.preventDefault();
            alert('Please enter your proposal text before submitting.');
            return false;
        }

        // Copy HTML content to hidden textarea for submission
        hiddenTextarea.value = editor.innerHTML;

        return true;
    }

    function viewProposal(id) {
        // TODO: Implement modal or redirect to view proposal details
        alert('View proposal #' + id + ' - Feature coming soon!');
    }

    // Initialize word count on page load
    document.addEventListener('DOMContentLoaded', function () {
        const editor = document.getElementById('proposalEditor');

        if (editor) {
            // Initialize word count
            updateWordCount();

            // Add input event listener for word count
            editor.addEventListener('input', function () {
                updateWordCount();
            });

            // Add paste event listener
            editor.addEventListener('paste', function (event) {
                handlePaste(event);
            });

            // Add placeholder behavior for contenteditable
            editor.addEventListener('focus', function () {
                if (this.innerHTML.trim() === '') {
                    this.innerHTML = '';
                }
            });

            editor.addEventListener('blur', function () {
                if (this.innerHTML.trim() === '') {
                    this.classList.add('empty');
                } else {
                    this.classList.remove('empty');
                }
            });
        }
    });
</script>

<style>
    /* Placeholder styling for contenteditable */
    #proposalEditor:empty:before {
        content: attr(placeholder);
        color: #9CA3AF;
        font-style: italic;
    }

    #proposalEditor:focus:before {
        content: '';
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 1000, once: true, easing: 'ease-out-quint' });
        }
    });
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>