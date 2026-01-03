@extends('layouts.admin')

@section('content')
<div style="display: flex; height: calc(100vh - 120px); gap: 0; margin: 0;">
    
    <!-- LEFT: Conversations List -->
    <div style="width: 360px; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; background: white;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 class="fw-bold mb-3" style="font-size: 20px;">💬 Chat</h3>
            
            <!-- Search -->
            <div style="position: relative; margin-bottom: 12px;">
                <input type="text" id="searchInput" placeholder="Tìm khách hàng..." 
                    class="form-control" style="padding-left: 12px; height: 38px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 14px;">
            </div>

            <!-- Filter Tabs -->
            <div style="display: flex; gap: 6px;">
                <button class="btn btn-sm btn-success" style="border-radius: 6px; font-size: 11px; padding: 5px 10px;">🟢 Đang mở</button>
                <button class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; font-size: 11px; padding: 5px 10px;">🔒 Đã đóng</button>
                <button class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; font-size: 11px; padding: 5px 10px;">Xóa lọc</button>
            </div>
        </div>

        <!-- Conversations List -->
        <div style="flex: 1; overflow-y: auto;">
            @php
                $allConversations = \App\Models\Conversation::with('user', 'messages')
                    ->orderByDesc('last_message_at')
                    ->limit(20)
                    ->get();
            @endphp
            
            @forelse($allConversations as $conv)
                <a href="{{ route('admin.chat.show', $conv) }}" 
                    class="list-group-item {{ $conv->id === $conversation->id ? 'active-chat' : '' }}"
                    style="
                        display: block;
                        padding: 14px 16px;
                        border: none;
                        border-bottom: 1px solid #f3f4f6;
                        text-decoration: none;
                        color: inherit;
                        background: {{ $conv->id === $conversation->id ? '#f0f4ff' : 'white' }};
                        border-left: {{ $conv->id === $conversation->id ? '3px solid #667eea' : '3px solid transparent' }};
                        cursor: pointer;
                        transition: all 0.15s ease;
                    "
                    onmouseover="if(!this.classList.contains('active-chat')) this.style.backgroundColor='#f9fafb'"
                    onmouseout="if(!this.classList.contains('active-chat')) this.style.backgroundColor='white'">
                    
                    <div style="display: flex; gap: 10px;">
                        <!-- Avatar -->
                        <div style="
                            width: 44px;
                            height: 44px;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-weight: bold;
                            flex-shrink: 0;
                            font-size: 16px;
                        ">
                            {{ strtoupper(substr($conv->user->name, 0, 1)) }}
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <!-- Name & Time -->
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2px;">
                                <div style="font-weight: 600; font-size: 13px; color: #1f2937;">
                                    {{ $conv->user->name }}
                                </div>
                                <span style="font-size: 10px; color: #9ca3af;">
                                    {{ $conv->last_message_at?->diffForHumans() ?? 'Mới' }}
                                </span>
                            </div>

                            <!-- Last Message Preview -->
                            <div style="font-size: 12px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;">
                                {{ Str::limit($conv->last_message ?? 'Bắt đầu cuộc trò chuyện', 40) }}
                            </div>

                            <!-- Status Badge -->
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="badge {{ $conv->status === 'open' ? 'bg-success' : 'bg-secondary' }}" 
                                    style="font-size: 9px; padding: 2px 6px;">
                                    {{ $conv->status === 'open' ? 'Open' : 'Closed' }}
                                </span>
                                @if($conv->unread_count > 0)
                                    <span class="badge bg-danger" style="font-size: 9px; padding: 2px 5px;">
                                        {{ $conv->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div style="padding: 40px 20px; text-align: center;">
                    <p class="text-muted" style="font-size: 13px;">Không có cuộc trò chuyện</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MIDDLE: Chat Thread Area -->
    <div style="flex: 1; display: flex; flex-direction: column; background: white; border-right: 1px solid #e5e7eb;">
        
        <!-- Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb; background: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="
                        width: 44px;
                        height: 44px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: bold;
                        font-size: 16px;
                    ">
                        {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="mb-0" style="font-size: 16px; font-weight: 600; color: #1f2937;">
                            {{ $conversation->user->name }}
                        </h4>
                        <small class="text-muted" style="font-size: 12px;">
                            {{ $conversation->user->email }}
                        </small>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <span class="badge {{ $conversation->status === 'open' ? 'bg-success' : 'bg-secondary' }}" 
                        style="padding: 6px 12px; font-size: 12px;">
                        {{ $conversation->status === 'open' ? '🟢 Đang mở' : '🔒 Đã đóng' }}
                    </span>
                    @if ($conversation->status === 'open')
                        <form action="{{ route('admin.chat.close', $conversation) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px; font-size: 12px; padding: 6px 12px;">
                                Đóng
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 12px; background: #fafbfc;">
            @forelse ($conversation->messages as $message)
                <div style="display: flex; @if ($message->sender_type === 'admin') justify-content: flex-end; @else justify-content: flex-start; @endif">
                    <div style="max-width: 65%;">
                        <!-- Sender Name & Time -->
                        <div style="display: flex; @if ($message->sender_type === 'admin') justify-content: flex-end; @endif margin-bottom: 4px; gap: 8px; align-items: center;">
                            <small style="color: {{ $message->sender_type === 'admin' ? '#667eea' : '#1f2937' }}; font-size: 12px; font-weight: 600;">
                                {{ $message->sender_type === 'admin' ? 'Admin' : $conversation->user->name }}
                            </small>
                            <small style="color: #9ca3af; font-size: 11px;">
                                {{ $message->created_at->format('H:i') }}
                            </small>
                            @if ($message->is_read && $message->sender_type === 'admin')
                                <small style="color: #10b981; font-size: 11px;">✓✓</small>
                            @endif
                        </div>

                        <!-- Message Bubble -->
                        <div style="
                            padding: 10px 14px;
                            border-radius: {{ $message->sender_type === 'admin' ? '12px 12px 2px 12px' : '12px 12px 12px 2px' }};
                            background: {{ $message->sender_type === 'admin' ? '#667eea' : '#f3f4f6' }};
                            color: {{ $message->sender_type === 'admin' ? 'white' : '#1f2937' }};
                            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
                        ">
                            @if ($message->message)
                                <p style="margin: 0; font-size: 13px; line-height: 1.5;">
                                    {{ $message->message }}
                                </p>
                            @endif

                            @if ($message->images && count($message->images) > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: {{ $message->message ? '8px' : '0' }};">
                                    @foreach ($message->images as $image)
                                        <a href="{{ $image }}" target="_blank">
                                            <img src="{{ $image }}" alt="Image" 
                                                style="max-width: 110px; max-height: 110px; border-radius: 6px; cursor: pointer;">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <div style="text-align: center;">
                        <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.3;">💭</div>
                        <p class="text-muted" style="font-size: 14px;">Chọn cuộc trò chuyện để bắt đầu hỗ trợ khách hàng</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        @if ($conversation->status === 'open')
            <div style="padding: 20px 24px; border-top: 1px solid #e5e7eb; background: white;">
                <form id="adminChatForm">
                    @csrf
                    
                    <!-- Image Preview -->
                    <div id="adminImagePreview" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;"></div>

                    <!-- Input Row -->
                    <div style="display: flex; gap: 8px; align-items: flex-end;">
                        <!-- Message Input -->
                        <textarea id="adminMessageInput" name="message" 
                            placeholder="Nhập tin nhắn..."
                            style="flex: 1; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 14px; resize: none; min-height: 40px; max-height: 120px;"
                        ></textarea>

                        <!-- File Input (hidden) -->
                        <input type="file" id="adminImageInput" accept="image/*" multiple hidden>

                        <!-- Buttons -->
                        <label style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #f3f4f6; border-radius: 8px; transition: all 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#e5e7eb'"
                            onmouseout="this.style.backgroundColor='#f3f4f6'"
                            title="Gửi ảnh">
                            <img src="{{ asset('icons/image.png') }}" alt="Image" style="width: 18px; height: 18px;">
                        </label>

                        <button type="submit" style="
                            width: 40px;
                            height: 40px;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border: none;
                            border-radius: 8px;
                            color: white;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            transition: all 0.2s ease;
                        "
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'"
                        id="adminSendBtn">
                            <img src="{{ asset('icons/gui.png') }}" alt="Send" style="width: 18px; height: 18px;">
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div style="padding: 20px 24px; background: #fef3c7; border-top: 1px solid #fcd34d; text-align: center;">
                <p class="mb-0" style="font-size: 13px; color: #92400e;">⚠️ Cuộc trò chuyện đã đóng</p>
            </div>
        @endif
    </div>

    <!-- Side Panel - Customer Info -->
    <div style="width: 320px; background: #f9fafb; border-left: 1px solid #e5e7eb; overflow-y: auto; display: flex; flex-direction: column;">
        
        <!-- User Card -->
        <div style="padding: 24px 20px; border-bottom: 1px solid #e5e7eb;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="
                    width: 60px;
                    height: 60px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    font-size: 24px;
                    margin: 0 auto 12px;
                ">
                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                </div>
                <h6 class="mb-1" style="font-weight: 600; color: #1f2937;">{{ $conversation->user->name }}</h6>
                <small class="text-muted" style="font-size: 12px;">{{ $conversation->user->email }}</small>
            </div>

            <!-- Info Items -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 4px;">
                        Điện thoại
                    </label>
                    <p style="margin: 0; font-size: 13px; color: #1f2937;">
                        {{ $conversation->user->phone ?? '—' }}
                    </p>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 4px;">
                        Ngày tạo
                    </label>
                    <p style="margin: 0; font-size: 13px; color: #1f2937;">
                        {{ $conversation->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 4px;">
                        Cập nhật
                    </label>
                    <p style="margin: 0; font-size: 13px; color: #1f2937;">
                        {{ $conversation->last_message_at?->format('d/m/Y H:i') ?? '—' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h6 style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 12px;">
                📊 Trạng thái
            </h6>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label style="font-size: 11px; color: #6b7280;">Cuộc trò chuyện</label>
                    <div style="margin-top: 4px;">
                        <span class="badge {{ $conversation->status === 'open' ? 'bg-success' : 'bg-secondary' }}" 
                            style="padding: 4px 8px; font-size: 11px;">
                            {{ $conversation->status === 'open' ? '🟢 Đang mở' : '🔒 Đã đóng' }}
                        </span>
                    </div>
                </div>

                <div>
                    <label style="font-size: 11px; color: #6b7280;">Tin nhắn chưa đọc</label>
                    <p style="margin: 4px 0 0; font-weight: 600; color: #1f2937;">{{ $conversation->unread_count ?? 0 }}</p>
                </div>

                <div>
                    <label style="font-size: 11px; color: #6b7280;">Tổng tin nhắn</label>
                    <p style="margin: 4px 0 0; font-weight: 600; color: #1f2937;">{{ count($conversation->messages) }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="padding: 20px; margin-top: auto; border-top: 1px solid #e5e7eb;">
            <h6 style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 12px;">
                Hành động
            </h6>
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <!-- Mark as Resolved -->
                <form action="{{ route('admin.chat.mark-resolved', $conversation) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100" style="
                        background: #10b981;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 8px 12px;
                        font-size: 12px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    "
                    onmouseover="this.style.backgroundColor='#059669'"
                    onmouseout="this.style.backgroundColor='#10b981'">
                        ✓ Đánh dấu đã xử lý
                    </button>
                </form>

                <!-- Close Chat -->
                @if ($conversation->status === 'open')
                    <form action="{{ route('admin.chat.close', $conversation) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm w-100" style="
                            background: #ef4444;
                            color: white;
                            border: none;
                            border-radius: 6px;
                            padding: 8px 12px;
                            font-size: 12px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        "
                        onmouseover="this.style.backgroundColor='#dc2626'"
                        onmouseout="this.style.backgroundColor='#ef4444'">
                            🔒 Đóng chat
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-sm w-100" style="
                        background: #9ca3af;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 8px 12px;
                        font-size: 12px;
                        font-weight: 500;
                        cursor: not-allowed;
                    " disabled>
                        🔒 Đóng chat
                    </button>
                @endif

                <!-- Pin Chat -->
                <button type="button" class="btn btn-sm w-100" style="
                    background: #f59e0b;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    padding: 8px 12px;
                    font-size: 12px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                "
                onmouseover="this.style.backgroundColor='#d97706'"
                onmouseout="this.style.backgroundColor='#f59e0b'"
                onclick="alert('Chức năng ghim chat sẽ được cập nhật')">
                    📌 Ghim chat
                </button>

                <!-- Delete Chat -->
                <button type="button" class="btn btn-sm w-100" style="
                    background: #8b5cf6;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    padding: 8px 12px;
                    font-size: 12px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                "
                onmouseover="this.style.backgroundColor='#7c3aed'"
                onmouseout="this.style.backgroundColor='#8b5cf6'"
                onclick="if(confirm('Bạn có chắc muốn xóa cuộc trò chuyện này?')) { alert('Chức năng xóa sẽ được cập nhật') }">
                    🗑️ Xóa chat
                </button>

                <!-- Add Notes -->
                <button type="button" class="btn btn-sm w-100" style="
                    background: #06b6d4;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    padding: 8px 12px;
                    font-size: 12px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                "
                onmouseover="this.style.backgroundColor='#0891b2'"
                onmouseout="this.style.backgroundColor='#06b6d4'"
                id="openNotesBtn">
                    📝 Ghi chú nội bộ
                </button>
            </div>
        </div>

        <!-- Notes Modal -->
        <div id="notesModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 8px; padding: 24px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <h5 style="margin-bottom: 16px; font-weight: 600;">Ghi chú nội bộ</h5>
                <textarea id="notesText" placeholder="Nhập ghi chú cho cuộc trò chuyện này..." style="
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #e5e7eb;
                    border-radius: 6px;
                    font-family: inherit;
                    font-size: 13px;
                    min-height: 120px;
                    resize: none;
                "></textarea>
                <div style="display: flex; gap: 8px; margin-top: 16px; justify-content: flex-end;">
                    <button type="button" class="btn btn-sm" style="
                        background: #e5e7eb;
                        color: #1f2937;
                        border: none;
                        border-radius: 6px;
                        padding: 8px 16px;
                        cursor: pointer;
                    " onclick="document.getElementById('notesModal').style.display='none'">
                        Hủy
                    </button>
                    <button type="button" class="btn btn-sm" style="
                        background: #06b6d4;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 8px 16px;
                        cursor: pointer;
                    " onclick="saveNotes()">
                        Lưu ghi chú
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const conversationId = {{ $conversation->id }};
    let selectedImages = [];

    // Handle image selection
    document.getElementById('adminImageInput').addEventListener('change', function(e) {
        selectedImages = Array.from(e.target.files).slice(0, 5);
        displayImagePreviews();
    });

    function displayImagePreviews() {
        const preview = document.getElementById('adminImagePreview');
        preview.innerHTML = '';
        
        selectedImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const item = document.createElement('div');
                item.style.cssText = 'position: relative; width: 80px; height: 80px;';
                item.innerHTML = `
                    <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 2px; right: 2px; width: 24px; height: 24px; padding: 0; font-size: 12px; border-radius: 4px;" onclick="removeImage(${index})">×</button>
                `;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedImages.splice(index, 1);
        displayImagePreviews();
    }

    // Handle send message
    document.getElementById('adminChatForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = document.getElementById('adminMessageInput').value.trim();
        if (!message && selectedImages.length === 0) {
            alert('Vui lòng nhập tin nhắn hoặc chọn ảnh');
            return;
        }

        const formData = new FormData();
        if (message) formData.append('message', message);
        selectedImages.forEach((file, i) => {
            formData.append(`images[${i}]`, file);
        });

        const sendBtn = document.getElementById('adminSendBtn');
        sendBtn.disabled = true;

        try {
            const response = await fetch(`/admin/chat/${conversationId}/message`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            });

            if (response.ok) {
                document.getElementById('adminMessageInput').value = '';
                selectedImages = [];
                document.getElementById('adminImagePreview').innerHTML = '';
                loadMessages();
            } else {
                alert('Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi kết nối');
        } finally {
            sendBtn.disabled = false;
        }
    });

    async function loadMessages() {
        try {
            const response = await fetch(`/admin/chat/${conversationId}/messages`);
            const data = await response.json();
            if (data.messages && data.messages.length > 0) {
                setTimeout(() => window.location.reload(), 300);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Handle Notes Modal
    document.getElementById('openNotesBtn').addEventListener('click', function() {
        document.getElementById('notesModal').style.display = 'flex';
    });

    // Close modal when clicking outside
    document.getElementById('notesModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });

    function saveNotes() {
        const notes = document.getElementById('notesText').value;
        // Send notes to server (implement endpoint)
        alert('Ghi chú: ' + notes);
        document.getElementById('notesModal').style.display = 'none';
    }

    // Auto scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }

    scrollToBottom();

    // Poll messages every 5 seconds and auto-scroll
    setInterval(() => {
        loadMessages();
        scrollToBottom();
    }, 5000);
</script>

<style>
    textarea {
        font-size: 14px;
    }

    textarea:focus {
        border-color: #667eea !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    @media (max-width: 768px) {
        [style*="width: 320px"] {
            width: 100% !important;
            border-left: 0;
            border-top: 1px solid #e5e7eb;
            max-height: 300px;
        }
    }
</style>
@endsection
