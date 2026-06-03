<p align="center">
    <h1 align="center">This is the repo containing my personal website</h1>
<p align="center">
	<img src="https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=flat&logo=JavaScript&logoColor=black" alt="JavaScript">
	<img src="https://img.shields.io/badge/HTML5-E34F26.svg?style=flat&logo=HTML5&logoColor=white" alt="HTML5">
	<img src="https://img.shields.io/badge/YAML-CB171E.svg?style=flat&logo=YAML&logoColor=white" alt="YAML">
	<img src="https://img.shields.io/badge/Markdown-000000.svg?style=flat&logo=Markdown&logoColor=white" alt="Markdown">
</p>
<hr>

##  Quick Links

> - [ Overview](#-overview)
> - [ Features](#-features)
> - [ Repository Structure](#-repository-structure)
> - [ Modules](#-modules)
> - [ Getting Started](#-getting-started)
>   - [ Installation](#-installation)
>   - [Running app](#-running-app)
>   - [ Tests](#-tests)
> - [ Project Roadmap](#-project-roadmap)
> - [ Contributing](#-contributing)
> - [ License](#-license)
> - [ Acknowledgments](#-acknowledgments)

---

##  Overview

The app project is a Jekyll-based static site that offers a variety of tools and games, including a Shell Command Game, Fake IP Generator, and IP lookup functionality. It enhances user experience by providing dynamic content, engaging interactions, and seamless integration of external services. The project focuses on enhancing SEO with features like meta tags management, URL redirections, and site ownership verification. Additionally, it ensures a consistent and visually appealing user interface with style configurations and navigation structures. Overall, the app project aims to provide a diverse set of tools and games while prioritizing user engagement and site optimization.

---

##  Notes

- SSL Checker is currently disabled. We need to come back and replace the third-party proxy before re-enabling it.

---

##  Repository Structure

```sh
└── app/
    ├── 3369873ba23e4dbe4a6ae01b5c5518368078fd6f.html
    ├── 404.html
    ├── Gemfile
    ├── LICENSE
    ├── _config.yml
    ├── _includes
    │   ├── footer.html
    │   ├── head.html
    │   └── nav.html
    ├── _layouts
    │   ├── home.html
    │   └── page.html
    ├── _redirects
    ├── assets
    │   ├── browserconfig.xml
    │   ├── favicon-114x114.png
    │   ├── favicon-120x120.png
    │   ├── favicon-144x144.png
    │   ├── favicon-150x150.png
    │   ├── favicon-152x152.png
    │   ├── favicon-16x16.png
    │   ├── favicon-180x180.png
    │   ├── favicon-192x192.png
    │   ├── favicon-310x310.png
    │   ├── favicon-32x32.png
    │   ├── favicon-57x57.png
    │   ├── favicon-60x60.png
    │   ├── favicon-70x70.png
    │   ├── favicon-72x72.png
    │   ├── favicon-76x76.png
    │   ├── favicon-96x96.png
    │   ├── favicon.ico
    │   ├── font
    │   │   ├── Bitwise-m19x.ttf
    │   │   └── Hacked-KerX.ttf
    │   └── img
    │       ├── banner.jpg
    │       └── profilepic.png
    ├── css
    │   ├── fakeip.css
    │   └── styles.css
    ├── google4a0c7e8f0461e331.html
    ├── index.markdown
    ├── js
    │   ├── copyright.js
    │   ├── ipv4.js
    │   ├── ipv6.js
    │   └── scripts.js
    ├── pages
    │   ├── abusedb.html
    │   ├── fakeip.html
    │   ├── ip.html
    │   ├── ovpn2onc.html
    │   └── shell-command-game.html
    ├── readme-ai.md
    └── robots.txt
```

---

##  Modules

<details closed><summary>.</summary>

| File                                                                                           | Summary                                                                                                                                                                                          |
| ---                                                                                            | ---                                                                                                                                                                                              |
| [_redirects](_redirects)                                                                       | Code snippet in `_redirects` directs URLs to external sites for blog, IP info, portfolio, and main domain. Crucial for website navigation and SEO.                                               |
| [_config.yml](_config.yml)                                                                     | Code Summary:** 🌟 In the `_config.yml` file, site details like title, email, and description are defined for the Jekyll blog. This file is essential for setting up and customizing the website. |
| [Gemfile](Gemfile)                                                                             | Summary:Transforms data from MongoDB to JSON format using a custom parser. Crucial for interfacing with frontend components in the parent repository's architecture.                             |
| [google4a0c7e8f0461e331.html](google4a0c7e8f0461e331.html)                                     | Code snippet in `google4a0c7e8f0461e331.html` verifies site ownership for Google. Crucial for SEO, it authenticates the website with Google.                                                     |
| [index.markdown](index.markdown)                                                               | Code in `index.markdown` sets layout to home in Jekyll-based site, influencing homepage appearance within repo's directory structure.                                                            |
| [404.html](404.html)                                                                           | Code snippet in 404.html handles the 404 error page layout and styling in the repository. It includes visual elements, content, and scripts for user interaction on the page.                    |
| [robots.txt](robots.txt)                                                                       | Code in `robots.txt` disallows specific paths for web crawlers. Enhances SEO by preventing indexing certain content for search engines in the parent repository.                                 |
| [3369873ba23e4dbe4a6ae01b5c5518368078fd6f.html](3369873ba23e4dbe4a6ae01b5c5518368078fd6f.html) | Code snippet in `3369873ba23e4dbe4a6ae01b5c5518368078fd6f.html` manages site meta tags for SEO optimization in the parent repository's static web pages architecture.                            |

</details>

<details closed><summary>pages</summary>

| File                                                     | Summary                                                                                                                                                                                                      |
| ---                                                      | ---                                                                                                                                                                                                          |
| [ovpn2onc.html](pages/ovpn2onc.html)                     | Code snippet updates dynamic content on the home page layout in the app repository. It enhances user experience by displaying real-time data, boosting engagement and interaction.                           |
| [shell-command-game.html](pages/shell-command-game.html) | Code snippet in `pages/shell-command-game.html` for a Shell Command Game. Allows users to input commands for each alphabet letter from A to Z, tracking time and displaying results.                         |
| [abusedb.html](pages/abusedb.html)                       | Code snippet in `abusedb.html` generates a contributor badge linking to AbuseIPDB page. Enhances visibility of users contributing to IP blacklist. Fits within parent repository's web content architecture. |
| [fakeip.html](pages/fakeip.html)                         | Code snippet in `pages/fakeip.html` generates fake IPv4 and IPv6 addresses. Key features include address generation functions and display areas. Intended for use in the parent repository's tools section.  |
| [ip.html](pages/ip.html)                                 | Code snippet in pages/ip.html displays an iframe hosting an external site for IP lookup. Enhances parent repository's architecture by integrating external functionality seamlessly.                         |

</details>

<details closed><summary>_includes</summary>

| File                                 | Summary                                                                                                                                                                                                                                                 |
| ---                                  | ---                                                                                                                                                                                                                                                     |
| [head.html](_includes/head.html)     | Code Summary:** `head.html` defines metadata and links to styling resources for the landing page. It configures essential elements for design consistency and functionality in the parent repository's frontend architecture.                           |
| [footer.html](_includes/footer.html) | Code snippet in _includes/footer.html in app/ handles footer display, social links, and scripts integration. Enhances site usability within repository's frontend architecture.                                                                         |
| [nav.html](_includes/nav.html)       | Code Summary: `nav.html` provides navbar structure for CJ Saathoff's portfolio site, offering links to homepage, blog, tools (e.g., Fake IP Generator), and games (e.g., Shell Command Game), enhancing user navigation in the repository architecture. |

</details>

<details closed><summary>_layouts</summary>

| File                            | Summary                                                                                                                                                                                        |
| ---                             | ---                                                                                                                                                                                            |
| [home.html](_layouts/home.html) | Summary: The `home.html` layout file in the repository defines the structure for the homepage display, featuring header with image and bio, using included templates for consistency.          |
| [page.html](_layouts/page.html) | Code snippet in _layouts/page.html centers content on the page using CSS flexbox. It includes header and navigation. Supports consistent layout for various pages in the repository structure. |

</details>

<details closed><summary>js</summary>

| File                            | Summary                                                                                                                                                                                                                                          |
| ---                             | ---                                                                                                                                                                                                                                              |
| [ipv6.js](js/ipv6.js)           | Code snippet `js/ipv6.js` generates a random IPv6 address using a defined base and outputting it to the DOM. It plays a crucial role in providing dynamic and unique IPv6 addresses within the parent repository's web application architecture. |
| [copyright.js](js/copyright.js) | Role:** `js/copyright.js` in `app/` folder updates the webpage's copyright year dynamically, presenting Christopher Saathoff's name.**Parent Repository Architecture:** Static site with JS functionality for dynamic copyright year display.    |
| [scripts.js](js/scripts.js)     | Code Summary:**`js/scripts.js` manages navbar behavior in response to user scroll and click events. It enhances user experience by dynamically adjusting navigation appearance.                                                                  |
| [ipv4.js](js/ipv4.js)           | Code snippet `js/ipv4.js` generates random IPv4 addresses from predefined ranges, enhancing dynamic data display in web pages.                                                                                                                   |

</details>

<details closed><summary>css</summary>

| File                         | Summary                                                                                                                                                                                                                            |
| ---                          | ---                                                                                                                                                                                                                                |
| [styles.css](css/styles.css) | Code snippet: `app/3369873ba23e4dbe4a6ae01b5c5518368078fd6f.html`Summary: Critical HTML file for site content rendering in the app, enhancing user experience. Supports overall site structure within the repository architecture. |
| [fakeip.css](css/fakeip.css) | Code Summary**:`fakeip.css` in `css` directory styles app interface. Body flex layout, centered content, button styling with hover effect. Enhances visual aesthetics and interactivity in the application architecture.           |

</details>

---
