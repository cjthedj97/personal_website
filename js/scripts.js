/*!
* Start Bootstrap - Freelancer v7.0.7 (https://startbootstrap.com/theme/freelancer)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-freelancer/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle ? themeToggle.querySelector('.theme-toggle__icon i') : null;
    const root = document.documentElement;

    function setTheme(theme) {
        root.dataset.theme = theme;
        root.style.colorScheme = theme;
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {}
        if (themeToggle) {
            themeToggle.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
        }
        if (themeIcon) {
            themeIcon.className = theme === 'light' ? 'fas fa-sun' : 'fas fa-moon';
        }
        const themeColor = document.querySelector('meta[name="theme-color"]');
        if (themeColor) {
            const computedThemeColor = getComputedStyle(root).getPropertyValue('--site-theme-color').trim();
            if (computedThemeColor) {
                themeColor.setAttribute('content', computedThemeColor);
            }
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const nextTheme = root.dataset.theme === 'light' ? 'dark' : 'light';
            setTheme(nextTheme);
        });
        setTheme(root.dataset.theme || 'dark');
    }

    const heroTerminalOutput = document.getElementById('heroTerminalOutput');
    const heroTerminalTitle = document.getElementById('heroTerminalTitle');
    if (heroTerminalOutput && heroTerminalTitle) {
        const terminalText = [
            'cj@home:~$ whoami',
            'cj',
            'cj@home:~$ uptime',
            'up since 1997, still learning',
            'cj@home:~$ uname -a',
            'Linux personal-web x86_64 GNU/Linux',
            'cj@home:~$ history | tail -5',
            'dig',
            'curl',
            'ssh',
            'make',
            './launch',
            'cj@home:~$ ./launch',
            'Welcome to /home/cj'
        ].join('\n');

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            heroTerminalOutput.textContent = terminalText;
        } else {
            heroTerminalTitle.textContent = 'Initializing...';
            let index = 0;
            const typeTerminalText = () => {
                heroTerminalOutput.textContent = terminalText.slice(0, index);
                index += 1;
                if (index <= terminalText.length) {
                    window.setTimeout(typeTerminalText, terminalText[index - 2] === '\n' ? 180 : 24);
                } else {
                    heroTerminalTitle.textContent = 'Welcome to /home/cj';
                }
            };
            typeTerminalText();
        }
    }

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        // Check if the nav item is not a dropdown toggle before adding the click event listener
        if (!responsiveNavItem.classList.contains('dropdown-toggle')) {
            responsiveNavItem.addEventListener('click', () => {
                if (window.getComputedStyle(navbarToggler).display !== 'none') {
                    navbarToggler.click();
                }
            });
        }
    });
});
