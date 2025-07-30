const express = require('express');
const bodyParser = require('body-parser');
const path = require('path');
require('dotenv').config();

const app = express();

// Middleware
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Serve static files
app.use('/static', express.static(path.join(__dirname, 'public')));

// Routes
const adminRoutes = require('./routes/admin');
app.use('/admin', adminRoutes);

// Root route redirect to admin
app.get('/', (req, res) => {
  res.redirect('/admin');
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
  console.log(`Admin panel: http://localhost:${PORT}/admin`);
});