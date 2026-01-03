// Admin Chat System - Unread count and notifications

class AdminChatSystem {
    constructor() {
        this.unreadCount = 0;
        this.init();
    }

    async init() {
        // Update unread count on page load
        await this.updateUnreadCount();
        
        // Poll for unread count every 10 seconds
        setInterval(() => this.updateUnreadCount(), 10000);
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/admin/chat/unread-count');
            const data = await response.json();
            
            if (data.count > 0) {
                this.showBadge(data.count);
            } else {
                this.hideBadge();
            }
        } catch (error) {
            console.error('Error fetching unread count:', error);
        }
    }

    showBadge(count) {
        let badge = document.getElementById('chatUnreadBadge');
        
        if (!badge) {
            // Create badge if it doesn't exist
            const chatLink = document.querySelector('a[href*="/admin/chat"]');
            if (chatLink) {
                badge = document.createElement('span');
                badge.id = 'chatUnreadBadge';
                badge.style.cssText = `
                    display: inline-block;
                    position: relative;
                    background-color: #ef4444;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    font-size: 12px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-left: 4px;
                `;
                chatLink.appendChild(badge);
            }
        }
        
        if (badge) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.style.display = 'inline-flex';
        }
    }

    hideBadge() {
        const badge = document.getElementById('chatUnreadBadge');
        if (badge) {
            badge.style.display = 'none';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    new AdminChatSystem();
});
