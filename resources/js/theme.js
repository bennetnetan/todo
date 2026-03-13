const STORAGE_KEY = 'todo-theme';

function applyTheme(dark) {
    const root = document.documentElement;
    root.setAttribute('data-theme', dark ? 'dark' : 'light');
    root.classList.toggle('dark', dark);

    const icon = document.getElementById('theme-icon');
    if (icon) icon.textContent = dark ? '🌙' : '☀️';

    try {
        localStorage.setItem(STORAGE_KEY, dark ? 'dark' : 'light');
    } catch (_) {
        // ignore storage issues (private mode, etc.)
    }
}

function initTheme() {
    const root = document.documentElement;
    const saved = (() => {
        try { return localStorage.getItem(STORAGE_KEY); } catch (_) { return null; }
    })();
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const startDark = saved ? saved === 'dark' : prefersDark;

    applyTheme(startDark);

    const toggle = document.getElementById('theme-toggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            applyTheme(root.getAttribute('data-theme') !== 'dark');
        });
    }
}

document.addEventListener('DOMContentLoaded', initTheme);
