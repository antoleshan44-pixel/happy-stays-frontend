{{-- resources/views/layouts/partials/footer-customer.blade.php --}}
<footer style="
    background: #0a0f1e;
    color: #e5e7eb;
    font-family: 'Outfit', 'Inter', sans-serif;
    margin-top: 5rem;
">

    {{-- Top section --}}
    <div class="container-custom" style="padding-top: 4rem; padding-bottom: 3rem;">
        <div style="display: grid; grid-template-columns: 1fr; gap: 3rem;">

            {{-- Brand block --}}
            <div style="display: grid; grid-template-columns: 1fr; gap: 3rem;"
                 class="footer-grid">

                <div>
                    {{-- Logo / wordmark --}}
                    <div style="margin-bottom: 1rem;">
                        <span style="
                            font-family: 'Cormorant Garamond', 'Georgia', serif;
                            font-size: 1.75rem;
                            font-weight: 600;
                            color: #ffffff;
                            letter-spacing: -0.01em;
                        ">Eserian <em style="font-style:italic; color: #93a8f4;">Homes</em></span>
                    </div>
                    <p style="font-size: 0.85rem; color: #9ca3af; line-height: 1.7; max-width: 260px; font-weight: 300;">
                        Premium property experiences across Kenya's most breathtaking destinations.
                    </p>

                    {{-- Social icons --}}
                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        @foreach([
                            ['https://facebook.com', '<path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>'],
                            ['https://twitter.com', '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.6-5.484 4.94 4.94 0 00.917-2.978v-.096a9.98 9.98 0 002.16-2.239z"/>'],
                            ['https://instagram.com', '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                            ['https://linkedin.com', '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.225 0z"/>']
                        ] as [$href, $svgPath])
                        <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" style="
                            width: 36px; height: 36px;
                            border-radius: 50%;
                            border: 1px solid rgba(255,255,255,0.12);
                            display: flex; align-items: center; justify-content: center;
                            color: #9ca3af;
                            transition: border-color 0.2s, color 0.2s, background 0.2s;
                            text-decoration: none;
                        " onmouseover="this.style.borderColor='#93a8f4';this.style.color='#93a8f4'"
                           onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='#9ca3af'">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">{!! $svgPath !!}</svg>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Links columns --}}
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;" class="footer-links-grid">

                    {{-- Resources --}}
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 700;
                            letter-spacing: 0.14em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 1rem;
                        ">Resources</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.6rem;">
                            <li><a href="{{ route('customer.help.center') }}" class="footer-link">Help Center</a></li>
                            <li><a href="#" class="footer-link">Safety Guidance</a></li>
                            <li><a href="#" class="footer-link">Insurance Policy</a></li>
                            <li><a href="{{ route('customer.browse') }}" class="footer-link">Browse Properties</a></li>
                        </ul>
                    </div>

                    {{-- Company --}}
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 700;
                            letter-spacing: 0.14em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 1rem;
                        ">Company</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.6rem;">
                            <li><a href="{{ route('about.us') }}" class="footer-link">About Us</a></li>
                            <li><a href="#" class="footer-link">Careers</a></li>
                            <li><a href="#" class="footer-link">Newsroom</a></li>
                            <li><a href="#" class="footer-link">Contact</a></li>
                        </ul>
                    </div>

                    {{-- Legal --}}
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 700;
                            letter-spacing: 0.14em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 1rem;
                        ">Legal</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.6rem;">
                            <li><a href="#" class="footer-link">Privacy Policy</a></li>
                            <li><a href="#" class="footer-link">Terms of Service</a></li>
                            <li><a href="#" class="footer-link">Cookie Policy</a></li>
                            <li><a href="#" class="footer-link">Sitemap</a></li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Divider with destinations strip --}}
            <div style="
                border-top: 1px solid rgba(255,255,255,0.07);
                padding-top: 2rem;
            ">
                <p style="
                    font-size: 0.65rem; font-weight: 700;
                    letter-spacing: 0.14em; text-transform: uppercase;
                    color: #4b5563; margin-bottom: 1rem;
                ">Popular Destinations</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach(['Nairobi', 'Diani Beach', 'Mombasa', 'Nakuru', 'Nanyuki', 'Naivasha', 'Malindi', 'Amboseli', 'Kisumu', 'Samburu'] as $dest)
                    <a href="{{ route('customer.browse') }}?location={{ urlencode($dest) }}"
                       style="
                           font-size: 0.75rem; color: #6b7280;
                           padding: 0.3rem 0.75rem;
                           border: 1px solid rgba(255,255,255,0.07);
                           border-radius: 99px;
                           text-decoration: none;
                           transition: color 0.2s, border-color 0.2s;
                       "
                       onmouseover="this.style.color='#93a8f4';this.style.borderColor='rgba(147,168,244,0.3)'"
                       onmouseout="this.style.color='#6b7280';this.style.borderColor='rgba(255,255,255,0.07)'">
                        {{ $dest }}
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div style="border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="container-custom" style="
            padding-top: 1.25rem; padding-bottom: 1.25rem;
            display: flex; flex-wrap: wrap;
            align-items: center; justify-content: space-between;
            gap: 0.75rem;
        ">
            <span style="font-size: 0.78rem; color: #4b5563;">
                © 2026 Eserian Homes Ltd. All rights reserved.
            </span>
            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <span style="
                    display: inline-block; width: 6px; height: 6px;
                    border-radius: 50%; background: #10b981;
                "></span>
                <span style="font-size: 0.72rem; color: #4b5563;">All systems operational</span>
            </div>
        </div>
    </div>

</footer>

<style>
    .footer-link {
        font-size: 0.83rem;
        color: #9ca3af;
        text-decoration: none;
        font-weight: 400;
        transition: color 0.2s;
        display: inline-block;
    }
    .footer-link:hover { color: #ffffff; }

    @media (min-width: 768px) {
        .footer-grid {
            grid-template-columns: 280px 1fr !important;
        }
    }
    @media (max-width: 560px) {
        .footer-links-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
</style>
