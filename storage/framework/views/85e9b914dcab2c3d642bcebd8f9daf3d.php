<?php $__env->startSection('title', 'Messages'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #00288e; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #002072; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-custom">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">Messages</h1>
        <p class="text-text-secondary">Chat with hosts about your stays</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-[calc(100vh-200px)] min-h-[500px]">
        <!-- Conversation List -->
        <aside class="md:col-span-4 bg-surface-card rounded-xl flex flex-col overflow-hidden shadow-sm border border-border-color">
            <div class="p-4 border-b border-border-color">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-sm">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-gray-50 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm"
                           placeholder="Search conversations" type="text" id="searchConversations">
                </div>
            </div>
            <div class="flex-grow overflow-y-auto custom-scrollbar" id="conversationsList">
                <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 border-b border-border-color hover:bg-gray-50 transition cursor-pointer <?php echo e($loop->first ? 'bg-brand-50 border-l-4 border-l-brand-500' : ''); ?>"
                     onclick="loadConversation(<?php echo e($conv['id']); ?>)">
                    <div class="flex gap-3">
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-semibold">
                                <?php echo e(substr($conv['other_user']['name'] ?? 'G', 0, 1)); ?>

                            </div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-success rounded-full border-2 border-white"></div>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-sm font-semibold text-text-primary truncate"><?php echo e($conv['other_user']['name'] ?? 'User'); ?></h3>
                                <span class="text-[10px] text-text-muted"><?php echo e($conv['last_message_time'] ?? '2 min'); ?></span>
                            </div>
                            <p class="text-xs text-brand-600 font-medium truncate"><?php echo e($conv['last_message'] ?? 'New message'); ?></p>
                            <p class="text-[11px] text-text-muted truncate"><?php echo e($conv['property']['title'] ?? 'Property'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-8 text-center">
                    <p class="text-text-muted text-sm">No conversations yet</p>
                </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Chat Window -->
        <section class="hidden md:flex md:col-span-8 bg-surface-card rounded-xl flex-col shadow-sm border border-border-color overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-border-color flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-semibold" id="chatUserAvatar">S</div>
                    <div>
                        <h2 class="font-semibold text-text-primary" id="chatUserNameFull">Select a conversation</h2>
                        <p class="text-xs text-text-muted flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">home</span>
                            <span id="chatPropertyInfo">No conversation selected</span>
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 bg-white border border-border-color rounded-lg text-xs font-semibold text-text-primary hover:bg-gray-50 transition">
                        View Details
                    </button>
                </div>
            </div>

            <div id="chatMessages" class="flex-grow overflow-y-auto p-6 flex flex-col gap-4 custom-scrollbar bg-gray-50">
                <div class="flex justify-center">
                    <span class="px-3 py-1 bg-white text-text-muted rounded-full text-xs shadow-sm">Select a conversation to start messaging</span>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-border-color">
                <div class="flex items-end gap-3 bg-gray-50 rounded-2xl p-2 border border-border-color">
                    <button class="p-2 text-text-muted hover:text-brand-600 transition">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                    </button>
                    <textarea class="flex-grow bg-transparent border-none focus:ring-0 text-text-primary py-2 resize-none"
                              placeholder="Type your message..." rows="1" id="messageInput"></textarea>
                    <button class="bg-brand-500 text-white p-2 rounded-xl hover:bg-brand-600 transition" onclick="sendMessage()">
                        <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    let currentConversationId = null;

    function loadConversation(id) {
        currentConversationId = id;
        document.getElementById('chatMessages').innerHTML = '<div class="flex justify-center"><span class="px-3 py-1 bg-white text-text-muted rounded-full text-xs shadow-sm">Loading messages...</span></div>';

        fetch('/api/messages/' + id)
            .then(res => res.json())
            .then(data => { updateChatUI(data); });
    }

    function updateChatUI(data) {
        const firstLetter = data.other_user?.name?.charAt(0) || '?';
        document.getElementById('chatUserAvatar').textContent = firstLetter;
        document.getElementById('chatUserNameFull').textContent = data.other_user?.name || 'User';
        document.getElementById('chatPropertyInfo').textContent = data.property?.title || 'Property';

        const currentUserId = <?php echo e(Auth::id() ?? 0); ?>;

        const messagesHtml = data.messages?.map(msg => `
            <div class="flex gap-3 max-w-[80%] ${msg.sender_id == currentUserId ? 'self-end' : 'self-start'}">
                <div class="bg-${msg.sender_id == currentUserId ? 'brand-500 text-white' : 'white shadow-sm border border-border-color'} p-3 rounded-2xl ${msg.sender_id == currentUserId ? 'rounded-br-none' : 'rounded-bl-none'}">
                    <p class="text-sm">${msg.message}</p>
                    <span class="text-[10px] ${msg.sender_id == currentUserId ? 'text-brand-100' : 'text-text-muted'} block mt-1 text-right">
                        ${new Date(msg.created_at).toLocaleTimeString()}
                    </span>
                </div>
            </div>
        `).join('') || '<div class="text-center text-text-muted">No messages yet</div>';

        document.getElementById('chatMessages').innerHTML = messagesHtml;
        // Scroll to bottom
        const chatContainer = document.getElementById('chatMessages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function sendMessage() {
        const input = document.getElementById('messageInput');
        if(!input.value.trim() || !currentConversationId) return;

        fetch('/api/messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                conversation_id: currentConversationId,
                message: input.value
            })
        }).then(() => {
            input.value = '';
            loadConversation(currentConversationId);
        });
    }

    // Poll for new messages every 5 seconds
    setInterval(() => {
        if(currentConversationId) loadConversation(currentConversationId);
    }, 5000);

    // Search functionality
    document.getElementById('searchConversations')?.addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        const conversations = document.querySelectorAll('#conversationsList > div');
        conversations.forEach(conv => {
            const text = conv.innerText.toLowerCase();
            conv.style.display = text.includes(search) ? 'block' : 'none';
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/customer/inbox.blade.php ENDPATH**/ ?>