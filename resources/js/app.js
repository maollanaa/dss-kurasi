import './bootstrap';
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Lucide Icons
    createIcons({ icons });

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

    // Mobile: lock body scroll when sidebar is open
    const sidebarMenu = document.getElementById('sidebarMenu');
    if (sidebarMenu && window.jQuery) {
        $(sidebarMenu).on('show.bs.collapse', function () {
            if (window.innerWidth < 768) {
                document.body.style.overflow = 'hidden';
            }
        });
        $(sidebarMenu).on('hide.bs.collapse', function () {
            document.body.style.overflow = '';
        });
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