{{-- resources/views/layouts/partials/footer-owner.blade.php --}}
<footer class="owner-footer">
    <div class="owner-footer-inner">

        {{-- ── Top grid ── --}}
        <div class="owner-footer-grid">

            {{-- Brand --}}
            <div class="owner-footer-brand">
                <div class="owner-footer-logo">
                    <span class="logo-icon material-symbols-outlined">travel_explore</span>
                    <span class="logo-text">Eserian <em>Homes</em></span>
                </div>
                <p class="brand-tagline">
                    Premium property management for serious hosts. Streamline your portfolio and grow your earnings.
                </p>
                <div class="owner-footer-badges">
                    <span class="footer-badge">
                        <span class="material-symbols-outlined badge-icon">verified_user</span>
                        Verified Platform
                    </span>
                    <span class="footer-badge">
                        <span class="material-symbols-outlined badge-icon">support_agent</span>
                        24/7 Support
                    </span>
                </div>
            </div>

            {{-- Platform links --}}
            <div class="owner-footer-col">
                <h4 class="footer-col-heading">Platform</h4>
                <ul class="footer-link-list">
                    <li><a href="{{ route('owner.dashboard') }}" class="footer-link">Dashboard</a></li>
                    <li><a href="{{ route('owner.properties') }}" class="footer-link">My Properties</a></li>
                    <li><a href="{{ route('owner.earnings') }}" class="footer-link">Earnings</a></li>
                    <li><a href="{{ route('owner.property.create') }}" class="footer-link">List a Property</a></li>
                    <li><a href="{{ route('customer.browse') }}" class="footer-link">Explore Listings</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div class="owner-footer-col">
                <h4 class="footer-col-heading">Resources</h4>
                <ul class="footer-link-list">
                    <li><a href="{{ route('customer.help.center') }}" class="footer-link">Help Center</a></li>
                    <li><a href="#" class="footer-link">Hosting Guide</a></li>
                    <li><a href="#" class="footer-link">Pricing Tips</a></li>
                    <li><a href="#" class="footer-link">Analytics Guide</a></li>
                    <li><a href="#" class="footer-link">Community Forum</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="owner-footer-col">
                <h4 class="footer-col-heading">Company</h4>
                <ul class="footer-link-list">
                    <li><a href="{{ route('about.us') }}" class="footer-link">About Us</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link">Terms of Service</a></li>
                    <li><a href="#" class="footer-link">Host Guarantee</a></li>
                    <li><a href="#" class="footer-link">Cookie Policy</a></li>
                </ul>
            </div>

        </div>

        {{-- ── Divider ── --}}
        <div class="owner-footer-divider"></div>

        {{-- ── Bottom bar ── --}}
        <div class="owner-footer-bottom">
            <span class="footer-copyright">
                © {{ date('Y') }} Eserian Homes Ltd. All rights reserved.
            </span>
            <div class="footer-status-row">
                <span class="footer-status-chip">
                    <span class="status-dot-live"></span>
                    All systems operational
                </span>
                <span class="footer-status-chip">
                    <span class="material-symbols-outlined" style="font-size:0.85rem;color:#4ade80">lock</span>
                    SSL Secured
                </span>
            </div>
        </div>

    </div>
</footer>

<style>
/* ── Owner Footer ── */
.owner-footer {
    background: #0c1120;
    border-top: 1px solid rgba(255,255,255,0.05);
    margin-top: 5rem;
    font-family: 'DM Sans', system-ui, sans-serif;
}

.owner-footer-inner {
    max-width: 1240px;
    margin-inline: auto;
    padding: 3rem 1.5rem 2rem;
}

/* Grid */
.owner-footer-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr;
    gap: 2.5rem;
    margin-bottom: 2.5rem;
}
@media (max-width: 900px) {
    .owner-footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
}
@media (max-width: 560px) {
    .owner-footer-grid { grid-template-columns: 1fr; gap: 1.5rem; }
}

/* Brand */
.owner-footer-logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.logo-icon {
    font-size: 1.4rem;
    color: #93a8f4;
    font-variation-settings: 'FILL' 1;
}
.logo-text {
    font-family: 'Fraunces', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: -0.01em;
}
.logo-text em {
    font-style: italic;
    color: #93a8f4;
    font-weight: 400;
}
.brand-tagline {
    font-size: 0.8rem;
    color: #6b7280;
    line-height: 1.6;
    max-width: 260px;
    margin-bottom: 1rem;
}
.owner-footer-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.footer-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    font-weight: 500;
    color: #9ca3af;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    padding: 0.25rem 0.6rem;
    border-radius: 9999px;
    letter-spacing: 0.01em;
}
.badge-icon { font-size: 0.8rem; color: #93a8f4; }

/* Columns */
.footer-col-heading {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #4b5563;
    margin-bottom: 0.875rem;
}
.footer-link-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}
.footer-link {
    font-size: 0.82rem;
    color: #9ca3af;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: color 0.18s, padding-left 0.18s;
}
.footer-link:hover {
    color: #c7d3fb;
    padding-left: 0.25rem;
}

/* Divider */
.owner-footer-divider {
    height: 1px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 1.5rem;
}

/* Bottom bar */
.owner-footer-bottom {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.footer-copyright {
    font-size: 0.72rem;
    color: #374151;
}
.footer-status-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.footer-status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    color: #4b5563;
}
.status-dot-live {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4ade80;
    display: inline-block;
    animation: pulse-live 2.5s ease-in-out infinite;
}
@keyframes pulse-live {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(74,222,128,0.4); }
    50%       { opacity: 0.8; box-shadow: 0 0 0 4px rgba(74,222,128,0); }
}
</style>
