# Foreman landing — design direction (v3: enowx-inspired)

User explicitly asked to imitate https://enowx.ai/ (R-30 allows mimicking
on explicit request). Take its visual language; keep Foreman's own
content and voice (real fleet incidents, shift narrative). Inspired by,
not a pixel clone.

## Language borrowed from enowx.ai

- Pure-dark developer tool aesthetic: bg #0a0a0b, surfaces #111214,
  thin hairline dividers, centered max-width column.
- Sticky nav: brand + status tag left, floating pill nav center,
  single pill CTA right.
- Headlines: light-weight humanist sans, sentence case, generous size.
  Section labels: uppercase letterspaced mono ("WHAT IS INSIDE").
- Mono path labels on cards (gate/dedup), numbered cards (01-04).
- Constellation particle network in hero (faint dot-and-line mesh).
- Marquee ticker strip with mono items.
- Warm orange-red signature accent, amber secondary, muted green only
  for "ok" statuses inside log panels.
- Terminal-style panels as the central visual device. For Foreman this
  is honest: the run log IS the product surface (run reports), not a
  costume. Tabs in the hero panel really switch content.

## Palette

- Base #0a0a0b, surface #111214, hairline rgba(255,255,255,.08).
- Ink #f2f2f0, muted #9a9a96.
- Accent #ff5a1f (warm orange-red): primary CTA, key emphasis, brand mark.
- Amber #f5b544: retry/warn states. Green #34d399: ok statuses only.
- One soft radial vignette in hero (their motif, dose-capped).

## Typography

- Display/body: Plus Jakarta Sans Light (300) for headlines, Regular
  for body. Bundled TTF, variable 100-900.
- Mono: IBM Plex Mono for labels, paths, code, log rows, ticker.

## Structure

1. Pill nav. 2. Hero split: headline + CTAs left, tabbed run-log
   panel right, constellation canvas behind. 3. Marquee ticker.
4. WHAT IS INSIDE: 4 numbered gate cards. 5. THE LOG: three incident
   rows (02:14, 03:40, 05:12) with hairline dividers. 6. HOW IT IS
   STAFFED: 3 layer cards + MCP config code block. 7. GET COVERAGE:
   waitlist form. 8. Two-column footer.

## Dials

ENERGY 2 / RHYTHM 2 / MOTION 2.

## Motion

- Hero is the alive part (enowx-like): a full-bleed constellation (denser
  node field) plus two slow-drifting ambient orbs. The mouse only plays
  with the nodes: they repel the cursor and nearby lines warm up.
  (Cursor-following glow was tried and removed per irfan — nodes only.)
- Headline enters word by word (one-time stagger); panel rows cascade in
  on load, then appear instantly on later tab switches.
- The run-log panel simulates a live run: rows stream in one by one with
  the status text typing out like a terminal line (blinking caret while
  typing), footer minutes tick as a fast timelapse to 18, then the next
  run number starts a fresh cycle. Pauses off-screen or in a hidden tab;
  static rows when prefers-reduced-motion.
- Cards lift 4px on hover and the number badge fills accent. Purpose:
  the gates are the product; hover says "these are worth reading".
- Log rows highlight on hover, timestamp turns accent. Panel rows too.
- Tab indicator slides between tabs (layout-measured, eased).
- Marquee pauses on hover. Primary buttons gain a warm shadow on hover.
- No pulses, no endless loops besides ambient drift + the reference's
  marquee. Respects prefers-reduced-motion (canvas static, glow/orbs off,
  entrances instant).

## Copy rules

No em dashes (R-02). No buzzwords (R-16). CTA: "Request coverage".
No invented numbers/testimonials (R-17, R-18). Incidents are real.
