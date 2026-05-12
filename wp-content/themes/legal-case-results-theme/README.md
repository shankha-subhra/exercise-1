# Legal Case Results Bootstrap Theme

## Overview

This is a custom WordPress Bootstrap theme created for a legal firm to manage and display case results with marketing data integration.

The theme includes:

1. Custom Post Type: Case Results
2. Custom metabox fields without plugins
3. Bootstrap archive layout
4. AJAX filtering by Case Type
5. Bootstrap pagination using `ul` and `li`
6. Schema.org LegalCase microdata
7. Google Tag Manager event tracking
8. Proper sanitization and validation

---

## Custom Post Type

The theme registers a custom post type called:

```txt
case_result
```

Archive URL:

```txt
/case-results/
```

Admin menu label:

```txt
Case Results
```

---

## Custom Fields

The fields are created using a custom WordPress metabox, not ACF or any plugin.

Fields included:

1. Case Type
   - Personal Injury
   - Car Accident
   - Slip & Fall
   - Medical Malpractice

2. Settlement Amount
   - Numeric input
   - Stored as raw number
   - Displayed as formatted currency

3. Case Duration
   - Stored in months
   - Minimum: 1
   - Maximum: 120

4. Client Location
   - City
   - State

5. Case Year
   - Year dropdown
   - From current year back to 1990

---

## Validation and Sanitization

All fields are validated and sanitized before saving.

Examples:

```php
sanitize_key()
sanitize_text_field()
absint()
(float)
wp_verify_nonce()
current_user_can()
```

The save action uses:

```php
save_post_case_result
```

This keeps the save logic specific to the Case Results custom post type.

---

## AJAX Filtering

The archive page includes a Bootstrap select dropdown for filtering case results by case type.

AJAX action:

```txt
lcr_filter_case_results
```

Registered for both logged-in and guest users:

```php
wp_ajax_lcr_filter_case_results
wp_ajax_nopriv_lcr_filter_case_results
```

Security is handled using:

```php
check_ajax_referer()
```

The AJAX response returns:

1. Case card HTML
2. Bootstrap pagination HTML

No full page reload is required.

---

## Pagination

Pagination is custom-built using Bootstrap classes:

```html
<ul class="pagination">
    <li class="page-item">
        <a class="page-link">1</a>
    </li>
</ul>
```

The pagination also works through AJAX.

---

## High-Value Case Results

The function:

```php
lcr_display_high_value_cases()
```

Displays the 5 most recent cases where the settlement amount is greater than `$100,000`.

It uses an efficient `WP_Query` with a numeric meta query:

```php
'meta_query' => [
    [
        'key' => '_lcr_settlement_amount',
        'value' => 100000,
        'type' => 'NUMERIC',
        'compare' => '>',
    ],
]
```

Usage:

```php
echo lcr_display_high_value_cases();
```

---

## Schema.org Microdata

Each case card includes Schema.org microdata:

```html
itemscope itemtype="https://schema.org/LegalCase"
```

Included properties:

```txt
name
about
award
location
dateCreated
description
```

This helps search engines better understand the legal case result content.

---

## GTM Tracking

When a visitor clicks a case result, the theme pushes this event to Google Tag Manager:

```js
window.dataLayer.push({
    event: 'case_result_view',
    case_type: caseType,
    settlement_amount: settlementAmount
});
```

Event name:

```txt
case_result_view
```

Parameters:

```txt
case_type
settlement_amount
```

---

## Marketing Context

Tracking case result clicks is important because legal websites often rely on proof, trust, and conversion behavior.

The tracked fields help marketers understand:

1. Which case types attract the most interest
2. Whether high-value settlements increase engagement
3. Which legal service pages may need stronger calls-to-action
4. Which case categories generate better conversion intent

Example:

If many users click Car Accident cases with high settlement amounts, the firm can improve landing pages, ads, and remarketing campaigns around Car Accident claims.

---

## Architecture Decisions

### Why Custom Metabox Instead of Plugin?

The requirement says custom code should be used instead of plugins.

A custom metabox gives full control over:

1. Field validation
2. Sanitization
3. Admin UI
4. Database storage
5. Query performance

---

### Why Store Settlement as Raw Number?

The settlement amount is stored as a numeric value, not a formatted string.

Good:

```txt
150000
```

Bad:

```txt
$150,000
```

This makes numeric queries possible:

```php
'type' => 'NUMERIC'
```

---

### Why AJAX Filtering?

AJAX improves the user experience because visitors can filter case results without reloading the full archive page.

This is useful for conversion-focused legal pages where users may quickly compare results by case type.

---

### Why Bootstrap?

Bootstrap provides:

1. Responsive grid
2. Clean card layout
3. Mobile-friendly form controls
4. Ready pagination UI
5. Fast implementation for client-facing designs

---

## Installation

1. Upload the theme ZIP from:

```txt
Dashboard > Appearance > Themes > Add New > Upload Theme
```

2. Activate:

```txt
Legal Case Results Bootstrap Theme
```

3. Go to:

```txt
Dashboard > Settings > Permalinks
```

4. Click:

```txt
Save Changes
```

This flushes rewrite rules so the archive URL works.

---

## Archive URL

```txt
/case-results/
```

---

## How to Add Menu Link

Go to:

```txt
Dashboard > Appearance > Menus
```

Add custom link:

```txt
URL: /case-results/
Label: Case Results
```

---

## Notes

If the archive page does not appear, re-save permalinks.

If AJAX does not work, check the browser console and make sure:

```txt
case-results.js
```

is loading correctly.

If GTM events do not appear, make sure Google Tag Manager is installed on the site and `dataLayer` is available.

---

## Homepage Case Results Filter

The theme includes `front-page.php`.

The homepage now shows:

1. Bootstrap hero section
2. Case Type filter
3. AJAX case result grid
4. Bootstrap pagination using `ul` and `li`
5. GTM click tracking using the same `case_result_view` event

To use this as the homepage:

```txt
Dashboard > Settings > Reading
```

Then select either:

```txt
Your latest posts
```

or assign a static page as the homepage. WordPress will use `front-page.php` automatically when the theme is active.
