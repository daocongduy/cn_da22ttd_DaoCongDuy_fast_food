<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit;
}
include '../../includes/header.php';
?>

<section class="container" style="padding: 32px 24px; max-width: 800px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 2em; font-weight: 700; color: #1f2937; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 12px;">
            <span>📬</span>
            <span>Tin nhắn của tôi</span>
        </h1>
        <p style="color: #6b7280;">Xem lịch sử liên hệ và phản hồi từ cửa hàng</p>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 2em; font-weight: 700;" id="sent-count">0</div>
            <div style="font-size: 0.9em; opacity: 0.9;">Tin nhắn đã gửi</div>
        </div>
        <div style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
            <div style="font-size: 2em; font-weight: 700;" id="reply-count">0</div>
            <div style="font-size: 0.9em; opacity: 0.9;">Phản hồi từ cửa hàng</div>
        </div>
    </div>

    <!-- Messages List -->
    <div id="messages-container" style="display: flex; flex-direction: column; gap: 16px;">
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <div style="font-size: 2em; margin-bottom: 8px;">⏳</div>
            <div>Đang tải...</div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" style="display: none; text-align: center; padding: 60px 20px; background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div style="font-size: 4em; margin-bottom: 16px; opacity: 0.5;">📭</div>
        <h3 style="color: #374151; margin-bottom: 8px;">Chưa có tin nhắn nào</h3>
        <p style="color: #6b7280; margin-bottom: 24px;">Bạn chưa gửi tin nhắn liên hệ nào</p>
        <a href="../../pages/contact.php" style="display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            📝 Gửi tin nhắn liên hệ
        </a>
    </div>
</section>

<script>
(function(){
    var container = document.getElementById('messages-container');
    var emptyState = document.getElementById('empty-state');
    
    function formatDateTime(d) {
        if (!d) return '-';
        return new Date(d.replace(' ', 'T')).toLocaleString('vi-VN');
    }
    
    function render(messages, totalSent, totalReplies) {
        document.getElementById('sent-count').textContent = totalSent;
        document.getElementById('reply-count').textContent = totalReplies;
        
        if (!messages || messages.length === 0) {
            container.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }
        
        container.style.display = 'flex';
        emptyState.style.display = 'none';
        
        container.innerHTML = messages.map(function(m) {
            if (m.type === 'sent') {
                // Tin nhắn user gửi
                return '<div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #3b82f6;">' +
                    '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">' +
                        '<div style="display: flex; align-items: center; gap: 8px; color: #3b82f6; font-weight: 600;">' +
                            '<span style="font-size: 1.2em;">📤</span>' +
                            '<span>Tin nhắn #' + m.id + ' - Bạn đã gửi</span>' +
                        '</div>' +
                        '<div style="font-size: 0.85em; color: #6b7280;">' + formatDateTime(m.created_at) + '</div>' +
                    '</div>' +
                    '<div style="background: #f8fafc; padding: 12px; border-radius: 8px; line-height: 1.6; color: #374151;">' + m.message + '</div>' +
                '</div>';
            } else {
                // Phản hồi từ admin
                return '<div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #22c55e;">' +
                    '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">' +
                        '<div style="display: flex; align-items: center; gap: 8px; color: #166534; font-weight: 600;">' +
                            '<span style="font-size: 1.2em;">💬</span>' +
                            '<span>Phản hồi cho tin nhắn #' + m.id + ' từ ' + (m.admin_name || 'Cửa hàng') + '</span>' +
                        '</div>' +
                        '<div style="font-size: 0.85em; color: #6b7280;">' + formatDateTime(m.created_at) + '</div>' +
                    '</div>' +
                    '<div style="background: white; padding: 12px; border-radius: 8px; line-height: 1.6; color: #374151;">' + m.message + '</div>' +
                '</div>';
            }
        }).join('');
    }
    
    function load() {
        fetch('../../../backend/user_contacts.php', {cache: 'no-store'})
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.ok) {
                    render(d.messages || [], d.total_sent || 0, d.total_replies || 0);
                } else {
                    container.innerHTML = '<div style="text-align:center;padding:40px;color:#ef4444;">❌ ' + (d.error || 'Không tải được') + '</div>';
                }
            })
            .catch(function() {
                container.innerHTML = '<div style="text-align:center;padding:40px;color:#ef4444;">❌ Không tải được dữ liệu</div>';
            });
    }
    
    load();
    // Auto refresh mỗi 30 giây
    setInterval(load, 30000);
})();
</script>

<?php include '../../includes/footer.php'; ?>
