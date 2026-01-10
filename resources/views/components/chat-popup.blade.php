<!-- Chat Floating Button & Popup -->
@php($chatUser = auth()->user())
@if($chatUser && !$chatUser->is_admin)
    <button type="button" class="chat-fab" id="chatFab" title="Chat với shop">
        <img src="{{ asset('icons/message.png') }}" alt="Chat" style="width: 28px; height: 28px;">
        <span class="badge" id="chatBadge" style="display: none;">0</span>
    </button>

    <div class="chat-popup" id="chatPopup" data-user-id="{{ $chatUser->id }}">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-header-avatar">
                    <img src="{{ asset('icons/shop.png') }}" alt="Support" style="width: 32px; height: 32px;">
                </div>
                <div class="chat-header-title">
                    <h3>HANZO Support</h3>
                    <p id="headerStatus">Đang hoạt động</p>
                </div>
            </div>
            <button type="button" class="chat-header-close" id="chatClose">&times;</button>
        </div>

        <!-- Body - Messages -->
        <div class="chat-body" id="chatBody">
            <div class="chat-empty">
                <div class="chat-empty-icon">
                    <img src="{{ asset('icons/message.png') }}" alt="Chat" style="width: 48px; height: 48px; opacity: 0.3;">
                </div>
                <p>Chào bạn! 👋<br>Hãy bắt đầu cuộc trò chuyện với chúng tôi</p>
            </div>
        </div>

        <!-- Footer - Input -->
        <div class="chat-footer">
            <form class="chat-input-group" id="chatForm">
                @csrf
                <input 
                    type="text" 
                    id="chatInput" 
                    placeholder="Nhập tin nhắn..."
                    autocomplete="off"
                    maxlength="2000"
                >
                <label for="chatImageInput" class="chat-file-btn" title="Gửi ảnh">
                    <img src="{{ asset('icons/image.png') }}" alt="Image" style="width: 20px; height: 20px;">
                </label>
                <input 
                    type="file" 
                    id="chatImageInput" 
                    accept="image/*" 
                    style="display: none;"
                >
                <button type="submit" title="Gửi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.99 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.01298017 C3.50612381,0.9 2.40987169,0.9 1.77946707,1.3748544 C0.994623095,2.00636533 0.837654326,3.0943639 1.15159189,3.88282327 L3.03521743,10.3237711 C3.03521743,10.4808686 3.34915502,10.637966 3.50612381,10.637966 L16.6915026,11.4234529 C16.6915026,11.4234529 17.1624089,11.4234529 17.1624089,11.8983073 C17.1624089,12.3731618 16.6915026,12.4744748 16.6915026,12.4744748 Z" fill="currentColor"/>
                    </svg>
                </button>
            </form>
            <div id="chatImagePreview" class="chat-image-preview"></div>
        </div>
    </div>

    <!-- Lightbox for viewing images -->
    <div class="chat-lightbox" id="chatImageLightbox">
        <button type="button" class="chat-lightbox-close" id="chatLightboxClose" aria-label="Đóng">&times;</button>
        <img id="chatLightboxImage" src="" alt="Xem ảnh" />
    </div>
@endif

<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
<script src="{{ asset('js/chat.js') }}"></script>
