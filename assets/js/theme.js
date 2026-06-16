/**
 * HarvestIQ Global Theme Controller
 * Persists preference in localStorage under harvestiq-theme
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'harvestiq-theme';
    var root = document.documentElement;

    function getStoredTheme() {
        try {
            return localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
    }

    function setStoredTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) { /* ignore */ }
    }

    function applyTheme(theme) {
        var resolved = theme === 'dark' ? 'dark' : 'light';
        root.setAttribute('data-theme', resolved);
        updateToggleUI(resolved);
    }

    function updateToggleUI(theme) {
        document.querySelectorAll('[data-hiq-theme-toggle]').forEach(function (btn) {
            var isDark = theme === 'dark';
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            btn.classList.toggle('is-dark', isDark);
        });
    }

    function toggleTheme() {
        var current = root.getAttribute('data-theme') || 'light';
        var next = current === 'dark' ? 'light' : 'dark';
        setStoredTheme(next);
        applyTheme(next);
    }

    /* Apply before paint (also called inline in nav for FOUC prevention) */
    applyTheme(getStoredTheme() || 'light');

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-hiq-theme-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleTheme();
            });
        });
    });

    window.HarvestIQTheme = {
        toggle: toggleTheme,
        set: function (theme) {
            setStoredTheme(theme);
            applyTheme(theme);
        },
        get: function () {
            return root.getAttribute('data-theme') || 'light';
        }
    };
})();
