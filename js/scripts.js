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
        const promptParts = {
            user: 'cj',
            host: 'home',
            path: '~/personal-web',
            symbol: '$'
        };

        const escapeHtml = value => value
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const makeCommandLine = command => {
            return `<span class="hero-terminal__line hero-terminal__line--command"><span class="hero-terminal__prompt"><span class="hero-terminal__prompt-part hero-terminal__prompt-part--user">${escapeHtml(promptParts.user)}</span><span class="hero-terminal__prompt-part hero-terminal__prompt-part--symbol">@</span><span class="hero-terminal__prompt-part hero-terminal__prompt-part--host">${escapeHtml(promptParts.host)}</span><span class="hero-terminal__prompt-part hero-terminal__prompt-part--separator">:</span><span class="hero-terminal__prompt-part hero-terminal__prompt-part--path">${escapeHtml(promptParts.path)}</span><span class="hero-terminal__prompt-part hero-terminal__prompt-part--symbol">${escapeHtml(promptParts.symbol)}</span></span><span class="hero-terminal__command"> ${escapeHtml(command)}</span></span>`;
        };

        const makeOutputLine = text => `<span class="hero-terminal__line hero-terminal__line--output"><span class="hero-terminal__output">${escapeHtml(text)}</span></span>`;

        const makeFingerLine = text => `<span class="hero-terminal__line hero-terminal__line--finger"><span class="hero-terminal__finger">${escapeHtml(text)}</span></span>`;

        const makeBlankLine = () => '<span class="hero-terminal__line hero-terminal__line--blank">&nbsp;</span>';

        const normalSequence = [
            { type: 'command', text: 'whoami' },
            { type: 'output', text: 'cj' },
            { type: 'command', text: 'uptime' },
            { type: 'output', text: 'up since 1997, still learning' },
            { type: 'command', text: 'uname -a' },
            { type: 'output', text: 'Linux personal-web x86_64 GNU/Linux' },
            { type: 'command', text: 'history | tail -2' },
            { type: 'output', text: 'make' },
            { type: 'output', text: './launch' },
            { type: 'command', text: './introduction' },
            { type: 'output', text: 'I build useful things.' },
            { type: 'output', text: 'I fix weird problems.' },
            { type: 'output', text: 'I keep systems running.' }
        ];

        const easterEggSequence = [
            { type: 'command', text: 'history | tail -5' },
            { type: 'output', text: '42  whoami' },
            { type: 'output', text: '43  uptime' },
            { type: 'output', text: '44  uname -a' },
            { type: 'output', text: '45  ls ~/projects' },
            { type: 'output', text: '46  sudo make me a sandwich' },
            { type: 'output', text: 'sudo: command not found' },
            { type: 'command', text: './deep_dive' },
            { type: 'output', text: 'warning: rabbit hole detected' },
            { type: 'output', text: 'continue? [y/N]' },
            { type: 'command', text: 'y' },
            { type: 'output', text: 'Ah ah ah...' },
            { type: 'effect', text: '☝' },
            { type: 'output', text: 'You didn\'t say the magic word.' }
        ];

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
                heroTerminalOutput.innerHTML = sequence.map(line => {
                    if (line.type === 'command') {
                        return makeCommandLine(line.text);
                    }
                    if (line.type === 'output') {
                        return makeOutputLine(line.text);
                    }
                    if (line.type === 'effect') {
                        return makeFingerLine(line.text);
                    }
                    return makeBlankLine();
                }).join('');
                return;
            }

            let lineIndex = 0;
            let charIndex = 0;
            let renderedHtml = '';
            const step = () => {
                if (sequenceToken !== activeSequenceToken) {
                    return;
                }
                let line = sequence[lineIndex];
                if (line) {
                    if (line.type === 'command') {
                        const partialText = line.text.slice(0, charIndex);
                        const isComplete = charIndex >= line.text.length;
                        const lineHtml = makeCommandLine(partialText);

                        heroTerminalOutput.innerHTML = `${renderedHtml}${lineHtml}`;

                        if (isComplete) {
                            renderedHtml += makeCommandLine(line.text);
                            heroTerminalOutput.innerHTML = renderedHtml;
                            lineIndex += 1;
                            charIndex = 0;
                            activeTimer = window.setTimeout(step, 0);
                            return;
                        }

                        charIndex += 1;
                        activeTimer = window.setTimeout(step, 72);
                        return;
                    }

                    if (line.type === 'effect') {
                        renderedHtml += makeFingerLine(line.text);
                        heroTerminalOutput.innerHTML = renderedHtml;
                        lineIndex += 1;
                        activeTimer = window.setTimeout(step, 0);
                        return;
                    }

                    while (line && line.type === 'output') {
                        renderedHtml += makeOutputLine(line.text);
                        heroTerminalOutput.innerHTML = renderedHtml;
                        lineIndex += 1;
                        charIndex = 0;
                        line = sequence[lineIndex];
                    }
                }
                if (lineIndex < sequence.length) {
                    activeTimer = window.setTimeout(step, 0);
                } else {
                    activeTimer = null;
                }
            };

            heroTerminalOutput.innerHTML = '';
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
