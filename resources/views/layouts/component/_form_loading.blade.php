{{--
    Form loading state utility — prevents double-submit on all forms
    Usage: @include('layouts.component._form_loading')
    
    Automatically hooks into all <form> elements on the page.
    Disables submit button + shows loading spinner on click.
--}}
<style>
.btn-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}
.btn-loading::after {
    content: '';
    position: absolute;
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: btn-spin 0.6s linear infinite;
    right: 14px;
    top: 50%;
    margin-top: -8px;
}
@keyframes btn-spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Skip if already loading
                if (form.dataset.loading === 'true') {
                    e.preventDefault();
                    return;
                }

                // Find submit button
                var btn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (!btn) return;

                // Mark as loading
                form.dataset.loading = 'true';
                btn.classList.add('btn-loading');
                btn.disabled = true;

                // Auto-reset after 8s (for network timeout cases)
                setTimeout(function() {
                    form.dataset.loading = '';
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                }, 8000);
            });
        });
    });
})();
</script>
