const express = require('express');
const router = express.Router();
const db = require('../config/database');

// Get all data and customization settings
router.get('/', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT * FROM website_data');
    const [settings] = await db.query('SELECT * FROM website_customization ORDER BY category, setting_name');
    
    // Group settings by category
    const groupedSettings = settings.reduce((acc, setting) => {
      if (!acc[setting.category]) {
        acc[setting.category] = [];
      }
      acc[setting.category].push(setting);
      return acc;
    }, {});
    
    res.render('admin/index', { 
      data: rows, 
      settings: groupedSettings,
      query: req.query 
    });
  } catch (error) {
    console.error('Error fetching data:', error);
    res.status(500).json({ message: error.message });
  }
});

// Add new data
router.post('/add', async (req, res) => {
  try {
    const { title, content } = req.body;
    await db.query('INSERT INTO website_data (title, content) VALUES (?, ?)', [title, content]);
    res.redirect('/admin');
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

// Update customization settings
router.post('/customize', async (req, res) => {
  try {
    const updates = req.body;
    
    // Update each setting
    for (const [settingName, settingValue] of Object.entries(updates)) {
      await db.query(
        'UPDATE website_customization SET setting_value = ?, updated_at = NOW() WHERE setting_name = ?',
        [settingValue, settingName]
      );
    }
    
    res.redirect('/admin?success=1');
  } catch (error) {
    console.error('Error updating customization settings:', error);
    res.redirect('/admin?error=1');
  }
});

// Get current customization settings as CSS
router.get('/styles.css', async (req, res) => {
  try {
    const [settings] = await db.query('SELECT setting_name, setting_value FROM website_customization');
    
    // Convert settings to CSS variables
    const cssVariables = settings.map(setting => 
      `  --${setting.setting_name.replace(/_/g, '-')}: ${setting.setting_value};`
    ).join('\n');
    
    const css = `
:root {
${cssVariables}
}

/* Apply customization variables */
body {
  background-color: var(--background-color);
  color: var(--text-color);
  font-family: var(--font-family);
  font-size: var(--font-size, 16)px;
}

header {
  background-color: var(--header-background-color);
  color: var(--header-text-color);
}

.btn-primary {
  background-color: var(--button-background-color);
  color: var(--button-text-color);
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:hover {
  opacity: 0.9;
}

a {
  color: var(--link-color);
  text-decoration: none;
}

a:hover {
  color: var(--link-hover-color);
}

/* Admin panel specific styles */
.admin-panel {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.customization-form {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.color-input {
  width: 60px;
  height: 40px;
  padding: 0;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.category-section {
  border: 1px solid #e9ecef;
  border-radius: 8px;
  margin-bottom: 20px;
  overflow: hidden;
}

.category-header {
  background-color: #e9ecef;
  padding: 15px;
  font-weight: bold;
  text-transform: capitalize;
  border-bottom: 1px solid #dee2e6;
}

.category-content {
  padding: 20px;
}

.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.alert {
  padding: 12px 20px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.preview-section {
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-top: 20px;
}

.preview-header {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 15px;
  color: var(--header-text-color);
  background-color: var(--header-background-color);
  padding: 10px;
  border-radius: 4px;
}

.preview-content {
  color: var(--text-color);
  line-height: 1.6;
}

.preview-button {
  background-color: var(--button-background-color);
  color: var(--button-text-color);
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  margin: 10px 5px 0 0;
  cursor: pointer;
}

.preview-link {
  color: var(--link-color);
  text-decoration: underline;
}

.preview-link:hover {
  color: var(--link-hover-color);
}
`;
    
    res.setHeader('Content-Type', 'text/css');
    res.send(css);
  } catch (error) {
    console.error('Error generating CSS:', error);
    res.status(500).send('/* Error generating CSS */');
  }
});

// Reset customization to defaults
router.post('/customize/reset', async (req, res) => {
  try {
    const defaultSettings = [
      ['background_color', '#ffffff'],
      ['text_color', '#333333'],
      ['header_background_color', '#f8f9fa'],
      ['header_text_color', '#212529'],
      ['button_background_color', '#007bff'],
      ['button_text_color', '#ffffff'],
      ['link_color', '#007bff'],
      ['link_hover_color', '#0056b3'],
      ['font_family', 'Arial, sans-serif'],
      ['font_size', '16'],
      ['site_title', 'My Website'],
      ['enable_dark_mode', 'false']
    ];
    
    for (const [name, value] of defaultSettings) {
      await db.query(
        'UPDATE website_customization SET setting_value = ?, updated_at = NOW() WHERE setting_name = ?',
        [value, name]
      );
    }
    
    res.redirect('/admin?reset=1');
  } catch (error) {
    console.error('Error resetting customization settings:', error);
    res.redirect('/admin?error=1');
  }
});

module.exports = router;