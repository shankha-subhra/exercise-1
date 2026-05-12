# Exercise 1: WordPress Custom Post Type with Marketing Data Integration

## Project Overview

This project is a custom WordPress Bootstrap theme created for a legal firm website.

The legal firm wants to showcase case results and collect useful marketing data for analytics and conversion optimization.

The theme includes:

1. Custom Post Type: Case Results
2. Custom metabox fields without plugins
3. Bootstrap frontend design
4. AJAX filtering by Case Type and Client State
5. Bootstrap pagination using `ul` and `li`
6. Schema.org structured data for legal cases
7. Google Tag Manager event tracking
8. Post duplicate functionality
9. Homepage and archive page case result listing

---

---

## Project Setup in XAMPP

### 1. Clone or Download the Project

Download this repository from GitHub and place it inside your XAMPP `htdocs` folder.

Example:

```txt
C:\xampp\htdocs\exercise-3
```

Your local project URL should be:

```txt
http://localhost/exercise-3/
```

---

### 2. Import Database

A `.sql` file is included in the project root.

Open phpMyAdmin:

```txt
http://localhost/phpmyadmin
```

Create a new database, for example:

```txt
exercise_3
```

Then import the `.sql` file from the project root.

---

### 3. Update WordPress Site URL

After importing the database, update the WordPress options table.

Open phpMyAdmin and find the table:

```txt
wp_options
```

Update these two option values:

```txt
site_url
home_url
```

Set both to:

```txt
http://localhost/exercise-1
```

Example SQL:

```sql
UPDATE wp_options
SET option_value = 'http://localhost/exercise-1'
WHERE option_name IN ('site_url', 'home_url');
```

If your table prefix is different, replace `wp_options` with your actual options table name.

Example:

```sql
UPDATE yourprefix_options
SET option_value = 'http://localhost/exercise-1'
WHERE option_name IN ('site_url', 'home_url');
```

---

### 4. WordPress Login Details

Use the following login credentials:

```txt
Username: exercise1
Password: Exercise1@!
```

Login URL:

```txt
http://localhost/exercise-1/wp-admin
```

---

### 5. Update Permalinks

After login, go to:

```txt
Dashboard > Settings > Permalinks
```

Click:

```txt
Save Changes
```

This will regenerate the WordPress rewrite rules.

## Theme Information

Theme name:

```txt
Legal Case Results Bootstrap Theme
```

Theme folder:

```txt
legal-case-results-theme
```

Theme location:

```txt
wp-content/themes/legal-case-results-theme/
```

---

## Scenario

A legal firm client wants to showcase their successful case results on their WordPress website.

Each case result stores marketing-related information, including:

```txt
- Case Type
- Settlement Amount
- Case Duration
- Client City
- Client State
- Case Year
```

This data helps the business understand which types of case results get the most user engagement.

---

## Custom Post Type

The project registers a custom post type:

```txt
case_result
```

Admin menu name:

```txt
Case Results
```

Archive URL:

```txt
/case-results/
```

The custom post type supports:

```txt
- Title
- Editor
- Excerpt
- Featured Image
```

---

## Custom Fields

The theme uses a custom coded WordPress metabox.

No ACF or third-party custom field plugin is used.

Metabox title:

```txt
Case Result Marketing Data
```

---

### 1. Case Type

Field type:

```txt
Dropdown
```

Options:

```txt
Personal Injury
Car Accident
Slip & Fall
Medical Malpractice
```

Meta key:

```txt
_lcr_case_type
```

Stored values:

```txt
personal_injury
car_accident
slip_and_fall
medical_malpractice
```

---

### 2. Settlement Amount

Field type:

```txt
Number input
```

Example input:

```txt
150000
```

Meta key:

```txt
_lcr_settlement_amount
```

The value is stored as a raw number so it can be used in numeric queries.

Frontend display example:

```txt
$150,000.00
```

---

### 3. Case Duration

Field type:

```txt
Number input
```

Value stored in months.

Example:

```txt
12
```

Meta key:

```txt
_lcr_case_duration
```

Validation:

```txt
Minimum: 1 month
Maximum: 120 months
```

---

### 4. Client City

Field type:

```txt
Text input
```

Example:

```txt
Chicago
```

Meta key:

```txt
_lcr_client_city
```

---

### 5. Client State

Field type:

```txt
Dropdown
```

The dropdown shows state code and state name.

Example:

```txt
CA - California
NY - New York
TX - Texas
```

Stored value:

```txt
CA
NY
TX
```

Meta key:

```txt
_lcr_client_state
```

---

### 6. Case Year

Field type:

```txt
Year dropdown
```

The dropdown shows years from the current year back to 1990.

Meta key:

```txt
_lcr_case_year
```

---

## Validation and Sanitization

The metabox save function includes proper WordPress validation and sanitization.

Security checks used:

```php
wp_verify_nonce()
current_user_can()
DOING_AUTOSAVE
```

Sanitization functions used:

```php
sanitize_key()
sanitize_text_field()
absint()
wp_unslash()
(float)
```

Validation rules:

