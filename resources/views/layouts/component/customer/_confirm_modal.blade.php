{{--
    Reusable confirmation modal component
    Usage: @include('layouts.component.customer._confirm_modal')
    
    Then trigger via JS:
    showConfirmModal({
        title: 'Hapus Alamat?',
        message: 'Alamat ini akan dihapus permanen.',
        confirmText: 'Ya, Hapus',
        cancelText: 'Batal',
        type: 'danger', // 'danger' | 'warning' | 'info'
        onConfirm: () => document.getElementById('form-id').submit()
    });
--}}

<div id="confirm-modal-overlay" class="confirm-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="confirm-backdrop" id="confirm-backdrop"></div>
    <div class="confirm-card" id="confirm-card">
        <div class="confirm-icon" id="confirm-icon"></div>
        <h3 class="confirm-title" id="confirm-title"></h3>
        <p class="confirm-message" id="confirm-message"></p>
        <div class="confirm-actions">
            <button type="button" class="confirm-btn confirm-btn--cancel" id="confirm-cancel">Batal</button>
            <button type="button" class="confirm-btn confirm-btn--confirm" id="confirm-ok">Konfirmasi</button>
        </div>
    </div>
</div>

<style>
.confirm-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.confirm-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 15, 35, 0.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.confirm-backdrop.visible {
    opacity: 1;
}
.confirm-card {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    padding: 28px 24px 22px;
    max-width: 340px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 47, 92, 0.25), 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: scale(0.85) translateY(20px);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
}
.confirm-card.visible {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.confirm-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    margin: 0 auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.confirm-icon.type-danger {
    background: #fff1f1;
    border: 2px solid #fecaca;
}
.confirm-icon.type-warning {
    background: #fffbeb;
    border: 2px solid #fde68a;
}
.confirm-icon.type-info {
    background: #e0f4ff;
    border: 2px solid #bae6fd;
}
.confirm-title {
    font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;
    font-size: 1.1rem;
    color: #1a2332;
    margin-bottom: 8px;
    line-height: 1.3;
}
.confirm-message {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 20px;
}
.confirm-actions {
    display: flex;
    gap: 10px;
}
.confirm-btn {
    flex: 1;
    padding: 12px 16px;
    border-radius: 12px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 800;
    cursor: pointer;
    border: none;
    transition: transform 0.15s, box-shadow 0.15s;
}
.confirm-btn:active {
    transform: scale(0.96);
}
.confirm-btn--cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1.5px solid #e2e8f0;
}
.confirm-btn--cancel:hover {
    background: #e2e8f0;
}
.confirm-btn--confirm.type-danger {
    background: #ef4444;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}
.confirm-btn--confirm.type-danger:hover {
    background: #dc2626;
}
.confirm-btn--confirm.type-warning {
    background: #f59e0b;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}
.confirm-btn--confirm.type-warning:hover {
    background: #d97706;
}
.confirm-btn--confirm.type-info {
    background: #0077b6;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 119, 182, 0.3);
}
.confirm-btn--confirm.type-info:hover {
    background: #005f92;
}
</style>

<script>
(function() {
    let _onConfirmCallback = null;

    window.showConfirmModal = function(options) {
        var overlay = document.getElementById('confirm-modal-overlay');
        var backdrop = document.getElementById('confirm-backdrop');
        var card = document.getElementById('confirm-card');
        var icon = document.getElementById('confirm-icon');
        var title = document.getElementById('confirm-title');
        var message = document.getElementById('confirm-message');
        var cancelBtn = document.getElementById('confirm-cancel');
        var okBtn = document.getElementById('confirm-ok');

        var type = options.type || 'danger';
        var icons = { danger: '⚠️', warning: '⚡', info: 'ℹ️' };

        title.textContent = options.title || 'Konfirmasi';
        message.textContent = options.message || 'Apakah Anda yakin?';
        cancelBtn.textContent = options.cancelText || 'Batal';
        okBtn.textContent = options.confirmText || 'Ya, Lanjutkan';

        icon.className = 'confirm-icon type-' + type;
        icon.textContent = icons[type] || icons.danger;
        okBtn.className = 'confirm-btn confirm-btn--confirm type-' + type;

        _onConfirmCallback = options.onConfirm || null;

        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function() {
            backdrop.classList.add('visible');
            card.classList.add('visible');
        });
    };

    function closeModal() {
        var overlay = document.getElementById('confirm-modal-overlay');
        var backdrop = document.getElementById('confirm-backdrop');
        var card = document.getElementById('confirm-card');

        backdrop.classList.remove('visible');
        card.classList.remove('visible');

        setTimeout(function() {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
            _onConfirmCallback = null;
        }, 250);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var cancelBtn = document.getElementById('confirm-cancel');
        var okBtn = document.getElementById('confirm-ok');
        var backdrop = document.getElementById('confirm-backdrop');

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeModal);
        }
        if (okBtn) {
            okBtn.addEventListener('click', function() {
                if (_onConfirmCallback) {
                    _onConfirmCallback();
                }
                closeModal();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var overlay = document.getElementById('confirm-modal-overlay');
                if (overlay && overlay.style.display !== 'none') {
                    closeModal();
                }
            }
        });
    });
})();
</script>
