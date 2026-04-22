import './bootstrap';
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Lucide Icons
    createIcons({ icons });
    window.lucide = { createIcons, icons };

    // Sidebar Toggle Logic — Desktop only (lg: ≥992px)
    // Tablet (md: 768–991px) is auto-minimized via CSS, no JS needed
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            // Only allow manual toggle on desktop
            if (window.innerWidth < 992) return;

            const isMinimized = document.documentElement.classList.toggle('sidebar-minimized');
            localStorage.setItem('sidebar-minimized', isMinimized);

            const icon = document.getElementById('toggleIcon');
            if (icon) {
                icon.setAttribute('data-lucide', isMinimized ? 'chevron-right' : 'chevron-left');
                createIcons({ icons });
            }
        });

        // Restore icon on load for desktop
        if (window.innerWidth >= 992) {
            const icon = document.getElementById('toggleIcon');
            if (icon && document.documentElement.classList.contains('sidebar-minimized')) {
                icon.setAttribute('data-lucide', 'chevron-right');
                createIcons({ icons });
            }
        }
    }

    // Mobile: toggle sidebar via class (smooth CSS translateX, no Bootstrap collapse)
    const sidebarMenu   = document.getElementById('sidebarMenu');
    const sidebarClose  = document.getElementById('sidebarClose');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    function openMobileSidebar() {
        if (!sidebarMenu) return;
        sidebarMenu.classList.add('sidebar-open');
        document.body.style.overflow = 'hidden';
        // Show backdrop: set display first, then opacity on next frame
        if (sidebarBackdrop) {
            sidebarBackdrop.style.display = 'block';
            requestAnimationFrame(() => sidebarBackdrop.classList.add('backdrop-visible'));
        }
    }

    function closeMobileSidebar() {
        if (!sidebarMenu) return;
        sidebarMenu.classList.remove('sidebar-open');
        document.body.style.overflow = '';
        if (sidebarBackdrop) {
            sidebarBackdrop.classList.remove('backdrop-visible');
            // Hide after transition ends
            sidebarBackdrop.addEventListener('transitionend', () => {
                if (!sidebarBackdrop.classList.contains('backdrop-visible')) {
                    sidebarBackdrop.style.display = 'none';
                }
            }, { once: true });
        }
    }

    // The navbar hamburger button opens the sidebar on mobile
    const navbarToggler = document.querySelector('.navbar-toggler[data-target="#sidebarMenu"], [data-sidebar-open]');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', openMobileSidebar);
    }

    // Close button inside sidebar
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeMobileSidebar);
    }

    // Clicking the backdrop also closes
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
    }

    if (typeof AOS !== 'undefined') {
        AOS.init();
    }
    // ... rest of the code

    if (window.jQuery) {
        $('.select2').each(function () {
            $(this).select2({
                theme: 'bootstrap4',
                width: '100%',
            });
        });

        $('.datatable').each(function () {
            $(this).DataTable();
        });

        $('.mask-date').each(function () {
            $(this).mask('00/00/0000');
        });
    }

    if (document.querySelector('.default-swiper') && typeof Swiper !== 'undefined') {
        new Swiper('.default-swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
        });
    }
});