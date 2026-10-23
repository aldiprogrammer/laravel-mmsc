const sidebar = document.getElementById('admin-sidebar');
const overlay = document.getElementById('admin-overlay');

const openSidebar = () => {
    sidebar?.classList.remove('-translate-x-full');
    overlay?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
};

const closeSidebar = () => {
    sidebar?.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
    button.addEventListener('click', openSidebar);
});

document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
    button.addEventListener('click', closeSidebar);
});

overlay?.addEventListener('click', closeSidebar);

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        closeSidebar();
    }
});

const dropdowns = Array.from(document.querySelectorAll('[data-dropdown]'));

const closeDropdowns = (except = null) => {
    dropdowns.forEach((dropdown) => {
        if (dropdown === except) {
            return;
        }

        dropdown.querySelector('[data-dropdown-menu]')?.classList.add('hidden');
        dropdown.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
    });
};

dropdowns.forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');

    trigger?.addEventListener('click', (event) => {
        event.stopPropagation();

        const isOpen = !menu.classList.contains('hidden');

        closeDropdowns();

        if (!isOpen) {
            menu.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
        }
    });
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('[data-dropdown]')) {
        closeDropdowns();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeSidebar();
        closeDropdowns();
    }
});

const escapeHtml = (value) =>
    String(value).replace(
        /[&<>"']/g,
        (character) =>
            ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[character],
    );

const chartData = document.getElementById('booking-chart-data');
const chartBars = document.querySelector('[data-chart-bars]');
const chartLabels = document.querySelector('[data-chart-labels]');
const chartRanges = Array.from(document.querySelectorAll('[data-chart-range]'));

const renderChart = (range) => {
    if (!chartData || !chartBars || !chartLabels) {
        return;
    }

    const payload = JSON.parse(chartData.textContent);
    const points = payload[range] ?? [];
    const max = Math.max(...points.map((point) => point.value), 1);

    chartBars.innerHTML = points
        .map((point) => {
            const height = point.value > 0 ? Math.max((point.value / max) * 100, 4) : 1.5;

            return `
                <div class="group relative flex h-full flex-1 items-end">
                    <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-2 py-1 text-[11px] font-medium text-white opacity-0 transition-opacity group-hover:opacity-100">
                        ${escapeHtml(point.value)} booking &middot; ${escapeHtml(point.date)}
                    </div>
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-blue-600 to-blue-400 transition-colors group-hover:from-blue-700 group-hover:to-blue-500" style="height: ${height}%"></div>
                </div>`;
        })
        .join('');

    chartLabels.innerHTML = points
        .map((point, index) => {
            const isVisible = points.length <= 7 || index % 3 === 0;

            return `<span class="flex-1 truncate text-center text-[10px] text-slate-500 sm:text-[11px]">${
                isVisible ? escapeHtml(point.label) : ''
            }</span>`;
        })
        .join('');

    chartRanges.forEach((button) => {
        const isActive = button.dataset.chartRange === String(range);

        button.classList.toggle('bg-white', isActive);
        button.classList.toggle('text-blue-700', isActive);
        button.classList.toggle('shadow-sm', isActive);
        button.classList.toggle('text-slate-600', !isActive);
    });
};

chartRanges.forEach((button) => {
    button.addEventListener('click', () => renderChart(button.dataset.chartRange));
});
