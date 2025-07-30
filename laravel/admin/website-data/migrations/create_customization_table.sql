-- Create customization settings table
CREATE TABLE IF NOT EXISTS website_customization (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_name VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_type ENUM('color', 'text', 'number', 'boolean') DEFAULT 'text',
    category VARCHAR(50) DEFAULT 'general',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default customization settings
INSERT INTO website_customization (setting_name, setting_value, setting_type, category, description) VALUES
('background_color', '#ffffff', 'color', 'appearance', 'Main background color of the website'),
('text_color', '#333333', 'color', 'appearance', 'Primary text color'),
('header_background_color', '#f8f9fa', 'color', 'appearance', 'Header background color'),
('header_text_color', '#212529', 'color', 'appearance', 'Header text color'),
('button_background_color', '#007bff', 'color', 'appearance', 'Primary button background color'),
('button_text_color', '#ffffff', 'color', 'appearance', 'Primary button text color'),
('link_color', '#007bff', 'color', 'appearance', 'Link color'),
('link_hover_color', '#0056b3', 'color', 'appearance', 'Link hover color'),
('font_family', 'Arial, sans-serif', 'text', 'typography', 'Primary font family'),
('font_size', '16', 'number', 'typography', 'Base font size in pixels'),
('site_title', 'My Website', 'text', 'general', 'Website title'),
('enable_dark_mode', 'false', 'boolean', 'general', 'Enable dark mode toggle')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);