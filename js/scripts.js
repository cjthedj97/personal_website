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

    const heroTerminal = document.getElementById('heroTerminal');
    const heroTerminalOutput = document.getElementById('heroTerminalOutput');
    if (heroTerminal && heroTerminalOutput) {
        const normalSequence = [
            'cj@home:~$ whoami',
            'cj',
            'cj@home:~$ uptime',
            'up since 1997, still learning',
            'cj@home:~$ uname -a',
            'Linux personal-web x86_64 GNU/Linux',
            'cj@home:~$ history | tail -2',
            'make',
            './launch',
            'cj@home:~$ ./introduction',
            'I build useful things.',
            'I fix weird problems.',
            'I keep systems running.'
        ].join('\n');

        const easterEggSequence = [
            'cj@home:~$ history | tail -5',
            '42  whoami',
            '43  uptime',
            '44  uname -a',
            '45  ls ~/projects',
            '46  sudo make me a sandwich',
            'sudo: command not found',
            'cj@home:~$ fortune | cowsay',
            ' ____________________________',
            '< hello from the other shell >',
            ' ----------------------------',
            '        \\   ^__^',
            '         \\  (oo)\\_______',
            '            (__)\\       )\/\\',
            '                ||----w |',
            '                ||     ||',
            'cj@home:~$ ./launch --extra',
            'warning: rabbit hole detected',
            'root access denied',
            'secret unlocked: side quests'
        ].join('\n');

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let activeSequenceToken = 0;
        let activeTimer = null;

        const typeSequence = sequence => {
            activeSequenceToken += 1;
            const sequenceToken = activeSequenceToken;
            if (activeTimer) {
                window.clearTimeout(activeTimer);
                activeTimer = null;
            }

            if (prefersReducedMotion) {
                heroTerminalOutput.textContent = sequence;
                return;
            }

            let index = 0;
            const step = () => {
                if (sequenceToken !== activeSequenceToken) {
                    return;
                }
                heroTerminalOutput.textContent = sequence.slice(0, index);
                index += 1;
                if (index <= sequence.length) {
                    activeTimer = window.setTimeout(step, sequence[index - 2] === '\n' ? 180 : 24);
                } else {
                    activeTimer = null;
                }
            };

            heroTerminalOutput.textContent = '';
            step();
        };

        const launchEasterEgg = () => {
            typeSequence(easterEggSequence);
        };

        heroTerminal.addEventListener('click', launchEasterEgg);
        heroTerminal.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                launchEasterEgg();
            }
        });

        typeSequence(normalSequence);
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
