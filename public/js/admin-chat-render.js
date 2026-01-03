// Helper functions for rendering admin chat UI (Messenger style)

function renderChatArea(conversation, messages) {
    const status = conversation.status;
    const isActive = true; // Closing feature removed; always allow interaction
    const chatArea = document.getElementById('chatArea');
    
    chatArea.innerHTML = `
        <!-- Chat Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; background: white; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="
                    width: 42px;
                    height: 42px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 700;
                    font-size: 16px;
                    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
                    position: relative;
                ">
                    ${conversation.user.name.charAt(0).toUpperCase()}
                    ${isActive ? '<div style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background: #10b981; border: 2px solid white; border-radius: 50%;"></div>' : ''}
                </div>
                <div>
                    <h4 class="mb-0" style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 2px;">${conversation.user.name}</h4>
                    <small style="font-size: 11px; color: ${isActive ? '#10b981' : '#6b7280'}; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                        ${isActive ? '<img src="/icons/green.png" alt="Active" style="width: 10px; height: 10px;"> Đang hoạt động' : 'Không hoạt động'}
                    </small>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 20px 24px; display: flex; flex-direction: column; gap: 12px; background: #ffffff;">
            ${messages.length === 0 
                ? '<div style="flex: 1; display: flex; align-items: center; justify-content: center;"><div style="text-align: center;"><img src="/icons/message.png" alt="No messages" style="width: 64px; height: 64px; opacity: 0.2; margin-bottom: 12px;"><p style="color: #9ca3af; font-size: 13px;">Bắt đầu cuộc trò chuyện với ' + conversation.user.name + '</p></div></div>' 
                : messages.map(msg => renderMessage(msg, conversation.user.name)).join('')
            }
        </div>
        
        <!-- Input Area (always enabled, closing disabled) -->
        <div id="chatInputArea" style="padding: 16px 20px; background: white; border-top: 1px solid #e5e7eb;">
            <form id="chatForm" onsubmit="window.adminChatManager.sendMessage(event, ${conversation.id})">
                <div id="imagePreview" style="margin-bottom: 10px;"></div>
                
                <div style="display: flex; align-items: flex-end; gap: 8px; background: white; border-radius: 8px; padding: 8px 12px; border: 1px solid #d1d5db; transition: all 0.2s;">
                    <input type="file" id="adminImageInput" accept="image/*" multiple style="display: none;">
                    
                    <button type="button" onclick="document.getElementById('adminImageInput').click()" style="width: 32px; height: 32px; border-radius: 4px; border: none; background: transparent; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; padding: 0;">
                        <img src="/icons/image.png" alt="Image" style="width: 20px; height: 20px;">
                    </button>
                    
                    <textarea id="messageInput" placeholder="Nhập tin nhân hỗ trợ khách hàng..." style="flex: 1; border: none; background: transparent; padding: 6px 8px; font-size: 14px; resize: none; max-height: 120px; font-family: inherit; line-height: 1.4; color: #111827;" rows="1"></textarea>
                    
                    <button type="submit" style="width: 32px; height: 32px; border-radius: 4px; border: none; background: #2563EB; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; transition: all 0.2s;">
                        ➤
                    </button>
                </div>
            </form>
        </div>
        <input type="hidden" id="conversationIdInput" value="${conversation.id}">
        
        <!-- Image Lightbox Modal -->
        <div id="imageLightbox" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center;" onclick="window.closeLightbox(event)">
            <button onclick="window.closeLightbox(event)" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; border-radius: 4px; background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                ×
            </button>
            <img id="lightboxImage" src="" alt="" style="max-width: 90%; max-height: 90vh; object-fit: contain; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,0.5);" onclick="event.stopPropagation()">
        </div>
    `;
    
    setTimeout(() => {
        const container = document.getElementById('messagesContainer');
        if (container) container.scrollTop = container.scrollHeight;
        
        const textarea = document.getElementById('messageInput');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
            
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
                }
            });
        }
        
        if (window.adminChatManager) {
            window.adminChatManager.attachImageListener();
        }
    }, 100);
}