```txt
- Case Type must be one of the allowed dropdown values
- Client State must be one of the allowed state codes
- Settlement Amount must be numeric
- Case Duration must be between 1 and 120 months
- Case Year must be between 1990 and the current year
```

---

## Frontend Templates

The theme includes these main template files:

```txt
front-page.php
archive-case_result.php
single-case_result.php
index.php
```

---

## Homepage

The homepage displays a case result section with:

```txt
- Hero section
- Filter by Case Type
- Filter by Client State
- Responsive case result grid
- AJAX pagination
- GTM click tracking
```

The homepage uses the same AJAX filter system as the archive page.

---

## Case Results Archive Page

Archive URL:

```txt
/case-results/
```

The archive page includes:

```txt
- Bootstrap hero section
- Case Type filter
- Client State filter
- Responsive Bootstrap card grid
- AJAX pagination
```

Users can filter by:

```txt
- Case Type only
- Client State only
- Case Type and Client State together
```

Example:

```txt
Car Accident + CA - California
```

This returns only posts where:

```txt
_lcr_case_type = car_accident
_lcr_client_state = CA
```

---

## Single Case Result Page

The single case result page displays:

```txt
- Case title
- Featured image
- Case content
- Case type
- Settlement amount
- Duration
- Client location
- Case year
```

It also includes Schema.org LegalCase microdata.

---

## AJAX Filtering

The AJAX filter is used on both the homepage and archive page.

AJAX action:

```txt
lcr_filter_case_results
```

WordPress AJAX hooks:

```php
add_action('wp_ajax_lcr_filter_case_results', 'lcr_ajax_filter_case_results');
add_action('wp_ajax_nopriv_lcr_filter_case_results', 'lcr_ajax_filter_case_results');
```

Security check:

```php
check_ajax_referer('lcr_case_filter_nonce', 'nonce');
```

The AJAX request sends:

```txt
case_type
client_state
page
nonce
```

The AJAX response returns:

```txt
- Case result HTML
- Pagination HTML
```

---

## Pagination

Pagination is built with Bootstrap using `ul` and `li`.

Example structure:

```html
<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link">Previous</a>
        </li>
        <li class="page-item active">
            <a class="page-link">1</a>
        </li>
        <li class="page-item">
            <a class="page-link">Next</a>
        </li>
    </ul>
</nav>
```

Pagination works through AJAX, so the page does not reload.

---

## High-Value Case Results Function

The theme includes this function:

```php
lcr_display_high_value_cases()
```

This displays the 5 most recent case results where the settlement amount is greater than:

```txt
$100,000
```

The query uses a numeric meta query:

```php
'meta_query' => [
    [
        'key'     => '_lcr_settlement_amount',
        'value'   => 100000,
        'type'    => 'NUMERIC',
        'compare' => '>',
    ],
]
```

Usage:

```php
echo lcr_display_high_value_cases();
```

---

## Schema.org Structured Data

Case result cards and single case result pages include Schema.org structured data.

Schema type:

```txt
LegalCase
```

Example:

```html
<article itemscope itemtype="https://schema.org/LegalCase">
```

Properties used:

```txt
name
about
award
location
dateCreated
description
```

This helps search engines understand the content better.

---

## Google Tag Manager Tracking

When a visitor clicks a case result, a GTM event is pushed to the data layer.

Event name:

```txt
case_result_view
```

JavaScript example:

```js
window.dataLayer = window.dataLayer || [];

window.dataLayer.push({
    event: 'case_result_view',
    case_type: caseType,
    settlement_amount: settlementAmount
});
```

Parameters passed:

```txt
case_type
settlement_amount
```

---

## Marketing Context

Tracking case result clicks is useful because legal websites depend heavily on trust, proof, and successful outcomes.

The tracking helps the marketing team understand:

```txt
- Which case types get the most clicks
- Whether high-value settlements increase engagement
- Which practice areas attract stronger visitor interest
- Which case categories should be used in ads
- Which landing pages may need better calls-to-action
```

Example:

If visitors frequently click high-value Car Accident case results, the firm can improve:

```txt
- Car Accident landing pages
- Google Ads campaigns
- Retargeting audiences
- Homepage featured case sections
```

---

## Duplicate Post Feature

The theme includes a custom duplicate feature for:

```txt
Posts
Pages
Case Results
```

The duplicate link appears in the admin post list:

```txt
Edit | Quick Edit | Trash | View | Duplicate
```

When clicked, it creates a new draft copy.

Copied data includes:

```txt
- Title
- Content
- Excerpt
- Featured image
- Custom fields
- Taxonomies
- Post type
```

The new draft title format is:

```txt
Original Title - Copy
```

---

## Bootstrap Design

The theme uses Bootstrap 5.

Bootstrap features used:

```txt
- Navbar
- Container
- Grid system
- Cards
- Buttons
- Badges
- Forms
- Spinner
- Pagination
```

Responsive layout:

```txt
Mobile: 1 column
Tablet: 2 columns
Desktop: 3 columns
```

---

## Theme File Structure

