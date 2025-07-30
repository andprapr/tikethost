# Website Data Admin Panel with Customization

A Node.js admin panel for managing website data with advanced customization features.

## Features

- **Website Data Management**: Add, view, and manage website content
- **Website Customization**: Change colors, typography, and appearance settings
- **Live Preview**: See changes in real-time before applying
- **Responsive Design**: Works on desktop and mobile devices
- **Database Storage**: All settings and data stored in MySQL database

## Installation

1. **Clone or navigate to the project directory**
   ```bash
   cd admin/website-data
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Set up database configuration**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` file with your database credentials:
   ```
   DB_HOST=localhost
   DB_USER=your_username
   DB_PASSWORD=your_password
   DB_NAME=website_data
   PORT=3000
   ```

4. **Set up the database**
   - Create a MySQL database named `website_data` (or your preferred name)
   - Run the migration script to create required tables:
   ```sql
   -- Run the contents of migrations/create_customization_table.sql
   ```

5. **Start the application**
   ```bash
   npm start
   ```
   Or for development with auto-restart:
   ```bash
   npm run dev
   ```

The admin panel will be available at: `http://localhost:3000/admin`

## Usage

### Accessing the Admin Panel

1. Open your browser and go to: `http://localhost:3000/admin`
2. The single page contains both:
   - Website data management (add/view content)
   - Website customization features

### Website Customization

The customization features are integrated directly into the main admin page:

1. Scroll down to the "🎨 Website Customization" section
2. Adjust appearance settings:
   - **Colors**: Background, text, header, buttons, and links
   - **Typography**: Font family and size
   - **General Settings**: Site title and features

3. **Live Preview**: See changes instantly in the preview panel on the right
4. **Save Changes**: Click "💾 Save Customization" to apply settings
5. **Reset**: Use "🔄 Reset to Defaults" to restore original settings

### Available Customization Options

#### Appearance Settings
- Background Color
- Text Color
- Header Background Color
- Header Text Color
- Button Background Color
- Button Text Color
- Link Color
- Link Hover Color

#### Typography Settings
- Font Family
- Font Size (in pixels)

#### General Settings
- Site Title
- Dark Mode Toggle (future feature)

## API Endpoints

- `GET /admin` - Main dashboard with data management and customization
- `POST /admin/add` - Add new website data
- `POST /admin/customize` - Save customization settings
- `POST /admin/customize/reset` - Reset to default settings
- `GET /admin/styles.css` - Generated CSS with custom variables

## Database Schema

### website_data Table
- `id` - Primary key
- `title` - Content title
- `content` - Content body
- `created_at` - Creation timestamp

### website_customization Table
- `id` - Primary key
- `setting_name` - Setting identifier
- `setting_value` - Setting value
- `setting_type` - Type (color, text, number, boolean)
- `category` - Setting category (appearance, typography, general)
- `description` - Setting description
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Customization Integration

To integrate the customization settings into your main website:

1. **Include the dynamic CSS**:
   ```html
   <link rel="stylesheet" href="http://localhost:3000/admin/styles.css">
   ```

2. **Use CSS custom properties** in your stylesheets:
   ```css
   body {
     background-color: var(--background-color);
     color: var(--text-color);
     font-family: var(--font-family);
   }
   ```

3. **Available CSS Variables**:
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

## Development

### Project Structure
```
admin/website-data/
├── app.js                 # Main application file
├── package.json           # Dependencies and scripts
├── .env.example          # Environment variables template
├── config/
│   └── database.js       # Database configuration
├── routes/
│   └── admin.js          # Admin routes and customization API
├── views/
│   └── admin/
│       ├── index.ejs     # Main dashboard
│       └── customize.ejs # Customization interface
├── migrations/
│   └── create_customization_table.sql
└── README.md
```

### Adding New Customization Options

1. **Add to database**: Insert new row in `website_customization` table
2. **Update interface**: Add form field in `customize.ejs`
3. **Update CSS generation**: Modify the CSS template in `admin.js`

## Security Considerations

- Input validation for all customization settings
- SQL injection prevention using parameterized queries
- XSS protection through EJS templating
- Consider adding authentication for production use

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check your `.env` file configuration
   - Ensure MySQL server is running
   - Verify database exists

2. **Port Already in Use**
   - Change the PORT in `.env` file
   - Kill existing processes on the port

3. **Customization Not Applying**
   - Check browser console for CSS loading errors
   - Verify the `/admin/styles.css` endpoint is accessible
   - Clear browser cache

## License

This project is open source and available under the MIT License.