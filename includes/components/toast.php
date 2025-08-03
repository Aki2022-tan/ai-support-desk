<?php
// Modular Multi-Toast Notification Component (Session-Based)
// Usage: $_SESSION['toast'] = ['type' => 'success'|'error'|'info', 'message' => '...'];
// Or: $_SESSION['toast'] = [ [...], [...], ... ];

if (isset($_SESSION['toast'])):

    // Normalize to multiple toast format
    $toasts = is_array($_SESSION['toast'][0] ?? null) ? $_SESSION['toast'] : [$_SESSION['toast']];

    // Styling by type
    $toastStyles = [
        'success' => 'bg-green-50 border border-green-400 text-green-800',
        'error'   => 'bg-red-50 border border-red-400 text-red-800',
        'info'    => 'bg-blue-50 border border-blue-400 text-blue-800',
    ];


?>

<!-- Toast Container (Centered Top) -->
<div id="toast-container" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 space-y-4 w-[95%] max-w-md px-4">
    <?php foreach ($toasts as $toast): 
        $type = htmlspecialchars($toast['type'] ?? 'info');
        $message = htmlspecialchars($toast['message'] ?? 'An unexpected error occurred.');
        $styleClass = $toastStyles[$type] ?? $toastStyles['info'];
    ?>
    <div class="toast flex items-center justify-between gap-4 p-4 rounded-2xl border shadow-xl <?= $styleClass ?> animate-fadeInSlide">
        <p class="text-sm font-medium leading-snug flex-1"><?= $message ?></p>
        <button onclick="dismissToast(this)" class="text-lg font-semibold leading-none focus:outline-none">&times;</button>
    </div>
    <?php endforeach; ?>
</div>

<!-- Script: Dismiss and Auto-Close -->
<script>
    function dismissToast(button) {
        const toast = button.closest('.toast');
        if (toast) {
            toast.classList.add('animate-fadeOutSlide');
            setTimeout(() => toast.remove(), 400);
        }
    }

    // Auto-dismiss after timeout
    setTimeout(() => {
        document.querySelectorAll('.toast').forEach(toast => {
            if (toast.querySelector('button')) {
                dismissToast(toast.querySelector('button'));
            }
        });
    }, 4000);
</script>

<!-- Tailwind-Safe Style Injection -->
<style>
    /* Keyframes for animations */
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes fadeOutSlide {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to   { opacity: 0; transform: translateY(-10px) scale(0.95); }
    }

    /* Animate classes used in JS */
    .animate-fadeInSlide {
        animation: fadeInSlide 0.4s ease-out forwards;
    }
    .animate-fadeOutSlide {
        animation: fadeOutSlide 0.3s ease-in forwards;
    }
    
    @media (min-width: 768px) {
    .animate-fadeInSlide {
        animation: fadeInSlideFromRight 0.4s ease-out forwards;
    }

    @keyframes fadeInSlideFromRight {
        from { opacity: 0; transform: translateX(50px) scale(0.95); }
        to   { opacity: 1; transform: translateX(0) scale(1); }
    }
}
</style>

<!-- Tailwind JIT-safe block -->
<div class="hidden">
    <?php foreach ($toastStyles as $class): ?>
        <div class="<?= $class ?>"></div>
    <?php endforeach; ?>
</div>

<?php unset($_SESSION['toast']); endif; ?>