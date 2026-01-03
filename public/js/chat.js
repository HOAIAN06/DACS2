// Chat System - Customer Side
class ChatSystem {
    constructor() {
        this.conversationId = null;
        this.userId = null;
        this.isOpen = false;
        this.pollingInterval = null;
        this.isLoading = false;
        this.baseUrl = window.location.origin;
        
        this.initElements();
        this.attachListeners();
        this.restoreState();
    }

    initElements() {
        this.fab = document.getElementById('chatFab');
        this.popup = document.getElementById('chatPopup');
        this.closeBtn = document.getElementById('chatClose');
        this.body = document.getElementById('chatBody');
        this.form = document.getElementById('chatForm');
        this.input = document.getElementById('chatInput');
        this.badge = document.getElementById('chatBadge');
        this.headerStatus = document.getElementById('headerStatus');
        this.imageInput = document.getElementById('chatImageInput');
        this.imagePreview = document.getElementById('chatImagePreview');
        this.selectedImages = [];
        this.userId = parseInt(this.popup?.dataset.userId || '0') || null;
        this.lightbox = document.getElementById('chatImageLightbox');
        this.lightboxImage = document.getElementById('chatLightboxImage');
        this.lightboxClose = document.getElementById('chatLightboxClose');
    }

    attachListeners() {
        this.fab?.addEventListener('click', () => this.toggle());
        this.closeBtn?.addEventListener('click', () => this.close());
        this.form?.addEventListener('submit', (e) => this.handleSendMessage(e));
        this.input?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.form?.dispatchEvent(new Event('submit'));
            }
        });
        this.imageInput?.addEventListener('change', (e) => this.handleImageSelect(e));

        // Lightbox: click image to open
        this.body?.addEventListener('click', (e) => {
            const target = e.target;
            if (target && target.classList?.contains('chat-message-image')) {
                this.openLightbox(target.getAttribute('src'));
            }
        });

        // Close lightbox
        this.lightbox?.addEventListener('click', (e) => {
            if (e.target === this.lightbox || e.target === this.lightboxClose) {
                this.closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeLightbox();
            }
        });
    }

    restoreState() {
        // Restore conversation ID from localStorage
        const savedId = localStorage.getItem('hanzo_conversation_id');
        const savedUser = localStorage.getItem('hanzo_conversation_user');

        if (savedId && savedUser && parseInt(savedUser) === this.userId) {
            this.conversationId = parseInt(savedId);
            this.loadMessages();
            this.startPolling();
        } else if (savedId || savedUser) {
            this.resetConversationState();
        }
    }

    async initConversation() {
        if (this.conversationId) return;
        
        try {
            const csrfToken = document.querySelector('[name="_token"]')?.value;
            const response = await fetch(`${this.baseUrl}/chat/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) throw new Error('Failed to start conversation');
            
            const data = await response.json();
            this.conversationId = data.conversation.id;
            localStorage.setItem('hanzo_conversation_id', this.conversationId);
            if (this.userId) {
                localStorage.setItem('hanzo_conversation_user', this.userId);
            }
            
            this.clearMessages();
            this.startPolling();
        } catch (error) {
            console.error('Error starting conversation:', error);
            this.showError('Không thể kết nối. Vui lòng thử lại.');
        }
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.isOpen = true;
        this.popup?.classList.add('active');
        this.fab?.classList.add('active');
        
        if (!this.conversationId) {
            this.initConversation();
        } else {
            this.loadMessages();
            this.startPolling();
        }
    }

    close() {
        this.isOpen = false;
        this.popup?.classList.remove('active');
        this.fab?.classList.remove('active');
        this.stopPolling();
    }

    async handleSendMessage(e) {
        e.preventDefault();
        
        const message = this.input?.value.trim();
        
        // Kiểm tra có tin nhắn hoặc ảnh
        if (!message && this.selectedImages.length === 0) return;

        if (!this.conversationId) {
            await this.initConversation();
        }

        this.isLoading = true;
        this.input.disabled = true;
        const btnSubmit = this.form?.querySelector('button[type="submit"]');
        if (btnSubmit) btnSubmit.disabled = true;

        try {
            const formData = new FormData();
            formData.append('message', message);
            
            // Thêm ảnh vào FormData
            this.selectedImages.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });
            
            formData.append('_token', document.querySelector('[name="_token"]')?.value || '');

            const response = await fetch(`${this.baseUrl}/chat/${this.conversationId}/message`, {
                method: 'POST',
                body: formData
            });

            if (response.status === 401 || response.status === 403) {
                this.handleUnauthorized();
                throw new Error('Unauthorized');
            }

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();
            this.input.value = '';
            this.selectedImages = [];
            this.imagePreview.innerHTML = '';
            this.imageInput.value = '';
            
            this.addMessage(data.message, 'customer');
            this.scrollToBottom();
            
            // Mark messages as read
            this.markAsRead();
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Gửi tin nhắn thất bại. Vui lòng thử lại.');
        } finally {
            this.isLoading = false;
            if (this.input) this.input.disabled = false;
            if (btnSubmit) btnSubmit.disabled = false;
        }
    }

    handleImageSelect(e) {
        const files = Array.from(e.target.files || []);
        
        files.forEach(file => {
            // Kiểm tra file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                this.showError(`${file.name} quá lớn. Max 5MB`);
                return;
            }
            
            // Kiểm tra file type
            if (!file.type.startsWith('image/')) {
                this.showError(`${file.name} không phải ảnh`);
                return;
            }
            
            this.selectedImages.push(file);
            this.showImagePreview(file);
        });
        
        // Reset input
        this.imageInput.value = '';
    }

    showImagePreview(file) {
        const reader = new FileReader();
        
        reader.onload = (e) => {
            const index = this.selectedImages.length - 1;
            const previewItem = document.createElement('div');
            previewItem.className = 'chat-image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="preview">
                <button type="button" class="chat-image-preview-remove" data-index="${index}">&times;</button>
            `;
            
            this.imagePreview?.appendChild(previewItem);
            
            previewItem.querySelector('button')?.addEventListener('click', (e) => {
                e.preventDefault();
                const idx = parseInt(e.target.dataset.index);
                this.selectedImages.splice(idx, 1);
                previewItem.remove();
            });
        };
        
        reader.readAsDataURL(file);
    }

    async loadMessages() {
        if (!this.conversationId) return;

        try {
            const response = await fetch(`${this.baseUrl}/chat/${this.conversationId}/messages`);
            if (response.status === 401 || response.status === 403) {
                this.handleUnauthorized();
                return;
            }

            if (!response.ok) throw new Error('Failed to load messages');

            const data = await response.json();
            this.clearMessages();

            if (data.messages.length === 0) {
                this.showEmpty();
            } else {
                data.messages.forEach(msg => {
                    this.addMessage(msg, msg.sender_type);
                });
                this.scrollToBottom();
            }

            // Update badge
            this.updateBadge(data.unread_count);
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    async startPolling() {
        if (this.pollingInterval) return;

        // Poll every 5 seconds
        this.pollingInterval = setInterval(() => {
            if (this.isOpen && this.conversationId) {
                this.loadMessages();
            }
        }, 5000);
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    async markAsRead() {
        if (!this.conversationId) return;

        try {
            const csrfToken = document.querySelector('[name="_token"]')?.value;
            await fetch(`${this.baseUrl}/chat/${this.conversationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    resetConversationState() {
        localStorage.removeItem('hanzo_conversation_id');
        localStorage.removeItem('hanzo_conversation_user');
        this.conversationId = null;
        this.clearMessages();
        this.showEmpty();
    }

    handleUnauthorized() {
        this.resetConversationState();
        this.showError('Phiên chat đã hết hạn. Đang tạo cuộc trò chuyện mới...');
        this.initConversation();
    }

    addMessage(message, type) {
        if (!this.body) return;

        // Remove empty state if exists
        this.body.querySelector('.chat-empty')?.remove();

        const messageEl = document.createElement('div');
        messageEl.className = `chat-message ${type}`;
        
        const senderName = message.sender?.name || (type === 'customer' ? 'Bạn' : 'Shop');
        const initials = senderName.charAt(0).toUpperCase();
        
        // Normalize images to array
        let imagesArr = [];
        if (Array.isArray(message.images)) {
            imagesArr = message.images;
        } else if (message.images && typeof message.images === 'string') {
            try {
                const parsed = JSON.parse(message.images);
                imagesArr = Array.isArray(parsed) ? parsed : Object.values(parsed || {});
            } catch (e) {
                imagesArr = [message.images];
            }
        } else if (message.images && typeof message.images === 'object') {
            imagesArr = Object.values(message.images);
        }
        
        let imageHTML = '';
        if (imagesArr.length > 0) {
            imageHTML = '<div class="chat-message-images">';
            imagesArr.forEach(img => {
                imageHTML += `<img src="${img}" alt="chat image" class="chat-message-image">`;
            });
            imageHTML += '</div>';
        }
        
        messageEl.innerHTML = `
            <div class="chat-message-avatar">${initials}</div>
            <div class="chat-message-content">
                ${message.message ? `<div class="chat-message-bubble">${this.escapeHtml(message.message)}</div>` : ''}
                ${imageHTML}
                <div class="chat-message-time">${this.formatTime(message.created_at)}</div>
            </div>
        `;

        this.body.appendChild(messageEl);
    }

    clearMessages() {
        if (!this.body) return;
        this.body.innerHTML = '';
    }

    showEmpty() {
        if (!this.body) return;
        this.body.innerHTML = `
            <div class="chat-empty">
                <div class="chat-empty-icon">💬</div>
                <p>Chào bạn! 👋<br>Hãy bắt đầu cuộc trò chuyện với chúng tôi</p>
            </div>
        `;
    }

    openLightbox(src) {
        if (!src || !this.lightbox || !this.lightboxImage) return;
        this.lightboxImage.src = src;
        this.lightbox.classList.add('active');
        document.body.classList.add('chat-lightbox-open');
    }

    closeLightbox() {
        if (!this.lightbox) return;
        this.lightbox.classList.remove('active');
        document.body.classList.remove('chat-lightbox-open');
    }

    showError(message) {
        alert(message);
    }

    scrollToBottom() {
        if (this.body) {
            setTimeout(() => {
                this.body.scrollTop = this.body.scrollHeight;
            }, 0);
        }
    }

    updateBadge(count) {
        if (count > 0) {
            this.badge.textContent = count > 99 ? '99+' : count;
            this.badge.style.display = 'flex';
        } else {
            this.badge.style.display = 'none';
        }
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        
        const isToday = date.toDateString() === now.toDateString();
        if (isToday) {
            return date.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit'
            });
        } else {
            return date.toLocaleDateString('vi-VN', { 
                month: 'short', 
                day: 'numeric'
            });
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.chatSystem = new ChatSystem();
});


// ========================================
// ADMIN CHAT PAGE JAVASCRIPT
// ========================================

class AdminChatManager {
    constructor() {
        this.conversationItems = document.querySelectorAll('.conversation-item');
        this.chatArea = document.getElementById('chatArea');
        this.infoPanel = document.getElementById('infoPanel');
        this.filterBtns = document.querySelectorAll('.filter-btn');
        this.currentConversationId = null;
        this.currentConversationUserName = '';
        this.selectedImages = [];
        
        this.init();
        this.setupImageHandler();
    }

    init() {
        this.attachConversationListeners();
        this.attachFilterListeners();
    }

    setupImageHandler() {
        // Will be set up after form is rendered in renderChatArea
    }

    attachImageListener() {
        const imageInput = document.getElementById('adminImageInput');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => this.handleImageSelect(e));
        }
    }

    handleImageSelect(e) {
        const files = Array.from(e.target.files || []);
        const preview = document.getElementById('imagePreview');
        
        files.forEach((file, idx) => {
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} quá lớn. Max 5MB`);
                return;
            }
            
            // Check file type
            if (!file.type.startsWith('image/')) {
                alert(`${file.name} không phải ảnh`);
                return;
            }
            
            this.selectedImages.push(file);
            
            const reader = new FileReader();
            reader.onload = (event) => {
                const previewItem = document.createElement('div');
                previewItem.style.cssText = 'position: relative; display: inline-block; margin-right: 8px;';
                previewItem.innerHTML = `
                    <img src="${event.target.result}" alt="preview" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    <button type="button" style="position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; background: red; color: white; border: none; border-radius: 50%; cursor: pointer; font-size: 12px; padding: 0; line-height: 1;" onclick="event.preventDefault(); event.stopPropagation();">×</button>
                `;
                
                previewItem.querySelector('button').addEventListener('click', (e) => {
                    e.preventDefault();
                    this.selectedImages.splice(this.selectedImages.indexOf(file), 1);
                    previewItem.remove();
                });
                
                if (preview) preview.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset input
        e.target.value = '';
    }

    attachConversationListeners() {
        const list = document.getElementById('conversationsList');

        // Event delegation to handle future DOM updates
        list?.addEventListener('click', (e) => {
            const item = e.target.closest('.conversation-item');
            if (!item) return;

            const conversationId = item.dataset.id;

            // Remove active class from all and add to current
            document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            // Load conversation details
            this.loadConversation(conversationId);
        });

        // Auto-load first conversation if available
        const firstItem = document.querySelector('.conversation-item');
        if (firstItem) {
            firstItem.classList.add('active');
            this.loadConversation(firstItem.dataset.id);
        }
    }

    attachFilterListeners() {
        this.filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.filterBtns.forEach(b => {
                    b.style.background = 'transparent';
                    b.style.color = 'white';
                    b.classList.remove('active');
                });
                btn.style.background = 'white';
                btn.style.color = '#667eea';
                btn.classList.add('active');
            });
        });
    }

    async loadConversation(id) {
        this.currentConversationId = id;
        const chatArea = document.getElementById('chatArea');
        if (chatArea) {
            chatArea.innerHTML = '<div style="padding: 24px; text-align: center; color: #6b7280;">Đang tải cuộc trò chuyện...</div>';
        }
        
        try {
            const response = await fetch(`/admin/chat/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Tải thất bại (${response.status})`);
            }

            let data;
            try {
                data = await response.json();
            } catch (parseErr) {
                const raw = await response.text();
                throw new Error('Phản hồi không phải JSON: ' + raw.slice(0, 200));
            }
            
            // Use global render functions
            if (typeof window.renderChatArea === 'function') {
                window.renderChatArea(data.conversation, data.messages);
            }
            if (typeof window.renderInfoPanel === 'function') {
                window.renderInfoPanel(data.conversation);
            }

            this.currentConversationUserName = data.conversation?.user?.name || '';
            this.selectedImages = [];
            
            // Update conversationIdInput
            const conversationIdInput = document.getElementById('conversationIdInput');
            if (conversationIdInput) {
                conversationIdInput.value = data.conversation.id;
            }
            const preview = document.getElementById('imagePreview');
            if (preview) preview.innerHTML = '';
            const messageInput = document.getElementById('messageInput');
            if (messageInput) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }
        } catch (error) {
            console.error('Error:', error);
            if (chatArea) {
                chatArea.innerHTML = `<div style="padding: 24px; text-align: center; color: #ef4444;">Không thể tải cuộc trò chuyện.<br><small>${error.message}</small></div>`;
            }
        }
    }

    async sendMessage(event, conversationId) {
        event?.preventDefault();

        const messageInput = document.getElementById('messageInput');
        const message = messageInput?.value.trim() || '';

        if (!message && this.selectedImages.length === 0) return;

        const formData = new FormData();
        formData.append('message', message);

        this.selectedImages.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]')?.value;
        if (csrf) formData.append('_token', csrf);

        try {
            const response = await fetch(`/admin/chat/${conversationId}/message`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf || ''
                }
            });

            if (!response.ok) {
                let detail = '';
                try {
                    const errJson = await response.json();
                    detail = errJson.message || errJson.error || JSON.stringify(errJson);
                } catch (_) {
                    detail = await response.text();
                }
                throw new Error(`Gửi thất bại (${response.status}): ${detail}`);
            }

            const data = await response.json();

            if (messageInput) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }

            const preview = document.getElementById('imagePreview');
            if (preview) preview.innerHTML = '';

            const imageInput = document.getElementById('adminImageInput');
            if (imageInput) imageInput.value = '';

            this.selectedImages = [];

            if (data?.message) {
                this.appendMessage(data.message);
            } else {
                this.loadConversation(conversationId);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gửi tin nhắn thất bại. Vui lòng thử lại.');
        }
    }

    appendMessage(message) {
        const container = document.getElementById('messagesContainer');
        if (!container || typeof window.renderMessage !== 'function') return;

        const html = window.renderMessage(message, this.currentConversationUserName || 'Khách');
        container.insertAdjacentHTML('beforeend', html);
        container.scrollTop = container.scrollHeight;
    }

    async handleAction(action, conversationId) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]')?.value;
        const headers = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf || ''
        };

        let url = '';
        let method = 'POST';

        if (action === 'pin') {
            url = `/admin/chat/${conversationId}/pin`;
        } else if (action === 'delete') {
            if (!confirm('Bạn có chắc muốn xóa cuộc trò chuyện này?')) return;
            url = `/admin/chat/${conversationId}/delete`;
            method = 'DELETE';
        } else {
            return;
        }

        try {
            const response = await fetch(url, { method, headers });
            if (!response.ok) throw new Error('Action failed');

            const data = await response.json();

            if (action === 'delete') {
                const item = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
                item?.remove();
                this.chatArea.innerHTML = '';
                this.infoPanel.innerHTML = '';
            } else if (action === 'pin') {
                // Reload conversation list to reorder pinned conversations to top
                await this.reloadConversationList();
                this.loadConversation(conversationId);
            }
        } catch (error) {
            console.error('Action error:', error);
            alert('Không thể thực hiện hành động. Vui lòng thử lại.');
        }
    }

    async reloadConversationList() {
        try {
            const response = await fetch('/admin/chat', {
                headers: {
                    'Accept': 'text/html'
                }
            });
            
            if (!response.ok) throw new Error('Failed to reload list');
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newList = doc.querySelector('#conversationsList');
            
            if (newList) {
                const currentList = document.getElementById('conversationsList');
                currentList.innerHTML = newList.innerHTML;
                this.attachConversationListeners();
            }
        } catch (error) {
            console.error('Error reloading conversation list:', error);
        }
    }
}

// Initialize Admin Chat Manager when DOM is ready
if (document.querySelector('.admin-chat')) {
    document.addEventListener('DOMContentLoaded', () => {
        window.adminChatManager = new AdminChatManager();
    });
}
