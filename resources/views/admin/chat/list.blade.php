@extends('layouts.admin')

@section('content')
<style>
/* Professional Chat UI - Realistic Design */
.admin-chat {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

.conversation-item {
    transition: all 0.2s ease;
}

.conversation-item:hover {
    background: #f9fafb !important;
}

.conversation-item.active {
    background: #f3f4f6 !important;
    border-left: 3px solid #2563EB !important;
}

.conversation-avatar {
    position: relative;
    flex-shrink: 0;
}

.conversation-avatar-inner {
    width: 44px;
    height: 44px;
    background: #2563EB;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.conversation-status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    background: #10b981;
    border: 2px solid white;
    border-radius: 50%;
}

.conversation-name {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
}

.conversation-name.unread {
    font-weight: 600;
}

.conversation-badge {
    background: #dc2626;
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
}

.conversation-preview {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-meta {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 4px;
    font-size: 12px;
}

.conversation-time {
    color: #9ca3af;
}

.conversation-status {
    font-size: 11px;
    font-weight: 500;
}

.status-open {
    color: #10b981;
}

.status-handled {
    color: #6b7280;
}

.message-item {
    margin-bottom: 8px;
    display: flex;
    animation: slideIn 0.2s ease;
}

@@keyframes slideIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-item.admin {
    justify-content: flex-end;
}

.message-bubble {
    max-width: 70%;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.message-item.customer .message-bubble {
    background: #f3f4f6;
    color: #1f2937;
}

.message-item.admin .message-bubble {
    background: #2563EB;
    color: white;
}

#imagePreview {
    display: flex;
    gap: 8px;
    padding: 8px 0;
    flex-wrap: wrap;
}
}

.image-preview-item {
    position: relative;
    width: 80px;
    height: 80px;
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

@@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<div class="admin-chat container-fluid" style="display: flex; height: calc(100vh - 120px); gap: 0; padding: 0; margin: 0; background: #ffffff;">
    <!-- LEFT: Conversations List -->
    <div style="width: 320px; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; background: white;">
        <!-- Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; background: #111827;">
            <h3 class="fw-bold mb-0" style="font-size: 16px; color: white; text-align: center; letter-spacing: -0.5px;">
                Đoạn Chat
            </h3>
        </div>

        <!-- Conversations List -->
        <div id="conversationsList" style="flex: 1; overflow-y: auto; padding: 0; background: #f9fafb;">
            @if ($conversations->isEmpty())
                <div style="padding: 40px 20px; text-align: center;">
                    <img src="{{ asset('icons/message.png') }}" alt="No messages" style="width: 64px; height: 64px; opacity: 0.3; margin-bottom: 12px;">
                    <p class="text-muted" style="font-size: 13px;">Không có cuộc trò chuyện</p>
                </div>
            @else
                @foreach ($conversations as $conversation)
                    @php
                        $isOpen = $conversation->status === 'open';
                        $unread = $conversation->unread_count > 0;
                    @endphp
                    <div class="conversation-item" data-id="{{ $conversation->id }}" style="
                        padding: 12px 16px;
                        border-bottom: 1px solid #e5e7eb;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        background: white;
                        position: relative;
                    ">
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <div class="conversation-avatar" style="margin-top: 2px;">
                                <div class="conversation-avatar-inner">
                                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                                </div>
                                @if($isOpen)
                                    <div class="conversation-status-dot"></div>
                                @endif
                            </div>

                            <div class="conversation-content" style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                    <div class="conversation-name {{ $unread ? 'unread' : '' }}">{{ $conversation->user->name }}</div>
                                    @if ($unread)
                                        <span class="conversation-badge">{{ $conversation->unread_count }}</span>
                                    @endif
                                </div>

                                <div class="conversation-preview">{{ Str::limit($conversation->last_message ?? 'Bắt đầu cuộc trò chuyện...', 30) }}</div>
                                
                                @if($conversation->is_pinned)
                                    <div style="font-size: 11px; color: #f59e0b; font-weight: 600; margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                                        <img src="{{ asset('icons/pin.png') }}" alt="Pinned" style="width: 12px; height: 12px;">
                                        Đã ghim
                                    </div>
                                @endif

                                <div class="conversation-meta">
                                    <span class="conversation-time">{{ $conversation->last_message_at?->diffForHumans() ?? 'Vừa xong' }}</span>
                                    <span class="conversation-status {{ $isOpen ? 'status-open' : 'status-handled' }}">
                                        @if($isOpen)
                                            <img src="{{ asset('icons/green.png') }}" alt="Active" style="width: 10px; height: 10px; display: inline-block; vertical-align: middle;">
                                        @else
                                            <img src="{{ asset('icons/hoanthanh.png') }}" alt="Resolved" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle;">
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- MIDDLE: Chat Messages Area -->
    <div id="chatArea" style="flex: 1; display: flex; flex-direction: column; background: #ffffff; border-right: 1px solid #e5e7eb;">
        <!-- Empty State -->
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; background: #f9fafb;">
            <div style="text-align: center;">
                <img src="{{ asset('icons/chat.png') }}" alt="Chat" style="width: 80px; height: 80px; opacity: 0.2; margin-bottom: 16px;">
                <h5 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0;">Chọn một cuộc trò chuyện để bắt đầu</h5>
                <p style="font-size: 12px; color: #9ca3af; margin-top: 6px; margin-bottom: 0;">Nhấp vào khách hàng bên trái để xem tin nhắn</p>
            </div>
        </div>
    </div>

    <!-- RIGHT: Customer Info Panel -->
    <div id="infoPanel" style="width: 300px; background: #f9fafb; border-left: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div style="text-align: center;">
            <img src="{{ asset('icons/user.png') }}" alt="User" style="width: 64px; height: 64px; opacity: 0.3; margin-bottom: 12px;">
            <h6 style="font-size: 13px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Thông tin khách hàng</h6>
            <p style="font-size: 12px; color: #9ca3af; margin: 0;">Chọn cuộc trò chuyện để xem chi tiết</p>
        </div>
    </div>
</div>

<input type="hidden" id="conversationIdInput" value="">

<script src="{{ asset('js/admin-chat-render.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
@endsection

