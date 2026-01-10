<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Danh sách cuộc hội thoại (cho admin)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Nếu là admin, xem tất cả conversations chưa được assign hoặc assign cho mình
        if ($user->is_admin) {
            $conversations = Conversation::with('user', 'messages')
                ->where(function($q) use ($user) {
                    $q->whereNull('admin_id')
                      ->orWhere('admin_id', $user->id);
                })
                ->orderByDesc('last_message_at')
                ->paginate(15);
            
            return view('admin.chat.list', compact('conversations'));
        }
        
        // Nếu là khách, xem conversation riêng của mình
        $conversations = Conversation::with('admin', 'messages')
            ->where('user_id', $user->id)
            ->orderByDesc('last_message_at')
            ->get();
        
        return view('customer.chat.index', compact('conversations'));
    }

    /**
     * Chi tiết 1 cuộc hội thoại
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền truy cập
        if (!$user->is_admin && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Load messages
        $conversation->load('messages.sender');
        
        // Đánh dấu tin nhắn của user khác là đã đọc
        Message::where('conversation_id', $conversation->id)
            ->where('is_read', false)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);
        
        // Cập nhật lại conversation
        $conversation->update(['unread_count' => 0]);
        
        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    /**
     * Gửi tin nhắn mới
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền
        if (!$user->is_admin && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);
        
        // Kiểm tra có tin nhắn hoặc ảnh
        if (empty($validated['message']) && empty($validated['images'])) {
            return response()->json(['error' => 'Cần tin nhắn hoặc ảnh'], 400);
        }
        
        // Xử lý upload ảnh
        $imageUrls = [];
        if (!empty($validated['images'])) {
            foreach ($validated['images'] as $image) {
                if ($image->isValid()) {
                    $path = $image->store('chat', 'public');
                    $imageUrls[] = \Illuminate\Support\Facades\Storage::url($path);
                }
            }
        }
        
        // Tạo tin nhắn mới
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_type' => $user->is_admin ? 'admin' : 'customer',
            'message' => $validated['message'] ?? '',
            'images' => !empty($imageUrls) ? $imageUrls : null,
            'is_read' => false,
        ]);
        
        // Cập nhật conversation
        $conversation->update([
            'last_message' => $validated['message'] ?? '[Có ảnh]',
            'last_message_at' => now(),
            'admin_id' => $user->is_admin ? $user->id : $conversation->admin_id,
        ]);
        
        // Nếu là khách, tăng unread_count
        if (!$user->is_admin) {
            $conversation->increment('unread_count');
        }
        
        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Bắt đầu conversation mới (từ customer)
     */
    public function startConversation(Request $request)
    {
        $user = Auth::user();
        
        // Admin không thể sử dụng customer chat bubble
        if ($user && $user->is_admin) {
            return response()->json(['error' => 'Admin users cannot use customer chat'], 403);
        }
        
        // Kiểm tra xem đã có conversation nào chưa
        $existingConversation = Conversation::where('user_id', $user->id)->first();
        
        if ($existingConversation) {
            return response()->json(['conversation' => $existingConversation]);
        }
        
        // Tạo conversation mới
        $conversation = Conversation::create([
            'user_id' => $user->id,
            'admin_id' => null,
            'last_message' => null,
            'last_message_at' => null,
            'unread_count' => 0,
            'status' => 'open',
        ]);
        
        return response()->json([
            'success' => true,
            'conversation' => $conversation,
        ]);
    }

    /**
     * Đánh dấu conversation là đã đọc
     */
    public function markAsRead(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền
        if ($user->is_admin) {
            Message::where('conversation_id', $conversation->id)
                ->where('sender_type', 'customer')
                ->update(['is_read' => true]);
        } else {
            if ($conversation->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            Message::where('conversation_id', $conversation->id)
                ->where('sender_type', 'admin')
                ->update(['is_read' => true]);
        }
        
        $conversation->update(['unread_count' => 0]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Lấy tin nhắn mới (polling)
     */
    public function getNewMessages(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền
        if (!$user->is_admin && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json([
            'messages' => $messages,
            'unread_count' => $conversation->unread_count,
        ]);
    }

    /**
     * Danh sách chat cho Admin
     */
    public function adminList()
    {
        $admin = Auth::user();
        
        // Kiểm tra là admin
        if (!$admin->is_admin) {
            abort(403, 'Unauthorized');
        }

        $conversations = Conversation::with('user', 'messages')
            ->where(function($q) use ($admin) {
                $q->whereNull('admin_id')  // Chưa assign
                  ->orWhere('admin_id', $admin->id);  // Assign cho mình
            })
            ->when(request('search'), function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('email', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('admin.chat.list', compact('conversations'));
    }

    /**
     * Chi tiết chat cho Admin
     */
    public function adminShow(Conversation $conversation)
    {
        $admin = Auth::user();
        
        // Kiểm tra là admin
        if (!$admin->is_admin) {
            abort(403, 'Unauthorized');
        }

        // Assign conversation cho admin nếu chưa assign
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => $admin->id]);
        } elseif ($conversation->admin_id !== $admin->id) {
            abort(403, 'Conversation already assigned to another admin');
        }

        $conversation->load('user', 'messages.sender');
        
        // Mark all customer messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->update(['unread_count' => 0]);

        // Check if AJAX request
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'conversation' => $conversation,
                'messages' => $conversation->messages
            ]);
        }

        return view('admin.chat.show', compact('conversation'));
    }

    /**
     * Admin gửi tin nhắn
     */
    public function adminStore(Request $request, Conversation $conversation)
    {
        $admin = Auth::user();
        
        // Kiểm tra là admin
        if (!$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Kiểm tra conversation - cho phép nếu chưa assign
        if ($conversation->admin_id !== $admin->id && $conversation->admin_id !== null) {
            return response()->json(['error' => 'Not assigned to you'], 403);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120', // 5MB
        ]);

        // Yêu cầu có tin nhắn hoặc ảnh
        if (!$validated['message'] && (!$validated['images'] || empty($validated['images']))) {
            return response()->json(['error' => 'Message or images required'], 422);
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('chat', 'public');
                $images[] = '/storage/' . $path;
            }
        }

        // Tự assign nếu chưa assign
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => $admin->id]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'sender_type' => 'admin',
            // DB column message NOT NULL -> use empty string when only images
            'message' => $validated['message'] ?? '',
            'images' => !empty($images) ? json_encode($images) : null,
            'is_read' => true,
        ]);

        // Update conversation
        $conversation->update([
            'last_message' => $validated['message'] ?? '[Image]',
            'last_message_at' => now(),
            'unread_count' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Admin polling - lấy tin nhắn mới
     */
    public function adminGetMessages(Conversation $conversation)
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin || $conversation->admin_id !== $admin->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastId = request('last_id', 0);

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'last_id' => $messages->last()?->id ?? $lastId,
        ]);
    }

    /**
     * Admin getUnreadCount - badge thông báo
     */
    public function adminUnreadCount()
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin) {
            return response()->json(['count' => 0]);
        }

        $count = Conversation::where(function($q) use ($admin) {
                $q->whereNull('admin_id')
                  ->orWhere('admin_id', $admin->id);
            })
            ->where('unread_count', '>', 0)
            ->sum('unread_count');

        return response()->json(['count' => $count]);
    }

    /**
     * Mark conversation as resolved
     */
    public function markResolved(Conversation $conversation)
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin || ($conversation->admin_id !== $admin->id && $conversation->admin_id !== null)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->update(['status' => 'resolved', 'resolved_at' => now()]);
        return response()->json(['success' => true, 'message' => 'Đã đánh dấu đã xử lý']);
    }

    /**
     * Close conversation
     */
    public function closeConversation(Conversation $conversation)
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin || ($conversation->admin_id !== $admin->id && $conversation->admin_id !== null)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->update(['status' => 'closed', 'resolved_at' => now()]);
        return response()->json(['success' => true, 'message' => 'Đã đóng cuộc trò chuyện']);
    }

    /**
     * Delete conversation
     */
    public function deleteConversation(Conversation $conversation)
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin || ($conversation->admin_id !== $admin->id && $conversation->admin_id !== null)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Message::where('conversation_id', $conversation->id)->delete();
        $conversation->delete();
        return response()->json(['success' => true, 'message' => 'Đã xóa cuộc trò chuyện']);
    }

    /**
     * Pin/Unpin conversation
     */
    public function togglePin(Conversation $conversation)
    {
        $admin = Auth::user();
        
        if (!$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->update([
            'is_pinned' => !$conversation->is_pinned,
            'pinned_at' => $conversation->is_pinned ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => $conversation->is_pinned ? 'Đã bỏ ghim' : 'Đã ghim cuộc trò chuyện',
            'is_pinned' => $conversation->is_pinned
        ]);
    }
}

