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

    // Only bind to toolbar buttons, not task cards
    const statusBtns = document.querySelectorAll('.toolbar [data-status-btn]');
    statusBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            statusBtns.forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            activeFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    const priorityBtns = document.querySelectorAll('.toolbar [data-priority-btn]');
    priorityBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            priorityBtns.forEach(b => {
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

function initDeleteModal() {
    const backdrop = document.getElementById('delete-backdrop');
    const form = document.getElementById('delete-form');
    const cancelBtn = document.getElementById('delete-cancel');
    const deleteBtns = document.querySelectorAll('[data-delete-id]');

    if (!backdrop || !form) return;

    const close = () => {
        backdrop.hidden = true;
        form.action = '';
    };

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.deleteId;
            // Assumes standard resource route: /todos/{id}
            form.action = `/todos/${id}`;
            backdrop.hidden = false;
        });
    });

    cancelBtn && cancelBtn.addEventListener('click', close);
    
    // Close on click outside
    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) close();
    });
    
    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !backdrop.hidden) close();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initFilters();
    initDeleteModal();
});
