{{-- resources/views/owner/calendar.blade.php --}}
@extends('layouts.owner')

@section('title', 'Availability Calendar')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<style>
    :root{
        --primary:#0021c8;
        --primary-light:#eef2ff;
        --success:#16a34a;
        --success-light:#dcfce7;
        --warning:#f59e0b;
        --warning-light:#fef3c7;
        --danger:#dc2626;
        --danger-light:#fee2e2;

        --surface:#ffffff;
        --background:#f5f7fb;

        --border:#e5e7eb;
        --text:#111827;
        --muted:#6b7280;
    }

    body{
        background: var(--background);
    }

    .calendar-page{
        padding: 24px;
    }

    /* Header */
    .page-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:24px;
        flex-wrap:wrap;
    }

    .header-left h1{
        font-size:30px;
        font-weight:800;
        color:var(--text);
        margin-bottom:6px;
    }

    .header-left p{
        color:var(--muted);
        font-size:14px;
    }

    .header-actions{
        display:flex;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
    }

    .property-selector{
        display:flex;
        align-items:center;
        gap:10px;
        background:#fff;
        border:1px solid var(--border);
        border-radius:14px;
        padding:12px 18px;
        font-weight:600;
        color:var(--text);
    }

    .view-toggle{
        display:flex;
        align-items:center;
        background:#e5e7eb;
        padding:4px;
        border-radius:12px;
    }

    .view-btn{
        border:none;
        background:transparent;
        padding:10px 18px;
        border-radius:10px;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
        transition:0.2s ease;
        color:var(--muted);
    }

    .view-btn.active{
        background:#fff;
        color:var(--primary);
        box-shadow:0 2px 10px rgba(0,0,0,0.08);
    }

    /* Stats */
    .stats-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:18px;
        margin-bottom:24px;
    }

    .stat-card{
        background:var(--surface);
        border-radius:18px;
        padding:20px;
        border:1px solid var(--border);
        transition:0.2s ease;
    }

    .stat-card:hover{
        transform:translateY(-3px);
        box-shadow:0 10px 30px rgba(0,0,0,0.06);
    }

    .stat-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin-bottom:16px;
    }

    .stat-icon{
        width:46px;
        height:46px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .stat-icon.blue{
        background:#dbeafe;
        color:#2563eb;
    }

    .stat-icon.green{
        background:#dcfce7;
        color:#16a34a;
    }

    .stat-icon.orange{
        background:#ffedd5;
        color:#ea580c;
    }

    .stat-icon.purple{
        background:#ede9fe;
        color:#7c3aed;
    }

    .stat-value{
        font-size:28px;
        font-weight:800;
        color:var(--text);
        line-height:1;
        margin-bottom:6px;
    }

    .stat-label{
        font-size:13px;
        color:var(--muted);
        font-weight:500;
    }

    /* Main Layout */
    .calendar-layout{
        display:grid;
        grid-template-columns:1fr 360px;
        gap:24px;
        align-items:start;
    }

    /* Calendar Card */
    .calendar-card{
        background:#fff;
        border-radius:24px;
        border:1px solid var(--border);
        overflow:hidden;
        box-shadow:0 4px 30px rgba(0,0,0,0.04);
    }

    .calendar-top{
        padding:22px 24px;
        border-bottom:1px solid var(--border);
        display:flex;
        align-items:center;
        justify-content:space-between;
        flex-wrap:wrap;
        gap:16px;
    }

    .calendar-top h2{
        font-size:22px;
        font-weight:700;
        color:var(--text);
    }

    .calendar-controls{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .calendar-btn{
        width:42px;
        height:42px;
        border-radius:12px;
        border:1px solid var(--border);
        background:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:0.2s ease;
    }

    .calendar-btn:hover{
        background:var(--primary-light);
        border-color:var(--primary);
        color:var(--primary);
    }

    #calendar{
        padding:18px;
    }

    /* Sidebar */
    .sidebar-card{
        background:#fff;
        border-radius:24px;
        border:1px solid var(--border);
        padding:24px;
        box-shadow:0 4px 30px rgba(0,0,0,0.04);
        margin-bottom:20px;
    }

    .sidebar-title{
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
    }

    .sidebar-title h3{
        font-size:18px;
        font-weight:700;
        color:var(--text);
    }

    /* Legend */
    .legend-list{
        display:flex;
        flex-direction:column;
        gap:14px;
    }

    .legend-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
    }

    .legend-left{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .legend-dot{
        width:12px;
        height:12px;
        border-radius:999px;
    }

    .legend-dot.confirmed{
        background:var(--success);
    }

    .legend-dot.pending{
        background:var(--warning);
    }

    .legend-dot.blocked{
        background:var(--danger);
    }

    .legend-dot.available{
        background:#d1d5db;
    }

    .legend-item span{
        font-size:14px;
        color:var(--muted);
    }

    /* Upcoming */
    .booking-list{
        display:flex;
        flex-direction:column;
        gap:14px;
    }

    .booking-card{
        border:1px solid var(--border);
        border-radius:18px;
        padding:16px;
        transition:0.2s ease;
    }

    .booking-card:hover{
        border-color:var(--primary);
        background:var(--primary-light);
    }

    .booking-header{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        margin-bottom:10px;
        gap:10px;
    }

    .booking-guest{
        font-size:15px;
        font-weight:700;
        color:var(--text);
        margin-bottom:4px;
    }

    .booking-date{
        font-size:13px;
        color:var(--muted);
    }

    .booking-status{
        font-size:11px;
        font-weight:700;
        padding:6px 10px;
        border-radius:999px;
        text-transform:uppercase;
    }

    .status-confirmed{
        background:var(--success-light);
        color:var(--success);
    }

    .status-pending{
        background:var(--warning-light);
        color:#b45309;
    }

    .status-blocked{
        background:var(--danger-light);
        color:var(--danger);
    }

    .booking-price{
        font-size:15px;
        font-weight:700;
        color:var(--primary);
    }

    .empty-state{
        text-align:center;
        padding:40px 20px;
        color:var(--muted);
    }

    /* FullCalendar */
    .fc{
        font-family:inherit !important;
    }

    .fc .fc-toolbar{
        display:none !important;
    }

    .fc-theme-standard td,
    .fc-theme-standard th{
        border:1px solid #edf0f5 !important;
    }

    .fc .fc-col-header-cell{
        background:#f9fafb;
        padding:10px 0;
    }

    .fc .fc-col-header-cell-cushion{
        font-size:12px;
        font-weight:700;
        text-transform:uppercase;
        color:var(--muted);
        text-decoration:none !important;
        letter-spacing:0.06em;
    }

    .fc .fc-daygrid-day{
        transition:0.2s ease;
    }

    .fc .fc-daygrid-day:hover{
        background:#f8fbff;
    }

    .fc .fc-daygrid-day-number{
        font-size:14px;
        font-weight:600;
        color:var(--text);
        text-decoration:none !important;
        padding:10px;
    }

    .fc .fc-day-today{
        background:#eef4ff !important;
    }

    .fc .fc-day-today .fc-daygrid-day-number{
        background:var(--primary);
        color:white !important;
        width:32px;
        height:32px;
        border-radius:999px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:6px;
    }

    .fc-daygrid-event{
        border:none !important;
        border-radius:8px !important;
        padding:4px 8px !important;
        font-size:11px !important;
        font-weight:700 !important;
    }

    .fc-event-confirmed{
        background:var(--success) !important;
        color:white !important;
    }

    .fc-event-pending{
        background:var(--warning) !important;
        color:white !important;
    }

    .fc-event-blocked{
        background:var(--danger) !important;
        color:white !important;
    }

    @media(max-width:1100px){
        .calendar-layout{
            grid-template-columns:1fr;
        }
    }

    @media(max-width:768px){
        .calendar-page{
            padding:16px;
        }

        .stats-grid{
            grid-template-columns:repeat(2,1fr);
        }

        .header-left h1{
            font-size:24px;
        }
    }
</style>
@endsection

@section('content')
<div class="calendar-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="header-left">
            <h1>Availability Calendar</h1>
            <p>
                Manage bookings, blocked dates, and availability for
                <strong>{{ $property->title ?? 'your property' }}</strong>
            </p>
        </div>

        <div class="header-actions">

            <div class="property-selector">
                <span class="material-symbols-outlined">apartment</span>
                <span>{{ $property->title ?? 'Select Property' }}</span>
                <span class="material-symbols-outlined">expand_more</span>
            </div>

            <div class="view-toggle">
                <button class="view-btn active" id="monthViewBtn">Month</button>
                <button class="view-btn" id="weekViewBtn">Week</button>
            </div>

        </div>

    </div>

    {{-- Stats --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ $totalProperties ?? 0 }}</div>
                    <div class="stat-label">Properties</div>
                </div>

                <div class="stat-icon blue">
                    <span class="material-symbols-outlined">home_work</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ $upcomingBookingsCount ?? 0 }}</div>
                    <div class="stat-label">Upcoming Bookings</div>
                </div>

                <div class="stat-icon green">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-value">{{ $completedBookingsCount ?? 0 }}</div>
                    <div class="stat-label">Completed Stays</div>
                </div>

                <div class="stat-icon purple">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-value">
                        KES {{ number_format($totalEarnings ?? 0) }}
                    </div>
                    <div class="stat-label">Total Earnings</div>
                </div>

                <div class="stat-icon orange">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Layout --}}
    <div class="calendar-layout">

        {{-- Calendar --}}
        <div class="calendar-card">

            <div class="calendar-top">

                <h2 id="calendarTitle">Calendar</h2>

                <div class="calendar-controls">

                    <button class="calendar-btn" id="prevBtn">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>

                    <button class="calendar-btn" id="todayBtn">
                        <span class="material-symbols-outlined">today</span>
                    </button>

                    <button class="calendar-btn" id="nextBtn">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>

                </div>

            </div>

            <div id="calendar"></div>

        </div>

        {{-- Sidebar --}}
        <div>

            {{-- Legend --}}
            <div class="sidebar-card">

                <div class="sidebar-title">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <h3>Calendar Legend</h3>
                </div>

                <div class="legend-list">

                    <div class="legend-item">
                        <div class="legend-left">
                            <span class="legend-dot confirmed"></span>
                            <span>Confirmed Booking</span>
                        </div>
                    </div>

                    <div class="legend-item">
                        <div class="legend-left">
                            <span class="legend-dot pending"></span>
                            <span>Pending Request</span>
                        </div>
                    </div>

                    <div class="legend-item">
                        <div class="legend-left">
                            <span class="legend-dot blocked"></span>
                            <span>Blocked Dates</span>
                        </div>
                    </div>

                    <div class="legend-item">
                        <div class="legend-left">
                            <span class="legend-dot available"></span>
                            <span>Available</span>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Upcoming Bookings --}}
            <div class="sidebar-card">

                <div class="sidebar-title">
                    <span class="material-symbols-outlined text-primary">upcoming</span>
                    <h3>Upcoming Bookings</h3>
                </div>

                <div class="booking-list" id="upcomingBookings"></div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const events = @json($events ?? []);

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        headerToolbar: false,

        firstDay: 0,

        height: 'auto',

        events: events,

        eventClassNames: function(info){
            return ['fc-event-' + (info.event.extendedProps.status || 'confirmed')];
        },

        datesSet: function(info){
            document.getElementById('calendarTitle').innerText =
                info.view.title;
        },

        eventClick: function(info){

            if(info.event.extendedProps.bookingId){
                window.location.href =
                    '/owner/bookings/' +
                    info.event.extendedProps.bookingId;
            }
        }

    });

    calendar.render();

    // Navigation
    document.getElementById('prevBtn').addEventListener('click', () => {
        calendar.prev();
    });

    document.getElementById('nextBtn').addEventListener('click', () => {
        calendar.next();
    });

    document.getElementById('todayBtn').addEventListener('click', () => {
        calendar.today();
    });

    // Views
    document.getElementById('monthViewBtn').addEventListener('click', function(){

        calendar.changeView('dayGridMonth');

        this.classList.add('active');

        document.getElementById('weekViewBtn')
            .classList.remove('active');
    });

    document.getElementById('weekViewBtn').addEventListener('click', function(){

        calendar.changeView('timeGridWeek');

        this.classList.add('active');

        document.getElementById('monthViewBtn')
            .classList.remove('active');
    });

    // Upcoming Bookings
    const bookingContainer =
        document.getElementById('upcomingBookings');

    const today = new Date();

    const upcoming = events
        .filter(event => {

            const start = new Date(event.start);

            return (
                start >= today &&
                (
                    event.extendedProps?.status === 'confirmed' ||
                    event.extendedProps?.status === 'pending'
                )
            );

        })
        .sort((a,b) => new Date(a.start) - new Date(b.start))
        .slice(0,5);

    if(upcoming.length === 0){

        bookingContainer.innerHTML = `
            <div class="empty-state">
                <span class="material-symbols-outlined" style="font-size:48px;">
                    event_available
                </span>
                <p>No upcoming bookings</p>
            </div>
        `;

    } else {

        let html = '';

        upcoming.forEach(booking => {

            const start = new Date(booking.start);

            const end = new Date(booking.end);

            const status =
                booking.extendedProps?.status || 'confirmed';

            html += `
                <div class="booking-card">

                    <div class="booking-header">

                        <div>

                            <div class="booking-guest">
                                ${escapeHtml(
                                    booking.extendedProps?.guest ||
                                    booking.title
                                )}
                            </div>

                            <div class="booking-date">
                                ${formatDate(start)}
                                -
                                ${formatDate(end)}
                            </div>

                        </div>

                        <span class="booking-status status-${status}">
                            ${status}
                        </span>

                    </div>

                    ${
                        booking.extendedProps?.price
                        ?
                        `
                        <div class="booking-price">
                            KES ${Number(
                                booking.extendedProps.price
                            ).toLocaleString()}
                        </div>
                        `
                        :
                        ''
                    }

                </div>
            `;
        });

        bookingContainer.innerHTML = html;
    }

    function formatDate(date){

        return date.toLocaleDateString('en-US', {
            month:'short',
            day:'numeric',
            year:'numeric'
        });
    }

    function escapeHtml(text){

        const div = document.createElement('div');

        div.textContent = text || '';

        return div.innerHTML;
    }

});
</script>
@endsection
