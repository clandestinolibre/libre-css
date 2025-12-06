# LibreCSS

A minimal, token-based CSS framework for modern, maintainable user interfaces. LibreCSS focuses on clarity, consistency, and long-term stability — without utility overload or external dependencies.

## Philosophy

- **Minimal by design** – only what is truly useful
- **Token-first** – spacing, colors, typography are centrally defined
- **Component-oriented** – styles are encapsulated per component
- **Offline-ready** – local fonts, no CDN or third-party assets
- **Predictable** – no magic, no side effects

## Project Structure

```
assets/
  css/
    base.css            # Design tokens, reset, globals
    helpers.css         # Small, intentional helpers
    components/
      navbar.css
      hero.css
      cards.css
      cta.css
      faq.css
      features.css
      pricing.css
      team.css
      testimonials.css
      image-grid.css
      lightbox.css
      contact-form.css
  fonts/
    LiberationSans-*.ttf
    LiberationMono-*.ttf
  js/
  	loader.js
  	components/
  		contact-form.js
  		lightbox.js
  		navbar.js
```

## Base Layer (base.css)

The base layer defines the system foundation:

- Design tokens (`:root`)
- CSS reset
- Typography defaults
- Forms & buttons
- Core layout primitives

### Typography

LibreCSS ships with **local fonts only**:

- **Sans-serif:** Liberation Sans
- **Monospace:** Liberation Mono

```
body {
  font-family: var(--lib-font-sans);
}

code, pre {
  font-family: var(--lib-font-mono);
}
```

## Helpers (helpers.css)

Helpers are intentionally kept **very small**.
They exist only when they remove real duplication.

Currently included:

- `.lib-text-left`
- `.lib-text-center`
- `.lib-text-right`
- `.lib-text-justify`

> New helpers should only be added when multiple components require the same rule.

***

## Layout Primitives

- `.lib-container` – max-width layout container
- `.lib-section` – vertical rhythm for page sections
- `.lib-grid` – responsive auto-fit grid

These primitives are reused across all components.

***

## Components

Each component:

- Is fully encapsulated
- Uses design tokens only
- Does not override global styles unnecessarily
- Avoids new helpers unless strictly required

### Example: Cards

```
<div class="lib-cards-grid">
  <div class="lib-card">
    <img src="image.png" alt="" />
    <h3>Card title</h3>
    <p>Card content</p>
  </div>
</div>
```

## JavaScript Guidelines

LibreCSS keeps JavaScript **optional and minimal**. CSS is always responsible for layout and visuals.
JavaScript only adds behavior where necessary.

General rules:

- Progressive enhancement
- No inline styles
- Stable, CSS-first class names
- JS never controls layout or spacing

## JavaScript Components

Only a small set of components ship with JavaScript. Each script is standalone and can be included only when needed.

### Navbar (`navbar.js`)

Purpose:

- Mobile menu toggle
- Scroll-based state handling

Behavior:

- Toggles `.active` on the menu for mobile navigation
- Adds `.scrolled` and `.shrink` classes based on scroll position

Requirements:

- `.lib-navbar`
- `.lib-navbar-toggle`
- `.lib-navbar-menu`

### Lightbox (`lightbox.js`)

Purpose:

- Image preview overlay

Behavior:

- Opens images in a fullscreen overlay
- Supports next/previous navigation
- Keyboard support (ESC, arrows)
- Click on backdrop closes the lightbox

Requirements:

- `.lib-lightbox`
- `.lib-lightbox-image`
- `.lib-lightbox-prev`, `.lib-lightbox-next`, `.lib-lightbox-close`

### Contact Form (`contact-form.js`)

Purpose:

- Client-side form validation
- User feedback

Behavior:

- Field-level validation
- Inline error messages
- Success message handling

Requirements:

- `.lib-contact`
- `.lib-contact-field`
- `.lib-contact-error`
- `.lib-contact-success`

## Design Tokens

All tokens are defined in `:root` inside **base.css**.

### Typography

```
--lib-font-sans
--lib-font-mono

--lib-text-xs
--lib-text-sm
--lib-text-md
--lib-text-lg
--lib-text-xl
--lib-text-2xl
--lib-text-3xl

--lib-line-tight
--lib-line-normal
--lib-line-relaxed

```

### Spacing

```
--lib-space-2xs
--lib-space-xs
--lib-space-sm
--lib-space-md
--lib-space-lg
--lib-space-xl
--lib-space-2xl
--lib-space-3xl

```

### Border Radius

```
--lib-radius-xs
--lib-radius-sm
--lib-radius-md
--lib-radius-lg
--lib-radius-xl
--lib-radius-full

```

### Colors

```
--lib-color-bg
--lib-color-surface
--lib-color-surface-alt

--lib-color-text
--lib-color-text-muted

--lib-color-primary
--lib-color-primary-strong
--lib-color-primary-dark
--lib-color-primary-soft
--lib-color-primary-contrast

--lib-color-border
--lib-color-border-subtle
--lib-color-danger

```

### Shadows

```
--lib-shadow-sm
--lib-shadow-md
--lib-shadow-focus

```

### Transitions

```
--lib-transition-fast
--lib-transition-normal

```

### Layout

```
--lib-max-width
--lib-container-padding-x
--lib-navbar-offset
```

## Best Practices

- Never hardcode values if a token exists
- Box-based components use `space-lg` padding
- Vertical spacing is controlled by `.lib-section`
- Components should not redefine tokens
- Prefer consistency over cleverness

## Extending LibreCSS

When adding new components:

1. Use existing tokens
2. Check base/helpers before adding new styles
3. Avoid global overrides
4. Keep the system predictable

LibreCSS is designed to stay small, readable, and reliable — even as it grows.
