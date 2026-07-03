# CJ Saathoff Reveal.js Theme

A standalone Reveal.js theme based on the visual language of the CJ Saathoff personal website.

It carries over the site’s dark infrastructure-console feel: layered navy gradients, glassy cards, teal/blue accent gradients, Montserrat headings, Lato body copy, and terminal-green code styling.

## Files

- `cj-saathoff.css` — compiled CSS theme that can be dropped into any Reveal.js deck.

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

## Notes

This theme is plain CSS and does not require the Reveal.js theme build pipeline.