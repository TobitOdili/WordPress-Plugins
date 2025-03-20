# Blog Info WP-CLI Guide

This guide explains how to manage Blog Info fields using WP-CLI. All field values are stored in WordPress options table under the `esm_meta_fields` key.

## Available Fields

The following fields are available for modification:

### Brand Info Fields
| Field ID           | Type     | Description                    |
|-------------------|----------|--------------------------------|
| brand_name        | text     | Your brand or blog name        |
| blog_niche        | text     | Your blog niche or category    |
| about_us_sidebar  | textarea | About your blog (sidebar)      |
| legal_info_sidebar| textarea | Legal disclaimers (sidebar)    |

### Welcome Page Fields
| Field ID                  | Type     | Description                         |
|--------------------------|----------|-------------------------------------|
| welcome_intro            | textarea | Introduction section                |
| welcome_recommendations  | textarea | Recommendations section            |
| welcome_contact         | textarea | Contact section                    |

### Social Media Fields
| Field ID           | Type     | Description                    |
|-------------------|----------|--------------------------------|
| facebook_url      | text     | Your Facebook page URL         |
| twitter_url       | text     | Your Twitter/X profile URL     |
| youtube_url       | text     | Your YouTube channel URL       |
| instagram_url     | text     | Your Instagram profile URL     |

### Image Fields
| Field ID           | Type     | Description                    |
|-------------------|----------|--------------------------------|
| get_started_image | image    | Featured image for Get Started section |
| category_1_image  | image    | Featured image for category 1  |
| category_2_image  | image    | Featured image for category 2  |
| category_3_image  | image    | Featured image for category 3  |
| category_4_image  | image    | Featured image for category 4  |
| category_5_image  | image    | Featured image for category 5  |
| category_6_image  | image    | Featured image for category 6  |
| category_7_image  | image    | Featured image for category 7  |
| category_8_image  | image    | Featured image for category 8  |

### Page Content Fields
| Field ID           | Type     | Description                    |
|-------------------|----------|--------------------------------|
| about_page        | wysiwyg  | Main content for the About page |
| contact_page      | wysiwyg  | Main content for the Contact page |
| faq_page          | wysiwyg  | Main content for the FAQ page  |
| partnerships_page | wysiwyg  | Main content for the Partnerships page |
| guest_post_page   | wysiwyg  | Main content for the Guest Post page |
| do_not_sell_page  | wysiwyg  | Main content for the Do Not Sell My Info page |
| terms_page        | wysiwyg  | Main content for the Terms of Service page |

## Using Fields in Elementor

### Text Fields
Text-based fields (text, textarea, wysiwyg) can be used in any text widget by:
1. Clicking the dynamic data icon
2. Selecting "Blog Info Field"
3. Choosing your desired field
4. Optionally setting before/after text and fallback value

### Image Fields
Image fields can be used in any image widget by:
1. Clicking the dynamic data icon
2. Selecting "Blog Info Image"
3. Choosing your desired image field
4. Optionally setting a fallback image

## Reading Field Values

To read all field values:

```bash
wp option get esm_meta_fields --format=json
```

To get a specific field value (e.g., brand name):

```bash
wp eval "print_r(get_option('esm_meta_fields')['brand_name']['value']);"
```

For image fields, to get the image URL:

```bash
wp eval "print_r(wp_get_attachment_url(get_option('esm_meta_fields')['category_1_image']['value']));"
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

For image fields, you need to provide the attachment ID:

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['category_1_image']['value'] = 123; // Replace 123 with actual attachment ID
update_option('esm_meta_fields', \$fields);
"
```

### Method 2: Update Multiple Fields

To update multiple fields at once:

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['brand_name']['value'] = 'Your Brand Name';
\$fields['blog_niche']['value'] = 'Technology';
\$fields['welcome_intro']['value'] = 'Welcome to our blog...';
\$fields['welcome_recommendations']['value'] = 'We recommend...';
\$fields['welcome_contact']['value'] = 'Contact us at...';
\$fields['category_1_image']['value'] = 123; // Replace 123 with actual attachment ID
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
  "welcome_intro": {
    "id": "welcome_intro",
    "label": "Welcome Page Introduction",
    "type": "textarea",
    "value": "Welcome to our blog...",
    "description": "Introduction section of the welcome page"
  },
  "category_1_image": {
    "id": "category_1_image",
    "label": "Category 1 Featured Image",
    "type": "image",
    "value": "123",
    "description": "Featured image for category 1"
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
4. For image fields, the value should be a valid WordPress attachment ID
5. WYSIWYG fields (page contents) support HTML content
6. The option is stored in WordPress's options table with autoload enabled

## Examples

### Update Welcome Page Content

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['welcome_intro']['value'] = 'Welcome to our amazing blog!';
\$fields['welcome_recommendations']['value'] = 'Check out our top posts...';
\$fields['welcome_contact']['value'] = 'Get in touch with us...';
update_option('esm_meta_fields', \$fields);
"
```

### Update Brand Info and Social Media

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['brand_name']['value'] = 'Awesome Blog';
\$fields['blog_niche']['value'] = 'Technology';
\$fields['about_us_sidebar']['value'] = 'We are a tech blog...';
\$fields['facebook_url']['value'] = 'facebook.com/awesomeblog';
\$fields['twitter_url']['value'] = 'twitter.com/awesomeblog';
update_option('esm_meta_fields', \$fields);
"
```

### Update Page Content

```bash
wp eval "
\$fields = get_option('esm_meta_fields');
\$fields['about_page']['value'] = '<h2>About Us</h2><p>Your HTML content here...</p>';
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