function renderMessage(msg, userName) {
    const isAdmin = msg.sender_type === 'admin';
    const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    
    // Normalize images to array to avoid "forEach is not a function"
    let imagesArr = [];
    if (Array.isArray(msg.images)) {
        imagesArr = msg.images;
    } else if (msg.images && typeof msg.images === 'string') {
        try {
            const parsed = JSON.parse(msg.images);
            imagesArr = Array.isArray(parsed) ? parsed : Object.values(parsed || {});
        } catch (e) {
            imagesArr = [msg.images];
        }
    } else if (msg.images && typeof msg.images === 'object') {
        imagesArr = Object.values(msg.images);
    }

    const flexDir = isAdmin ? 'justify-content: flex-end;' : 'justify-content: flex-start;';
    const bgColor = isAdmin ? '#2563EB' : '#f3f4f6';
    const textColor = isAdmin ? '#ffffff' : '#050505';
    
    let html = '<div class="message-item ' + (isAdmin ? 'admin' : 'customer') + '" style="margin-bottom: 8px; display: flex; ' + flexDir + '">';
    html += '<div style="max-width: 70%; display: flex; flex-direction: column; gap: 4px;">';
    
    // Text bubble (if exists)
    if (msg.message && msg.message.trim()) {
        html += '<div class="message-bubble" style="padding: 8px 12px; border-radius: 10px; font-size: 14px; line-height: 1.35; word-wrap: break-word; word-break: break-word; white-space: pre-wrap; background: ' + bgColor + '; color: ' + textColor + '; display: inline-block; max-width: 100%;">';
        html += msg.message;
        html += '</div>';
    }
    
    // Images (if exist) - separate from text like Messenger
    if (imagesArr.length > 0) {
        const imgCount = imagesArr.length;
        
        if (imgCount === 1) {
            // Single image - full size with rounded corners
            html += '<div style="max-width: 280px;">';
            html += '<img src="' + imagesArr[0] + '" alt="image" onclick="window.openLightbox(\'' + imagesArr[0] + '\')" style="width: 100%; max-height: 350px; border-radius: 18px; display: block; object-fit: cover; cursor: pointer;">';
            html += '</div>';
        } else if (imgCount === 2) {
            // 2 images - side by side grid
            html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px; max-width: 280px; border-radius: 18px; overflow: hidden;">';
            imagesArr.forEach(img => {
                html += '<img src="' + img + '" alt="image" onclick="window.openLightbox(\'' + img + '\')" style="width: 100%; height: 180px; object-fit: cover; display: block; cursor: pointer;">';
            });
            html += '</div>';
        } else if (imgCount === 3) {
            // 3 images - 1 large + 2 stacked
            html += '<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2px; max-width: 280px; border-radius: 18px; overflow: hidden;">';
            html += '<img src="' + imagesArr[0] + '" alt="image" onclick="window.openLightbox(\'' + imagesArr[0] + '\')" style="grid-row: 1 / 3; width: 100%; height: 100%; object-fit: cover; display: block; cursor: pointer;">';
            html += '<img src="' + imagesArr[1] + '" alt="image" onclick="window.openLightbox(\'' + imagesArr[1] + '\')" style="width: 100%; height: 100%; object-fit: cover; display: block; cursor: pointer;">';
            html += '<img src="' + imagesArr[2] + '" alt="image" onclick="window.openLightbox(\'' + imagesArr[2] + '\')" style="width: 100%; height: 100%; object-fit: cover; display: block; cursor: pointer;">';
            html += '</div>';
        } else {
            // 4+ images - 2x2 grid (max 4 shown)
            html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px; max-width: 280px; border-radius: 18px; overflow: hidden;">';
            imagesArr.slice(0, 4).forEach((img, idx) => {
                html += '<div style="position: relative;">';
                html += '<img src="' + img + '" alt="image" onclick="window.openLightbox(\'' + img + '\')" style="width: 100%; height: 140px; object-fit: cover; display: block; cursor: pointer;">';
                if (idx === 3 && imagesArr.length > 4) {
                    html += '<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600; pointer-events: none;">+' + (imagesArr.length - 4) + '</div>';
                }
                html += '</div>';
            });
            html += '</div>';
        }
    }
    
    // Timestamp
    html += '<div class="message-time" style="font-size: 12px; color: #9ca3af; margin-top: 2px; padding: 0 8px; text-align: ' + (isAdmin ? 'right' : 'left') + ';">';
    html += time;
    html += '</div>';
    
    html += '</div>';
    html += '</div>';
    
    return html;
}

function renderInfoPanel(conversation) {
    const infoPanel = document.getElementById('infoPanel');
    const user = conversation.user;
    const status = conversation.status;
    const statusLabel = status === 'resolved' ? '✅ Đã xử lý' : '🟢 Đang mở';
    const statusBg = status === 'resolved' ? '#eff6ff' : '#f0fdf4';
    const statusColor = status === 'resolved' ? '#1e40af' : '#166534';
    
    infoPanel.innerHTML = `
        <div style="width: 100%; padding: 20px;">
            <!-- User Avatar & Name -->
            <div style="text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                <div style="
                    width: 64px;
                    height: 64px;
                    background: #2563EB;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 600;
                    font-size: 24px;
                    margin: 0 auto 12px;
                    position: relative;
                ">
                    ${user.name.charAt(0).toUpperCase()}
                    <div style="position: absolute; bottom: 0; right: 0; width: 14px; height: 14px; background: #10b981; border: 2px solid white; border-radius: 50%;"></div>
                </div>
                <h6 class="mb-1" style="font-weight: 600; font-size: 14px; color: #111827;">${user.name}</h6>
                <small style="font-size: 12px; color: #6b7280; display: flex; align-items: center; justify-content: center; gap: 4px;">
                    <img src="/icons/email.png" alt="Email" style="width: 12px; height: 12px;">
                    ${user.email}
                </small>
            </div>
            
            <!-- Actions -->
            <div>
                <h6 style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    <img src="/icons/thunder.png" alt="Actions" style="width: 14px; height: 14px;">
                    Hành động
                </h6>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <button class="btn w-100" style="background: #f59e0b; color: white; border: none; border-radius: 6px; padding: 10px 16px; font-size: 13px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px;" onclick="window.adminChatManager.handleAction('pin', ${conversation.id})">
                        <img src="/icons/pin.png" alt="Pin" style="width: 14px; height: 14px; filter: brightness(0) invert(1);">
                        ${conversation.is_pinned ? 'Bỏ ghim' : 'Ghim cuộc trò chuyện'}
                    </button>
                    <button class="btn w-100" style="background: #dc2626; color: white; border: none; border-radius: 6px; padding: 10px 16px; font-size: 13px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px;" onclick="window.adminChatManager.handleAction('delete', ${conversation.id})">
                        <img src="/icons/delete.png" alt="Delete" style="width: 14px; height: 14px; filter: brightness(0) invert(1);">
                        Xóa hội thoại
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Lightbox functions
window.openLightbox = function(imageSrc) {
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    if (lightbox && lightboxImage) {
        lightboxImage.src = imageSrc;
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
};

window.closeLightbox = function(event) {
    if (event) event.stopPropagation();
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
    }
};

// Close lightbox on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.closeLightbox();
    }
});

// Export functions
if (typeof window !== 'undefined') {
    window.renderChatArea = renderChatArea;
    window.renderMessage = renderMessage;
    window.renderInfoPanel = renderInfoPanel;
}
