// resources/js/app.js

/**
 * ===============================
 * 1. Load jQuery FIRST + Globalize
 * ===============================
 */
// resources/js/app.js

import $ from 'jquery';

window.$ = window.jQuery = $;

// FORCE load AFTER global attach
import 'slick-carousel';
import select2 from 'select2';
import 'blazy';
import 'masonry-layout';
import { Fancybox } from '@fancyapps/ui';

window.Fancybox = Fancybox;

new select2();

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/**
 * ===============================
 * 5. Load Custom Logic
 * ===============================
 */
import './custom';
import './script/main'
import './script/home-carousel'
import './script/ajax'
import './script/inlt'
import './script/property-page'
import './script/slick'
import './script/aggregation-search'
import './script/map-display'
import './script/map-individual'
import './script/map-leaflet-property'
import './script/map-sales'
import './script/shortlist'

$(() => {
    // smooth header
    const header = document.getElementById("smooth-header");
    let lastScroll = window.scrollY;
    let ticking = false;
    let isHidden = false;

    function updateHeader() {
        const current = window.scrollY;
        const diff = current - lastScroll;

        const scrollDown = diff > 10;
        const scrollUp = diff < -10;

        if (scrollDown && !isHidden && current > 100) {
            header.classList.add("hidden");
            isHidden = true;
        } else if (scrollUp && isHidden) {
            header.classList.remove("hidden");
            isHidden = false;
        }

        lastScroll = current;
        ticking = false;
    }

    window.addEventListener(
        "scroll",
        () => {
            if (!ticking) {
                window.requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }, {
            passive: true
        }
    );

    // end here

    if (document.getElementById('profileTabs') && document.getElementById('arrow-left') && document.getElementById('arrow-right')) {
        const container = document.getElementById('profileTabs');
        const leftArrow = document.getElementById('arrow-left');
        const rightArrow = document.getElementById('arrow-right');
        const swipToRight = document.getElementById('swiptoright');

        const scrollAmount = 200;
        const mobileBreakpoint = 768; // px

        function updateArrows() {

            const isOverflowing = container.scrollWidth > container.clientWidth;

            // ❌ Hide arrows completely on mobile
            if (window.innerWidth < mobileBreakpoint) {
                leftArrow.style.display = 'none';
                rightArrow.style.display = 'none';
                // ✅ Show swipe hint only if overflow exists
                swipToRight.style.display = isOverflowing ? 'block' : 'none';

                return;
            }

            if (!isOverflowing) {
                leftArrow.style.display = 'none';
                rightArrow.style.display = 'none';
                return;
            }

            leftArrow.style.display = container.scrollLeft > 0 ? 'flex' : 'none';
            rightArrow.style.display =
                container.scrollLeft + container.clientWidth < container.scrollWidth ?
                'flex' :
                'none';
        }

        leftArrow.addEventListener('click', (e) => {
            e.preventDefault();
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        rightArrow.addEventListener('click', (e) => {
            e.preventDefault();
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        container.addEventListener('scroll', updateArrows);
        window.addEventListener('resize', updateArrows);

        // Initial check
        updateArrows();

        $('.select-pw').on('select2:open', function() {
            const dropdown = $('.select2-dropdown');

            dropdown
                .removeClass('select2-dropdown--above')
                .addClass('select2-dropdown--below')
                .css({
                    top: '100%',
                    bottom: 'auto'
                });
        });
    }

    document.querySelectorAll('[name="telephone"]').forEach((input) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+ ]/g, '');
        });
    });
});


// custom modal js 

document.addEventListener('DOMContentLoaded', () => {

    console.log('Property Inquiry Modal initialized');

    const modalEl = document.getElementById('enquerymodalmain');
    if (!modalEl || typeof bootstrap === 'undefined') {
        console.error('Bootstrap or modal missing');
        return;
    }

    // =============================
    // SINGLE MODAL INSTANCE
    // =============================
    const modal = new bootstrap.Modal(modalEl);

    // =============================
    // AUTO OPEN (once per session)
    // =============================
    setTimeout(() => {
        modal.show();
    }, 40000);
    // if (!sessionStorage.getItem('propertyInquiryModalShown')) {
    //     sessionStorage.setItem('propertyInquiryModalShown', 'true');
    // }

    // =============================
    // FIX ARIA / FOCUS ISSUE
    // =============================
    modalEl.addEventListener('hide.bs.modal', () => {

        if (document.activeElement) {
            document.activeElement.blur();
        }

        document.body.focus();
    });

    // =============================
    // SELECT2 INIT (SAFE)
    // =============================
    if (window.jQuery && $.fn.select2) {

        const $selects = $('#propertyInquiryModal .modal-select');

        if ($selects.length && !$selects.hasClass('select2-hidden-accessible')) {
            $selects.select2({
                minimumResultsForSearch: 5,
                width: '100%'
            });
        }

        $('#modal_location_list').on('change', function () {

            const country = this.value;
            if (!country) return;

            fetch('/ajax/get-areas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ country })
            })
            .then(res => res.json())
            .then(data => {

                const areaSelect = document.getElementById('modal_area_list');
                if (!areaSelect) return;

                areaSelect.innerHTML = '';

                Object.entries(data).forEach(([key, value]) => {
                    areaSelect.insertAdjacentHTML('beforeend',
                        `<option value="${key}">${value}</option>`
                    );
                });

                $('#modal_area_list').trigger('change');

            });

        });
    }

    // =============================
    // WHATSAPP
    // =============================
    window.sendWhatsAppFromModal = () => {

        const phone = "+971585365111";

        const message = encodeURIComponent(
            "Hello Tereza Estates! I would like more information about properties.\n\nInterested in available options."
        );

        window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
    };

    // =============================
    // PHONE VALIDATION
    // =============================
    const telephoneInput = document.getElementById('telephone-input');
    const submitButton = document.getElementById('btn-submit-inquiry');
    const warningElement = document.getElementById('phone-warning');

    function validatePhone(phone = '') {

        const valid = phone.startsWith('+') && /^\+[0-9]+$/.test(phone);

        if (!valid) {
            warningElement?.style.setProperty('display', 'block');
            submitButton && (submitButton.disabled = true);
        } else {
            warningElement?.style.setProperty('display', 'none');
            submitButton && (submitButton.disabled = false);
        }

        return valid;
    }

    if (telephoneInput) {
        telephoneInput.addEventListener('input', e => validatePhone(e.target.value));
        telephoneInput.addEventListener('blur', e => validatePhone(e.target.value));
    }

    // =============================
    // FORM SUBMIT
    // =============================
    const inquiryForm = document.getElementById('modal-inquiry-form');

    if (inquiryForm) {

        inquiryForm.addEventListener('submit', async (e) => {

            e.preventDefault();

            if (!validatePhone(telephoneInput?.value)) return;

            const submitBtn = document.getElementById('btn-submit-inquiry');
            const responseDiv = document.getElementById('form-response-message');

            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = 'Submitting...';
            submitBtn.disabled = true;

            try {

                const res = await fetch(inquiryForm.action, {
                    method: 'POST',
                    body: new FormData(inquiryForm),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {

                    responseDiv.innerHTML =
                        `<div class="alert alert-success">${data.message}</div>`;

                    inquiryForm.reset();

                    setTimeout(() => modal.hide(), 3000);

                } else {

                    responseDiv.innerHTML =
                        `<div class="alert alert-danger">${data.message || 'Error occurred'}</div>`;
                }

            } catch (err) {

                console.error(err);

                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }

        });
    }

});

$(() => {
    Fancybox.bind('[data-fancybox]', {
        backFocus: true,
    });
});
