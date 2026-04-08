/**
 * Widget Builder - Modern Drag & Drop Management
 * Using SortableJS for smooth interactions
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. COLLAPSIBLE LOGIC
    // Restore collapsed state from localStorage
    const getStoredCollapseState = () => JSON.parse(localStorage.getItem('widget_areas_collapse') || '{}');
    const setStoredCollapseState = (states) => localStorage.setItem('widget_areas_collapse', JSON.stringify(states));

    const collapseStates = getStoredCollapseState();

    document.querySelectorAll('.area-card').forEach(card => {
        const header = card.querySelector('.area-header');
        const body   = card.querySelector('.area-body');
        const areaId = card.dataset.area;
        const icon   = header.querySelector('.collapse-icon');

        const toggle = (isInitial = false) => {
            const isCollapsed = body.classList.toggle('collapsed');
            if (icon) icon.style.transform = isCollapsed ? 'rotate(0deg)' : 'rotate(180deg)';
            
            if (!isInitial) {
                const currentStates = getStoredCollapseState();
                currentStates[areaId] = isCollapsed;
                setStoredCollapseState(currentStates);
            }
        };

        // Initialize state
        if (collapseStates[areaId]) {
            body.classList.add('collapsed');
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            if (icon) icon.style.transform = 'rotate(180deg)';
        }

        header.addEventListener('click', () => toggle());
    });


    // 2. SEARCH FILTRATION
    const searchInput = document.getElementById('widget-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('.available-widget-item').forEach(item => {
                const label = (item.dataset.label || '').toLowerCase();
                const type  = (item.dataset.type || '').toLowerCase();
                item.style.display = (label.includes(query) || type.includes(query)) ? '' : 'none';
            });
        });
    }


    // 3. SORTABLEJS INITIALIZATION
    // Shared configuration
    const sortableConfig = {
        group: {
            name: 'widgets',
            pull: true,
            put: true
        },
        animation: 250,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        handle: '.widget-handle',
        fallbackTolerance: 3, // Sensitivity for mobile
        onEnd: (evt) => {
            // Save state via AJAX
            saveWidgetState();
            
            // Check for empty placeholders
            refreshPlaceholders();
        }
    };

    // Sidebar: Available Widgets (Clone only, no pull from areas to sidebar)
    const availableWidgetsEl = document.getElementById('available-widgets-list');
    if (availableWidgetsEl) {
        new Sortable(availableWidgetsEl, {
            group: {
                name: 'widgets',
                pull: 'clone', // Enable cloning
                put: false     // Don't allow dropping here
            },
            sort: false, // Don't sort the source list
            animation: 150,
            onClone: (evt) => {
                const origEl = evt.item;
                const cloneEl = evt.clone;
                // Add necessary classes or data if needed
            },
            onEnd: (evt) => {
                refreshPlaceholders();
                // If dropped into an area, it will trigger that area's onAdd
            }
        });
    }

    // Drop Zones
    document.querySelectorAll('.widget-drop-zone').forEach(el => {
        new Sortable(el, {
            ...sortableConfig,
            onAdd: (evt) => {
                const item = evt.item;
                // If it's a new widget from the available list
                if (item.classList.contains('available-widget-item')) {
                    transformToPlaced(item, el.dataset.area);
                }
                saveWidgetState();
                refreshPlaceholders();
            },
            onUpdate: () => {
                saveWidgetState();
            }
        });
    });


    // 4. TRANSFORM LOGIC (When pulling from Sidebar to Area)
    function transformToPlaced(el, areaId) {
        const type  = el.dataset.type;
        const label = el.dataset.label;
        const icon  = el.querySelector('i').className;

        // In a real app, you might want to open a modal here immediately 
        // to set the widget name/config before finalizing.
        // For now, let's just make it look like a placed widget.
        
        // AUTO-SAVE MOCK: We'd normally call the store API here to get a DB ID
        createNewWidget(type, areaId, label);
        
        // Remove the temporary clone from the DOM (will reload after API succeeds)
        // or replace it with a loading state.
        el.innerHTML = `<div class="d-flex align-items-center gap-2 text-muted px-2 py-1"><i class="fa-solid fa-spinner fa-spin"></i> Initializing...</div>`;
    }


    // 5. API INTERACTIONS
    function saveWidgetState() {
        const payload = [];
        document.querySelectorAll('.widget-drop-zone').forEach(zone => {
            const area = zone.dataset.area;
            zone.querySelectorAll('.placed-widget-item').forEach((item, index) => {
                const id = item.dataset.id;
                if (id) {
                    payload.push({ id: parseInt(id), area: area, sort_order: index });
                }
            });
        });

        if (payload.length === 0) return;

        // Use standard Larvel fetch/Axios
        fetch(window.REORDER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({ items: payload })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (window.adminToast) window.adminToast('Updated', 'Layout saved successfully', 'success');
            }
        })
        .catch(err => console.error('Save failed:', err));
    }

    function createNewWidget(type, area, defaultName) {
        const fd = new FormData();
        fd.append('type', type);
        fd.append('area', area);
        fd.append('name', defaultName);
        fd.append('_token', window.CSRF_TOKEN);

        fetch(window.STORE_URL, {
            method: 'POST',
            body: fd,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            }
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`Server Error: ${res.status}. Check console for details.`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                location.reload(); // Simplest way to get full UI back for the new widget
            }
        });
    }


    function refreshPlaceholders() {
        document.querySelectorAll('.widget-drop-zone').forEach(zone => {
            const hasItems = zone.querySelectorAll('.placed-widget-item').length > 0;
            const placeholder = zone.querySelector('.empty-area-placeholder');
            if (hasItems && placeholder) {
                placeholder.classList.add('hidden');
            } else if (!hasItems && placeholder) {
                placeholder.classList.remove('hidden');
            }
        });
    }

    // Initial check
    refreshPlaceholders();

});

// Global functions for inline calls
window.togglePlacedWidget = (id, btn) => {
    fetch(window.TOGGLE_URL.replace('{id}', id), {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': window.CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        btn.classList.toggle('active', d.is_active);
        if (window.adminToast) window.adminToast('Visibility', 'Widget state updated', 'success');
    });
};
