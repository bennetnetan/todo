function initFilters() {
    const listEl = document.getElementById('task-list');
    const noResults = document.getElementById('no-results');
    if (!listEl) return;

    const cards = () => Array.from(listEl.querySelectorAll('.task-card'));
    const searchInp = document.getElementById('search-input');
    const sortSel = document.getElementById('sort-select');

    let activeFilter = 'all';
    let activePriority = 'all';
    const PRIORITY_ORDER = { high: 0, medium: 1, low: 2, none: 3 };

    const applyFilters = () => {
        const q = searchInp ? searchInp.value.toLowerCase().trim() : '';
        const visible = [];
        const hidden = [];

        cards().forEach(card => {
            const status = card.dataset.status;
            const priority = card.dataset.priority;
            const title = card.dataset.title;
            const desc = card.dataset.desc;

            const matchStatus = activeFilter === 'all' || status === activeFilter;
            const matchPriority = activePriority === 'all' || priority === activePriority;
            const matchSearch = !q || title.includes(q) || desc.includes(q);

            if (matchStatus && matchPriority && matchSearch) {
                visible.push(card);
            } else {
                card.style.display = 'none';
                hidden.push(card);
            }
        });

        const sortVal = sortSel ? sortSel.value : 'default';
        visible.sort((a, b) => {
            switch (sortVal) {
                case 'priority-desc': return (PRIORITY_ORDER[a.dataset.priority] ?? 3) - (PRIORITY_ORDER[b.dataset.priority] ?? 3);
                case 'priority-asc':  return (PRIORITY_ORDER[b.dataset.priority] ?? 3) - (PRIORITY_ORDER[a.dataset.priority] ?? 3);
                case 'title-asc':     return a.dataset.title.localeCompare(b.dataset.title);
                case 'title-desc':    return b.dataset.title.localeCompare(a.dataset.title);
                case 'due-asc': {
                    const da = a.dataset.due || '9999-12-31';
                    const db = b.dataset.due || '9999-12-31';
                    return da.localeCompare(db);
                }
                case 'due-desc': {
                    const da = a.dataset.due || '';
                    const db = b.dataset.due || '';
                    return db.localeCompare(da);
                }
                default: return 0;
            }
        });

        visible.forEach(card => {
            card.style.display = '';
            listEl.appendChild(card);
        });
        hidden.forEach(card => listEl.appendChild(card));

        if (noResults) noResults.style.display = visible.length === 0 ? 'block' : 'none';
    };

    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-filter]').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            activeFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    document.querySelectorAll('[data-priority]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-priority]').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            activePriority = btn.dataset.priority;
            applyFilters();
        });
    });

    searchInp && searchInp.addEventListener('input', applyFilters);
    sortSel && sortSel.addEventListener('change', applyFilters);
}

function loadSortable(callback) {
    if (window.Sortable) {
        callback(window.Sortable);
        return;
    }
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js';
    script.defer = true;
    script.onload = () => window.Sortable && callback(window.Sortable);
    document.head.appendChild(script);
}

function initSortable() {
    const listEl = document.getElementById('task-list');
    if (!listEl) return;

    loadSortable(() => {
        window.Sortable.create(listEl, {
            animation: 180,
            easing: 'cubic-bezier(.4,0,.2,1)',
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd() {
                const order = Array.from(listEl.querySelectorAll('.task-card')).map(c => c.dataset.id).filter(Boolean);
                console.log('New order:', order);
            },
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initFilters();
    initSortable();
    initDeleteModal();
    initAccountMenu();
});

function initDeleteModal() {
    const backdrop = document.getElementById('delete-backdrop');
    if (!backdrop) return;

    const form = document.getElementById('delete-form');
    const titleEl = document.getElementById('delete-title');
    const cancelBtn = document.getElementById('delete-cancel');

    const open = (id, title) => {
        form.action = `/todos/${id}`;
        titleEl.textContent = `Delete “${title}”`;
        backdrop.hidden = false;
        backdrop.style.display = 'grid';
        cancelBtn.focus();
    };

    const close = () => {
        backdrop.hidden = true;
        backdrop.style.display = 'none';
    };

    document.querySelectorAll('[data-delete-id]').forEach(btn => {
        btn.addEventListener('click', () => {
            open(btn.dataset.deleteId, btn.dataset.deleteTitle || 'this task');
        });
    });

    cancelBtn?.addEventListener('click', close);
    backdrop.addEventListener('click', (e) => { if (e.target === backdrop) close(); });
    document.addEventListener('keydown', (e) => {
        if (!backdrop.hidden && e.key === 'Escape') close();
    });
}

function initAccountMenu() {
    const menu = document.querySelector('.account-menu');
    if (!menu) return;
    const toggle = document.getElementById('account-menu-toggle');
    const panel = document.getElementById('account-menu-panel');
    let open = false;

    const setOpen = (state) => {
        open = state;
        menu.dataset.open = state ? 'true' : 'false';
        if (panel) panel.hidden = !state;
    };

    toggle?.addEventListener('click', () => setOpen(!open));
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) setOpen(false);
    });
    document.addEventListener('keydown', (e) => {
        if (open && e.key === 'Escape') setOpen(false);
    });
}
