// resources/js/custom.js

import $ from 'jquery';
window.$ = window.jQuery = $;

$(function () {

    console.log("Custom.js loaded");

    /**
     * =========================
     * Sticky Header
     * =========================
     */
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 50) {
            $('header').addClass('sticky');
        } else {
            $('header').removeClass('sticky');
        }
    });

    /**
     * =========================
     * Mobile Menu Toggle
     * =========================
     */
    $('.menu-toggle').on('click', function () {
        $('.mobile-menu').toggleClass('active');
    });

    /**
     * =========================
     * Back to Top
     * =========================
     */
    let btn = $('#backToTop');

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 300) {
            btn.fadeIn();
        } else {
            btn.fadeOut();
        }
    });

    btn.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 600);
    });

});