# BS Example — Block Template

This block is a **template**. Do NOT use it in production — duplicate it to create new blocks.

---

## How to create a new block

### 1. Copy the folder

Duplicate `blocks/bs-example/` and rename it to `blocks/bs-yourblock/`.  
The parent theme auto-discovers any folder inside `/blocks/` that contains `functions.php`, `index.js`, and `style.css`.

### 2. Find and replace

In all three files, replace:

| Find                | Replace with             |
|---------------------|--------------------------|
| `bs-example`        | `bs-yourblock`           |
| `bs_example`        | `bs_yourblock`           |
| `BS Example`        | `BS Your Block`          |

### 3. Define your attributes

Edit `functions.php` → `register_bs_yourblock()` to declare the attributes your block needs.  
Mirror the same attributes in `index.js` → `registerBlockType()`.

**Common attribute types:**

| PHP Type    | JS Type     | Control Component       |
|-------------|-------------|-------------------------|
| `string`    | `string`    | `TextControl`           |
| `string`    | `string`    | `TextareaControl`       |
| `boolean`   | `boolean`   | `ToggleControl`         |
| `string`    | `string`    | `MediaUpload` (for URL) |
| `string`    | `string`    | `SelectControl`         |
| `integer`   | `number`    | `RangeControl`          |

### 4. Update the render callback

Edit `functions.php` → `render_bs_yourblock()` to produce your desired HTML output.  
Use `esc_html()`, `esc_attr()`, `esc_url()`, and `wp_kses_post()` for proper escaping.

### 5. Update editor controls

Edit `index.js` → `EditComponent()` to match your attributes.  
Each attribute should have a corresponding control in the editor UI.

### 6. Update styles

Edit `style.css` with your block's CSS classes — both editor and frontend sections.

---

## File structure

```
blocks/bs-yourblock/
├── functions.php   ← Server-side: registration + render callback
├── index.js        ← Editor-side: block definition + edit UI
├── style.css       ← Styles (editor + frontend)
└── README.md       ← This guide (optional, can be removed)
```

## Block namespace

All blocks use the namespace `uikit-boostsolutions/bs-*` and the category `boost-solutions`.
