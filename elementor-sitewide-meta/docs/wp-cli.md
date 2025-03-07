# Blog Info WP-CLI Guide

This guide explains how to manage Blog Info fields using WP-CLI. All field values are stored in WordPress options table under the `esm_meta_fields` key.

## Available Fields

The following fields are available for modification:

| Field ID       | Type | Description                    |
|---------------|------|--------------------------------|
| brand_name    | text | Your brand or blog name        |
| blog_niche    | text | Your blog niche or category    |
| facebook_url  | url  | Your Facebook page URL         |
| twitter_url   | url  | Your Twitter/X profile URL     |
| youtube_url   | url  | Your YouTube channel URL       |
| instagram_url | url  | Your Instagram profile URL     |

## Reading Field Values

To read all field values:

```bash
wp option get esm_meta_fields --format=json
```

To get a specific field value (e.g., brand name):

```bash
wp eval "print_r(get_option('esm_meta_fields')['brand_name']['value']);"
```

## Updating Field Values

### Method 1: Update Single Field

To update a single field value, use this WP-CLI command:

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['brand_name']['value'] = 'Your Brand Name';
update_option('esm_meta_fields', \$fields);
"
```

Replace `brand_name` with any field ID and adjust the value accordingly.

### Method 2: Update Multiple Fields

To update multiple fields at once:

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['brand_name']['value'] = 'Your Brand Name';
\$fields['blog_niche']['value'] = 'Technology';
\$fields['facebook_url']['value'] = 'https://facebook.com/yourbrand';
update_option('esm_meta_fields', \$fields);
"
```

### Method 3: Update via JSON

Create a JSON file (e.g., `fields.json`):

```json
{
  "brand_name": {
    "id": "brand_name",
    "label": "Brand Name",
    "type": "text",
    "value": "Your Brand Name",
    "description": "Your brand or blog name (text)"
  },
  "blog_niche": {
    "id": "blog_niche",
    "label": "Blog Niche",
    "type": "text",
    "value": "Technology",
    "description": "Your blog niche or category (text)"
  }
}
```

Then update using:

```bash
wp option update esm_meta_fields "$(cat fields.json)" --format=json
```

## Reset All Values

To reset all field values to empty strings:

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
foreach (\$fields as \$key => \$field) {
    \$fields[\$key]['value'] = '';
}
update_option('esm_meta_fields', \$fields);
"
```

## Important Notes

1. All changes made via WP-CLI will immediately reflect in the WordPress dashboard
2. Field structure (id, label, type, description) should not be modified
3. Only the `value` property should be updated
4. URL fields should contain valid URLs
5. The option is stored in WordPress's options table with autoload enabled

## Examples

### Update Brand Name and Social Media URLs

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['brand_name']['value'] = 'Awesome Blog';
\$fields['facebook_url']['value'] = 'https://facebook.com/awesomeblog';
\$fields['twitter_url']['value'] = 'https://twitter.com/awesomeblog';
update_option('esm_meta_fields', \$fields);
"
```

### Export Current Values

```bash
wp option get esm_meta_fields --format=json > blog_info_backup.json
```

### Import Values from Backup

```bash
wp option update esm_meta_fields "$(cat blog_info_backup.json)" --format=json
```