```txt
legal-case-results-theme/
│
├── style.css
├── functions.php
├── header.php
├── footer.php
├── index.php
├── front-page.php
├── archive-case_result.php
├── single-case_result.php
├── README.md
│
└── assets/
    ├── css/
    │   └── custom.css
    └── js/
        └── case-results.js
```

---

## Important WordPress Hooks Used

Custom post type registration:

```php
add_action('init', 'lcr_register_case_results_cpt');
```

Theme setup:

```php
add_action('after_setup_theme', 'lcr_theme_setup');
```

Asset loading:

```php
add_action('wp_enqueue_scripts', 'lcr_enqueue_assets');
```

Metabox registration:

```php
add_action('add_meta_boxes', 'lcr_add_case_result_metaboxes');
```

Metabox save:

```php
add_action('save_post_case_result', 'lcr_save_case_result_meta');
```

AJAX filter:

```php
add_action('wp_ajax_lcr_filter_case_results', 'lcr_ajax_filter_case_results');
add_action('wp_ajax_nopriv_lcr_filter_case_results', 'lcr_ajax_filter_case_results');
```

Duplicate action:

```php
add_action('admin_action_lcr_duplicate_post', 'lcr_duplicate_post_as_draft');
```

Admin row action filters:

```php
add_filter('post_row_actions', 'lcr_add_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'lcr_add_duplicate_post_link', 10, 2);
```

Body class filter:

```php
add_filter('body_class', 'lcr_archive_body_class');
```

---

## Database Meta Keys

The theme stores case result data in WordPress post meta.

Meta keys:

```txt
_lcr_case_type
_lcr_settlement_amount
_lcr_case_duration
_lcr_client_city
_lcr_client_state
_lcr_case_year
```

---

## Query Optimization Notes

The case result query uses:

```php
'update_post_meta_cache' => true
```

This helps avoid repeated meta queries while rendering cards.

The high-value case query uses:

```php
'no_found_rows' => true
```

This improves performance because pagination is not required for that section.

The settlement filter uses:

```php
'type' => 'NUMERIC'
```

This ensures settlement amount comparisons work correctly.

---

## Installation Steps

### Step 1: Upload Theme

Upload the theme folder to:

```txt
wp-content/themes/legal-case-results-theme/
```

Or upload the ZIP from:

```txt
Dashboard > Appearance > Themes > Add New > Upload Theme
```

---

### Step 2: Activate Theme

Go to:

```txt
Dashboard > Appearance > Themes
```

Activate:

```txt
Legal Case Results Bootstrap Theme
```

---

### Step 3: Refresh Permalinks

Go to:

```txt
Dashboard > Settings > Permalinks
```

Click:

```txt
Save Changes
```

This refreshes WordPress rewrite rules.

---

### Step 4: Add Case Results

Go to:

```txt
Dashboard > Case Results > Add New
```

Add the following data:

```txt
- Title
- Content
- Excerpt
- Featured Image
- Case Type
- Settlement Amount
- Case Duration
- Client City
- Client State
- Case Year
```

Publish the case result.

---

### Step 5: View Archive Page

Open:

```txt
/case-results/
```

---

## Testing Checklist

Test the following:

```txt
- Case Results menu appears in WordPress admin
- Metabox fields display correctly
- Case Type dropdown saves correctly
- Client State dropdown saves state code correctly
- Settlement amount displays as currency
- Homepage shows case results
- Archive page works at /case-results/
- Case Type filter works
- State filter works
- Case Type + State filter works together
- AJAX pagination works
- Single case result page works
- Duplicate link creates a draft copy
- GTM dataLayer event fires on case result click
- Responsive layout works on mobile
```

---

## GTM Testing

Open the browser console and run:

```js
window.dataLayer
```

Click a case result.

Expected event:

```js
{
    event: 'case_result_view',
    case_type: 'Car Accident',
    settlement_amount: '150000'
}
```

---

## Troubleshooting

### Case Results archive shows 404

Go to:

```txt
Dashboard > Settings > Permalinks
```

Click:

```txt
Save Changes
```

---

### AJAX filter is not working

Check that this file is loading:

```txt
assets/js/case-results.js
```

Check browser console for:

```js
lcr_ajax
```

If it is undefined, check the `wp_localize_script()` code in `functions.php`.

---

### State filter is not working

The metabox stores the state code only.

Correct stored value:

```txt
CA
```

Incorrect stored value:

```txt
California
```

---

### GTM event is not showing

The theme only pushes the event to:

```js
window.dataLayer
```

Google Tag Manager must be installed separately on the website.

---

### Duplicate link is not visible

Check that the current user has permission to edit posts.

Also check that the post type is one of:

```txt
post
page
case_result
```

---

## Final Summary

This Exercise 1 project demonstrates a complete custom WordPress theme for legal case results.

It includes:

```txt
- Custom post type
- Custom metabox fields
- Proper validation
- Proper sanitization
- Bootstrap design
- AJAX filtering
- Bootstrap pagination
- Schema.org structured data
- GTM event tracking
- Duplicate post feature
- Marketing-focused architecture
```

The implementation avoids custom field plugins and keeps the main functionality inside the custom theme.
