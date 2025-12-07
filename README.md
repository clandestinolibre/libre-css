# LibreCSS

LibreCSS is a lightweight website template designed to help you build modern websites quickly — without build tools, frameworks, or external dependencies.

It combines:

- a component-based CSS framework
- a small JavaScript utility library for common browser features
- ready-to-use HTML templates
- a simple PHP-based mailer for the contact form

Everything is designed to be minimal, predictable, and easy to extend.

## Philosophy

- Lightweight by default – no build step required  
- Component-based CSS framework  
- Small JavaScript library for essential browser features  
- Ready-to-use HTML templates  
- Offline-ready (local fonts, no CDNs)  
- Predictable behavior, no magic  

## CSS Framework

LibreCSS ships with a small, token-based CSS framework.

### Base Layer (`base.css`)

The base layer defines:

- Design tokens (`:root`)
- CSS reset
- Typography defaults
- Forms and buttons
- Core layout primitives

#### Typography

LibreCSS uses local fonts only:

- Sans-serif: Liberation Sans  
- Monospace: Liberation Mono  

```css
body {
  font-family: var(--lib-font-sans);
}

code,
pre {
  font-family: var(--lib-font-mono);
}
```

### Helpers (`helpers.css`)

Helpers are intentionally kept very small and are only added when they remove real duplication.

Currently included helpers:

- `.lib-text-left`
- `.lib-text-center`
- `.lib-text-right`
- `.lib-text-justify`

### Layout Primitives

- `.lib-container` – max-width layout container
- `.lib-section` – vertical spacing between sections
- `.lib-grid` – responsive auto-fit grid

These primitives are reused across all templates and components.

## Components

Each component:

- Is fully encapsulated
- Uses design tokens only
- Avoids unnecessary overrides
- Does not rely on JavaScript unless required

### Example: Cards

```html
<div class="lib-cards-grid">
  <div class="lib-card">
    <img src="image.png" alt="" />
    <h3>Card title</h3>
    <p>Card content</p>
  </div>
</div>
```

## JavaScript Library

LibreCSS includes a small JavaScript library for essential browser features.
JavaScript is optional and only used where needed.

### General Rules

- Progressive enhancement
- No inline styles
- Stable, CSS-first class names
- JavaScript controls behavior, not layout

### Navbar (`navbar.js`)

**Purpose**

- Mobile menu toggle
- Scroll-based state handling

**Behavior**

- Toggles `.active` on mobile navigation
- Adds `.scrolled` / `.shrink` classes based on scroll position

### Lightbox (`lightbox.js`)

**Purpose**

- Fullscreen image preview

**Behavior**

- Opens images in an overlay
- Previous / next navigation
- Keyboard support (ESC, arrow keys)
- Click on backdrop closes the lightbox

### Contact Form (`contact-form.js`)

**Purpose**

- Client-side form validation

**Behavior**

- Field-level validation
- Inline error messages
- Success message handling

## HTML Templates

LibreCSS includes ready-to-use HTML templates:

- Landing page
- Component showcase
- Image gallery with lightbox
- Contact form integration

Templates are fully wired with CSS and JavaScript and can be used as a starting point for real projects.

## Server-Side Mailer

LibreCSS includes a simple PHP-based mailer for the contact form.

**Features**

- Server-side email sending
- Minimal configuration
- Works with the included contact form
- No framework dependencies

**Location**

```
server/mailer.php
```

## Design Tokens

All design tokens live in `:root` inside `base.css`.

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

### Colors

```
--lib-color-bg
--lib-color-surface
--lib-color-surface-alt

--lib-color-text
--lib-color-text-muted

--lib-color-primary
--lib-color-primary-dark
--lib-color-primary-soft
--lib-color-primary-contrast

--lib-color-border
--lib-color-border-subtle
--lib-color-danger
```

LibreCSS is designed to stay small, readable, and practical — even as your project grows.
