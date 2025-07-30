# Setup Instructions for Custom Website Feature

## Database Migration

To set up the website customization feature, you need to run the database migration:

```bash
php artisan migrate
```

This will create the `website_customization` table with the following structure:
- `id` - Primary key
- `setting_name` - Unique setting identifier
- `setting_value` - Setting value
- `setting_type` - Type of setting (color, text, number, boolean)
- `category` - Setting category (appearance, typography, general)
- `description` - Setting description
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Default Settings

The system will automatically initialize default settings when you first visit the Custom Website page:

### Appearance Settings
- Background Color: #ffffff
- Text Color: #333333
- Header Background Color: #f8f9fa
- Header Text Color: #212529
- Button Background Color: #007bff
- Button Text Color: #ffffff
- Link Color: #007bff
- Link Hover Color: #0056b3

### Typography Settings
- Font Family: Arial, sans-serif
- Font Size: 16px

### General Settings
- Site Title: My Website
- Enable Dark Mode: false

## Usage

1. **Access the Custom Website page**: Navigate to `/admin/custom-website` in the admin panel
2. **Customize settings**: Use the form to modify colors, typography, and general settings
3. **Live Preview**: See changes in real-time in the preview panel
4. **Save changes**: Click "Simpan Perubahan" to apply settings
5. **Reset to defaults**: Use "Reset ke Default" to restore original settings

## Integration with Frontend

To use the customization settings on your frontend, include the dynamic CSS:

```html
<link rel="stylesheet" href="/css/custom.css">
```

This will load CSS custom properties that you can use in your stylesheets:

```css
body {
  background-color: var(--background-color);
  color: var(--text-color);
  font-family: var(--font-family);
}

.header {
  background-color: var(--header-background-color);
  color: var(--header-text-color);
}

.btn-primary {
  background-color: var(--button-background-color);
  color: var(--button-text-color);
}

a {
  color: var(--link-color);
}

a:hover {
  color: var(--link-hover-color);
}
```

## Available CSS Variables

- `--background-color`
- `--text-color`
- `--header-background-color`
- `--header-text-color`
- `--button-background-color`
- `--button-text-color`
- `--link-color`
- `--link-hover-color`
- `--font-family`
- `--font-size`
- `--site-title`
- `--enable-dark-mode`