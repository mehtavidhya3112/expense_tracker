/**
 * Expense Tracker - Client-side interactivity
 * (Progressive enhancement only — the app fully works via PHP
 * form submissions even if JavaScript is disabled.)
 */

document.addEventListener('DOMContentLoaded', function () {

    // ---------------- Dark / Light theme toggle ----------------
    // The theme itself is already applied before paint by the inline
    // script in includes/header.php (to avoid a flash of the wrong
    // theme); this just wires up the toggle button and persists choice.
    const THEME_KEY = 'expenseTrackerTheme';
    const themeToggle = document.getElementById('themeToggle');

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            try {
                localStorage.setItem(THEME_KEY, next);
            } catch (e) {
                // localStorage unavailable (e.g. private browsing) — theme
                // still applies for this page view, just won't persist.
            }
        });
    }

    // Confirm before deleting an expense
    document.querySelectorAll('[data-confirm-delete]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            const confirmed = window.confirm(
                'Are you sure you want to delete this expense? This action cannot be undone.'
            );
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });

    // Auto-hide flash alert messages after a few seconds
    const flashAlert = document.getElementById('flashAlert');
    if (flashAlert) {
        const dismiss = function () {
            flashAlert.style.transition = 'opacity 0.4s ease';
            flashAlert.style.opacity = '0';
            setTimeout(function () {
                flashAlert.remove();
            }, 400);
        };

        const closeBtn = flashAlert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', dismiss);
        }

        setTimeout(dismiss, 4000);
    }

    // Extra client-side validation feedback for amount fields
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function () {
            const value = parseFloat(amountInput.value);
            if (isNaN(value) || value <= 0) {
                amountInput.setCustomValidity('Amount must be greater than zero.');
            } else {
                amountInput.setCustomValidity('');
            }
        });
    }
});
