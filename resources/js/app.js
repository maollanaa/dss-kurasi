import './bootstrap';
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Lucide Icons
    createIcons({ icons });

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