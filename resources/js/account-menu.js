function initAccountMenu() {
    const menus = document.querySelectorAll('[data-account-menu]');
    if (!menus.length) return;

    menus.forEach(menu => {
        const toggle = menu.querySelector('[data-account-toggle]');
        const panel = menu.querySelector('[data-account-panel]');
        let open = false;

        const setOpen = (state) => {
            open = state;
            menu.dataset.open = state ? 'true' : 'false';
            if (panel) panel.hidden = !state;
        };

        toggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            setOpen(!open);
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target)) setOpen(false);
        });

        document.addEventListener('keydown', (e) => {
            if (open && e.key === 'Escape') setOpen(false);
        });
    });
}
