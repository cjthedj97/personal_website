# CJ Saathoff Reveal.js Theme

A standalone Reveal.js theme based on the visual language of the CJ Saathoff personal website.

It carries over the site’s dark infrastructure-console feel: layered navy gradients, glassy cards, teal/blue accent gradients, Montserrat headings, Lato body copy, and terminal-green code styling.

## Files

- `cj-saathoff.css` — compiled CSS theme that can be dropped into any Reveal.js deck.
- `plugin-compat.css` — optional companion CSS for testing common Reveal.js plugin UI and demo surfaces against the theme.
- `demo.html` — consolidated Reveal.js demo deck pointed at `cj-saathoff.css`.

## Demo

Open `demo.html` in a browser to test the theme locally:

```bash
python3 -m http.server 8000
# then open http://localhost:8000/revealjs-theme/demo.html
```

Once this branch is deployed by GitHub Pages, the demo will also be available at:

```text
/revealjs-theme/demo.html
```

The demo loads Reveal.js from CDN and exercises:

- official plugins: Highlight, Markdown, Notes, Search, Zoom, and Math/KaTeX.
- selected `rajgoel/reveal.js-plugins` surfaces: `audio-slideshow`, `anything`, and `animate`.
- local/static multiple-choice poll and question flows where answer buttons link to correct or incorrect feedback slides.
- layout examples for split content, metrics, cards, tables, vertical slides, terminal blocks, and light mode.

No room codes, join-room UI, Seminar setup, or Socket.io dependency is included.

## Usage

Include Reveal.js core styles first, then this theme:

```html
<link rel="stylesheet" href="dist/reveal.css">
<link rel="stylesheet" href="revealjs-theme/cj-saathoff.css">
```

For CDN-based Reveal.js decks:

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js/dist/reveal.css">
<link rel="stylesheet" href="./revealjs-theme/cj-saathoff.css">
```

## Optional plugin compatibility CSS

For decks using Highlight, Math, Search, Zoom, audio-slideshow, anything, animate, or local multiple-choice question/poll surfaces, load the compatibility stylesheet after the main theme:

```html
<link rel="stylesheet" href="revealjs-theme/cj-saathoff.css">
<link rel="stylesheet" href="revealjs-theme/plugin-compat.css">
```

It keeps plugin and demo surfaces aligned with the theme without making those styles mandatory for every deck.

## Optional slide helpers

The theme includes a few utility classes for slides that need the personal-site feel:

```html
<section>
  <p class="eyebrow">Infrastructure • Automation • Security</p>
  <h1>Better systems, fewer surprises.</h1>
  <div class="terminal-card">
    <pre><code>$ whoami
cj-saathoff</code></pre>
  </div>
</section>

<section class="glass-card">
  <h2>Operational Excellence</h2>
  <p>Readable, resilient, and easy to reason about.</p>
</section>
```

## Light mode

The default is dark. Add `light` to the Reveal root for a matching light variant:

```html
<div class="reveal light">
  <div class="slides">...</div>
</div>
```

The demo includes a button to toggle this class for quick comparison. It also persists the selected demo mode in `localStorage` and supports `?theme=light`.

## PDF export check

Reveal.js PDF export can be tested from the demo by opening:

```text
http://localhost:8000/revealjs-theme/demo.html?print-pdf
```

Then print from Chromium/Chrome using landscape orientation, no margins, and background graphics enabled.

## Notes

This theme is plain CSS and does not require the Reveal.js theme build pipeline.