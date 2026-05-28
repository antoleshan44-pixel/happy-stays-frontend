


<?php $__env->startSection('title', 'My Calendar'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
<style>
    :root {
        --brand: #00288e;
        --brand-light: #e6e9f5;
        --brand-mid: #374fad;
        --success: #059669;
        --success-bg: #d1fae5;
        --warning: #d97706;
        --warning-bg: #fef3c7;
        --muted: #6b7280;
        --muted-bg: #f3f4f6;
        --surface: #f7f9fb;
        --card: #ffffff;
        --border: #e5e7eb;
        --text: #111827;
        --text-secondary: #4b5563;
    }

    .cal-page {
        font-family: 'DM Sans', sans-serif;
        background: var(--surface);
        min-height: 100vh;
        padding: 2rem 0 4rem;
    }

    /* ── Header ── */
    .cal-header {
        margin-bottom: 2.5rem;
    }
    .cal-header__eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--brand);
        margin-bottom: 0.4rem;
    }
    .cal-header__title {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: clamp(2rem, 4vw, 2.75rem);
        color: var(--text);
        line-height: 1.1;
        margin: 0 0 0.5rem;
    }
    .cal-header__title em {
        font-style: italic;
        color: var(--brand);
    }
    .cal-header__sub {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 400;
    }

    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 640px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); }
    }

    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.25rem 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,40,142,0.08);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
        background: var(--accent, var(--brand));
    }
    .stat-card--total  { --accent: var(--brand); }
    .stat-card--upcoming { --accent: var(--success); }
    .stat-card--completed { --accent: var(--muted); }
    .stat-card--nights { --accent: #8b5cf6; }

    .stat-card__icon {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .stat-card__value {
        font-family: 'DM Serif Display', serif;
        font-size: 2rem;
        color: var(--text);
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .stat-card--upcoming .stat-card__value { color: var(--success); }
    .stat-card--nights .stat-card__value { color: #7c3aed; }
    .stat-card__label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--muted);
    }

    /* ── Calendar card ── */
    .cal-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    }
    .cal-card__inner {
        padding: 1.5rem;
    }

    /* ── FullCalendar overrides ── */
    .fc {
        font-family: 'DM Sans', sans-serif !important;
    }
    .fc .fc-toolbar {
        margin-bottom: 1.5rem !important;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .fc .fc-toolbar-title {
        font-family: 'DM Serif Display', serif !important;
        font-size: 1.4rem !important;
        font-weight: 400 !important;
        color: var(--text) !important;
    }
    .fc .fc-button {
        font-family: 'DM Sans', sans-serif !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.02em !important;
        padding: 0.45rem 0.9rem !important;
        border-radius: 0.5rem !important;
        text-transform: none !important;
    }
    .fc .fc-button-primary {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        box-shadow: none !important;
    }
    .fc .fc-button-primary:hover {
        background: #002072 !important;
        border-color: #002072 !important;
    }
    .fc .fc-button-primary:focus {
        box-shadow: 0 0 0 3px rgba(0,40,142,0.2) !important;
    }
    .fc .fc-button-primary:not(.fc-button-active):not(:hover) {
        background: var(--brand-light) !important;
        border-color: var(--brand-light) !important;
        color: var(--brand) !important;
    }
    .fc .fc-button-primary.fc-button-active {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        color: white !important;
    }
    .fc .fc-today-button {
        background: var(--brand-light) !important;
        border-color: var(--brand-light) !important;
        color: var(--brand) !important;
    }
    .fc .fc-today-button:not(:disabled):hover {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        color: white !important;
    }
    .fc .fc-col-header-cell-cushion {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        color: var(--muted) !important;
        padding: 0.75rem 0 !important;
        text-decoration: none !important;
    }
    .fc .fc-daygrid-day-number {
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        color: var(--text-secondary) !important;
        padding: 0.5rem !important;
        text-decoration: none !important;
    }
    .fc .fc-day-today {
        background: var(--brand-light) !important;
    }
    .fc .fc-day-today .fc-daygrid-day-number {
        color: var(--brand) !important;
        font-weight: 700 !important;
    }
    .fc .fc-daygrid-event {
        border-radius: 0.35rem !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        padding: 2px 6px !important;
        border: none !important;
        margin: 1px 2px !important;
    }
    .fc .fc-event-title {
        font-weight: 500 !important;
    }
    .fc-event-confirmed { background: var(--success) !important; color: white !important; }
    .fc-event-pending   { background: var(--warning) !important; color: white !important; }
    .fc-event-completed { background: var(--muted)   !important; color: white !important; }

    .fc .fc-scrollgrid {
        border-color: var(--border) !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }
    .fc .fc-scrollgrid td,
    .fc .fc-scrollgrid th {
        border-color: var(--border) !important;
    }
    .fc .fc-daygrid-day.fc-day-other .fc-daygrid-day-number {
        color: #d1d5db !important;
    }

    /* ── Legend ── */
    .cal-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #fafafa;
        border-top: 1px solid var(--border);
    }
    .cal-legend__item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--text-secondary);
    }
    .cal-legend__dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .cal-legend__dot--confirmed { background: var(--success); }
    .cal-legend__dot--pending   { background: var(--warning); }
    .cal-legend__dot--completed { background: var(--muted); }

    /* ── Upcoming stays sidebar strip ── */
    .upcoming-strip {
        margin-top: 1.5rem;
        display: grid;
        gap: 0.75rem;
    }
    .upcoming-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow 0.2s;
    }
    .upcoming-item:hover { box-shadow: 0 4px 16px rgba(0,40,142,0.07); }
    .upcoming-item__date {
        background: var(--brand-light);
        color: var(--brand);
        border-radius: 0.6rem;
        width: 48px; height: 52px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .upcoming-item__date-day {
        font-family: 'DM Serif Display', serif;
        font-size: 1.25rem;
        line-height: 1;
    }
    .upcoming-item__date-mon {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.8;
    }
    .upcoming-item__info { flex: 1; min-width: 0; }
    .upcoming-item__name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .upcoming-item__meta {
        font-size: 0.78rem;
        color: var(--muted);
        margin-top: 2px;
    }
    .upcoming-item__badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 99px;
        flex-shrink: 0;
    }
    .badge--confirmed { background: var(--success-bg); color: var(--success); }
    .badge--pending   { background: var(--warning-bg); color: var(--warning); }
    .badge--completed { background: var(--muted-bg);   color: var(--muted); }

    .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 0.75rem;
    }

    /* empty state */
    .empty-upcoming {
        text-align: center;
        padding: 2rem;
        color: var(--muted);
        font-size: 0.875rem;
        background: var(--card);
        border: 1px dashed var(--border);
        border-radius: 1rem;
    }
    .empty-upcoming__icon { font-size: 2rem; margin-bottom: 0.5rem; }

    /* Responsive two-column layout */
    .calendar-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1024px) {
        .calendar-layout {
            grid-template-columns: 1fr 320px;
        }
    }

    .calendar-main {
        min-width: 0; /* Prevents overflow */
    }

    .calendar-sidebar {
        min-width: 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="cal-page">
<div class="container-custom">

    
    <div class="cal-header">
        <p class="cal-header__eyebrow">Dashboard · Calendar</p>
        <h1 class="cal-header__title">Your <em>stays</em> at a glance</h1>
        <p class="cal-header__sub">Track upcoming bookings, past adventures, and everything in between.</p>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card stat-card--total">
            <span class="stat-card__icon">🗓️</span>
            <div class="stat-card__value"><?php echo e($totalBookings ?? 0); ?></div>
            <div class="stat-card__label">Total Bookings</div>
        </div>
        <div class="stat-card stat-card--upcoming">
            <span class="stat-card__icon">✈️</span>
            <div class="stat-card__value"><?php echo e($upcomingCount ?? 0); ?></div>
            <div class="stat-card__label">Upcoming Stays</div>
        </div>
        <div class="stat-card stat-card--completed">
            <span class="stat-card__icon">✅</span>
            <div class="stat-card__value"><?php echo e($completedCount ?? 0); ?></div>
            <div class="stat-card__label">Completed</div>
        </div>
        <div class="stat-card stat-card--nights">
            <span class="stat-card__icon">🌙</span>
            <div class="stat-card__value"><?php echo e($totalNights ?? 0); ?></div>
            <div class="stat-card__label">Total Nights</div>
        </div>
    </div>

    
    <div class="calendar-layout">

        
        <div class="calendar-main">
            <div class="cal-card">
                <div class="cal-card__inner">
                    <div id="calendar"></div>
                </div>
                <div class="cal-legend">
                    <div class="cal-legend__item">
                        <span class="cal-legend__dot cal-legend__dot--confirmed"></span>
                        Confirmed
                    </div>
                    <div class="cal-legend__item">
                        <span class="cal-legend__dot cal-legend__dot--pending"></span>
                        Pending
                    </div>
                    <div class="cal-legend__item">
                        <span class="cal-legend__dot cal-legend__dot--completed"></span>
                        Completed
                    </div>
                </div>
            </div>
        </div>

        
        <div class="calendar-sidebar">
            <div class="cal-card">
                <div class="cal-card__inner">
                    <p class="section-label" style="margin-bottom: 1rem;">Upcoming stays</p>
                    <div id="upcoming-list">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const events = <?php echo json_encode($events ?? [], 15, 512) ?>;

    console.log('Events loaded:', events); // Debug log

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        events: events,
        eventClassNames: function(arg) {
            return ['fc-event-' + (arg.event.extendedProps.status ?? 'confirmed')];
        },
        eventClick: function (info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        height: 'auto',
        firstDay: 1,
        buttonText: { today: 'Today', month: 'Month', week: 'Week' },
        noEventsText: 'No bookings for this period'
    });

    calendar.render();

    // Build upcoming list
    const listContainer = document.getElementById('upcoming-list');
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Filter upcoming events (confirmed or pending, start date >= today)
    const upcoming = events
        .filter(e => {
            const eventDate = new Date(e.start);
            const status = e.extendedProps?.status;
            return eventDate >= today && (status === 'confirmed' || status === 'pending');
        })
        .sort((a, b) => new Date(a.start) - new Date(b.start))
        .slice(0, 5);

    if (upcoming.length === 0) {
        listContainer.innerHTML = `
            <div class="empty-upcoming">
                <div class="empty-upcoming__icon">🏖️</div>
                <p>No upcoming stays yet</p>
                <p style="font-size: 0.75rem; margin-top: 0.5rem;">Time to plan your next escape!</p>
            </div>`;
    } else {
        let listHtml = '';
        upcoming.forEach(function(ev) {
            const startDate = new Date(ev.start);
            const day = startDate.getDate();
            const mon = startDate.toLocaleString('en', { month: 'short' }).toUpperCase();
            const status = ev.extendedProps?.status ?? 'confirmed';
            const nights = ev.extendedProps?.nights ?? '';
            const property = ev.extendedProps?.property ?? ev.title;

            // Calculate nights if not provided
            let nightsText = '';
            if (nights) {
                nightsText = `${nights} night${nights > 1 ? 's' : ''}`;
            } else if (ev.end) {
                const endDate = new Date(ev.end);
                const nightsCount = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                nightsText = `${nightsCount} night${nightsCount > 1 ? 's' : ''}`;
            }

            listHtml += `
            <div class="upcoming-item">
                <div class="upcoming-item__date">
                    <span class="upcoming-item__date-day">${day}</span>
                    <span class="upcoming-item__date-mon">${mon}</span>
                </div>
                <div class="upcoming-item__info">
                    <div class="upcoming-item__name">${escapeHtml(property)}</div>
                    ${nightsText ? `<div class="upcoming-item__meta">${nightsText}</div>` : ''}
                </div>
                <span class="upcoming-item__badge badge--${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
            </div>`;
        });
        listContainer.innerHTML = listHtml;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/customer/calendar.blade.php ENDPATH**/ ?>