# Interface Design System: BillarNexus
**Domain**: Billiard Hall Point of Sale (POS) & Club Management
**Intent**: A fast, tactile, and immersive point-of-sale built for operators in dim environments. The software must feel physical and responsive, echoing the geometry and materials of a billiard table while keeping cognitive load minimal during busy hours.

## Color World (Midnight Felt)
The environment of a billiard hall influenced the color scale. We reject standard SaaS colors (Slate/Blue).
- **Backgrounds**: `0D0D0E` (Obsidian/Deepest shadows) rather than ` Slate-900`.
- **Raised Surfaces**: `161618` and `222225`. Whisper-quiet changes in lightness to avoid jarring borders.
- **Accents**: Neon Orange (`FF6700`), evoking neon signs commonly found in billiard halls, replacing generic orange (`F97316`).

## Depth Strategy
- **Base Cards**: Subtle `0 4px 12px` drop shadow. Hover pushes the shadow deeper `-4px rgba(0,0,0,0.3)` instead of expanding borders.
- **Drawer / Modals**: Deep blurring backgrounds (`4px backdrop-filter`) to push the noisy POS activity back while the transaction takes foreground. Deep separation without using generic offcanvas wrappers.

## Signature Elements
- **Billiard Ball Identity**: Pool table numerical identifiers act as literal UI physical objects (a CSS circle with an inset radial gradient mimicking light reflection on a phenolic resin sphere). 
- **Tabular Timers**: The `cronometro` component strictly uses `tabular-nums` ensuring time counters never shift layout width.

## Consistency Checks
Before releasing any element:
- *Swap Test*: If this panel was light blue and generic, would the user behave differently? If no, push for more "Billiard" immersion.
- *Spacing Rule*: Components follow multiples of Tailwind's 4px base logic (`radius-md: 0.75rem`).
- *No AdminLTE small-boxes*: Data sits in Bento Grid surfaces. Never solid-color block indicators.
