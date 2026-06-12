---
name: Industrial Port Inspector
colors:
  surface: '#f9f9fc'
  surface-dim: '#dadadc'
  surface-bright: '#f9f9fc'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3f6'
  surface-container: '#eeeef0'
  surface-container-high: '#e8e8ea'
  surface-container-highest: '#e2e2e5'
  on-surface: '#1a1c1e'
  on-surface-variant: '#594138'
  inverse-surface: '#2f3133'
  inverse-on-surface: '#f0f0f3'
  outline: '#8d7166'
  outline-variant: '#e1bfb3'
  surface-tint: '#a63b00'
  primary: '#a63b00'
  on-primary: '#ffffff'
  primary-container: '#f26522'
  on-primary-container: '#4f1800'
  inverse-primary: '#ffb599'
  secondary: '#1961a1'
  on-secondary: '#ffffff'
  secondary-container: '#81b9ff'
  on-secondary-container: '#004980'
  tertiary: '#545f72'
  on-tertiary: '#ffffff'
  tertiary-container: '#8792a7'
  on-tertiary-container: '#202b3c'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdbce'
  primary-fixed-dim: '#ffb599'
  on-primary-fixed: '#370e00'
  on-primary-fixed-variant: '#7f2b00'
  secondary-fixed: '#d2e4ff'
  secondary-fixed-dim: '#a1c9ff'
  on-secondary-fixed: '#001c37'
  on-secondary-fixed-variant: '#004880'
  tertiary-fixed: '#d8e3fa'
  tertiary-fixed-dim: '#bcc7dd'
  on-tertiary-fixed: '#111c2c'
  on-tertiary-fixed-variant: '#3c475a'
  background: '#f9f9fc'
  on-background: '#1a1c1e'
  surface-variant: '#e2e2e5'
typography:
  headline-xl:
    fontFamily: IBM Plex Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: IBM Plex Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: IBM Plex Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: IBM Plex Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: IBM Plex Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: IBM Plex Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  label-caps:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
  data-mono:
    fontFamily: JetBrains Mono
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 18px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  unit: 4px
  container-padding: 24px
  gutter: 16px
  stack-sm: 8px
  stack-md: 16px
  stack-lg: 32px
---

## Brand & Style

The design system is engineered for high-stakes industrial environments where clarity, safety, and data integrity are paramount. It draws inspiration from the heavy industry sector, specifically port and mining operations, to create a workspace that feels durable, authoritative, and precise.

The aesthetic follows a **Corporate / Modern** approach with a utilitarian edge. It prioritizes information density and logical structure over decorative elements. By utilizing a high-contrast palette and a rigid grid system, the interface ensures that safety inspectors can navigate complex facility reports and technical data without cognitive friction. The emotional response is one of reliability and "Safety First," mirroring the physical environment of port operations.

## Colors

The color palette is rooted in industrial safety and corporate stability. 

- **Primary (Safety Orange):** Reserved strictly for critical actions, alerts, and primary navigation highlights. It demands attention against the darker neutral tones.
- **Secondary (Industrial Blue):** Used for structural elements, headers, and secondary actions. It provides a professional, stable foundation that contrasts with the warmth of the orange.
- **Neutral (Deep Charcoal):** Used for text, iconography, and heavy UI surfaces to ensure high legibility and a sophisticated, "hard-hat" feel.
- **Surface & Backgrounds:** A range of cool grays (#F8F9FA to #E2E8F0) are used to differentiate content sections in dense reporting views.
- **Status Indicators:** 
  - Success: #059669 (Deep Emerald)
  - Warning: #D97706 (Amber)
  - Critical/Fail: #DC2626 (Safety Red)

## Typography

This design system utilizes **IBM Plex Sans** for its primary typeface, chosen for its technical precision and exceptional legibility in both digital and physical-industrial contexts. 

For data-heavy reports, asset IDs, and technical specifications, **JetBrains Mono** is employed to ensure character distinction (e.g., O vs 0, I vs 1), which is critical for avoiding errors during facility inspections.

Typography is scaled to maintain high density. On desktop, the system prioritizes `body-sm` and `data-mono` to maximize the amount of information visible on a single screen without requiring excessive scrolling. Headlines use a tighter letter-spacing to maintain a "structured" look.

## Layout & Spacing

The layout is built on a **12-column fluid grid** that locks at a maximum width of 1440px for desktop reporting screens. 

- **Density:** The system uses a 4px base unit. For inspection forms and data tables, a "Compact" spacing model is applied (8px padding in cells) to allow inspectors to view multiple facility parameters simultaneously.
- **Breakpoints:**
  - Desktop (1280px+): Full 12-column layout with persistent left sidebar for facility navigation.
  - Tablet (768px - 1279px): 8-column layout, sidebar collapses to an icon-only rail.
  - Mobile (Under 768px): 4-column layout, vertical stacking of all data cards.
- **Reflow Rules:** Data tables on mobile convert to "Card Views" where each row becomes a standalone card with labeled key-value pairs.

## Elevation & Depth

Visual hierarchy in this design system is achieved through **Tonal Layering** and **Low-Contrast Outlines** rather than aggressive shadows. This maintains an industrial, "flat" efficiency.

- **Level 0 (Background):** #F1F5F9 (Light Slate). The base layer for the application.
- **Level 1 (Cards/Containers):** White (#FFFFFF) with a 1px border in #E2E8F0. This is the primary surface for data entry and report viewing.
- **Level 2 (Dropdowns/Modals):** White with a 1px border in #CBD5E1 and a tight, low-opacity shadow (0 4px 6px -1px rgba(0, 0, 0, 0.1)) to provide focus without breaking the industrial aesthetic.
- **Focus States:** High-visibility 2px solid strokes in Secondary Blue (#005696) ensure keyboard navigability for power users.

## Shapes

The shape language is **Soft (0.25rem)**. This subtle rounding provides a modern touch to the otherwise rigid industrial grid, making the UI feel like contemporary hardware interface software.

- **Small elements (Checkboxes, Tags):** 2px (rounded-sm) for a sharper, more precise feel.
- **Standard elements (Buttons, Inputs, Cards):** 4px (rounded).
- **Large elements (Modals, Large Containers):** 8px (rounded-lg).

This approach ensures that the UI feels "constructed" and modular, reflecting the architectural and engineering nature of port facilities.

## Components

### Buttons
- **Primary:** Solid Safety Orange with white text. Bold, all-caps for "SUBMIT INSPECTION" or "START REPORT."
- **Secondary:** Outline Industrial Blue. Used for "Download PDF" or "Edit Asset."
- **Ghost:** Deep Charcoal text with no background. Used for "Cancel" or "Go Back."

### Status Badges
Badges are critical for inspection results. They use high-contrast backgrounds with white text:
- `PASS`: Solid Green.
- `FAIL`: Solid Red.
- `MONITOR`: Solid Amber.
- `NOT INSPECTED`: Solid Charcoal.

### Data Tables
- **Header:** Industrial Blue background with white, semi-bold text.
- **Rows:** Zebra striping (White / Light Gray) with 1px bottom borders.
- **Active State:** A 4px vertical Safety Orange bar on the far left of a row indicates the currently selected item.

### Input Fields
- Structured with a persistent 1px Slate border. 
- Focus state uses a 2px Industrial Blue ring. 
- Labels are always positioned top-left in `label-caps` for clear field identification even when filled.

### Progress Indicators
Linear progress bars are used for inspection completion percentages. They use a light gray track and a Safety Orange fill to indicate work in progress.