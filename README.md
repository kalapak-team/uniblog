# University Blog System

A comprehensive university blog system built with PHP and MySQL that allows publishing articles, managing content, and user interaction.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green)

## 🌟 Features

- **📝 Blog System**: Publish and manage blog posts with categories
- **👨‍💼 Admin Panel**: Full-featured admin interface for content management
- **👥 User Management**: Admin and author roles with different permissions
- **💬 Comment System**: User comments with moderation capabilities
- **🔍 Search Functionality**: Advanced search across posts, authors, and categories
- **📱 Responsive Design**: Mobile-friendly Bootstrap interface
- **🖼️ Image Uploads**: Support for featured images in posts
- **📂 Category Management**: Organize content by categories

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.3, HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6.4
- **Server**: Apache with mod_rewrite

## 📦 Installation

### Prerequisites
- Web server (Apache recommended)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (optional)

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/university-blog.git
   cd university-blog
   ```

2. **Create database**
   ```sql
   CREATE DATABASE uniblog;
   ```

3. **Import database schema**
   ```bash
   mysql -u yourusername -p uniblog < database/unibog.sql
   ```

4. **Configure database connection**
   Edit `config/db.php` with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'uniblog');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

5. **Set up file permissions**
   ```bash
   chmod 755 uploads/
   chmod 644 config/db.php
   ```

6. **Configure website settings**
   Update SITE_URL in `config/db.php`:
   ```php
   define('SITE_URL', 'http://yourdomain.com/uniblog');
   ```

7. **Access the application**
   - Frontend: http://yourdomain.com/uniblog
   - Admin Panel: http://yourdomain.com/uniblog/admin
   - Default admin login: admin / password123

## 📁 Complete Project Structure

```
university-blog/
├── admin/                          # Admin Panel
│   ├── includes/                   # Admin includes
│   │   ├── header.php              # Admin header
│   │   ├── footer.php              # Admin footer
│   │   └── sidebar.php             # Admin sidebar navigation
│   ├── categories.php              # Category management
│   ├── comments.php                # Comment moderation
│   ├── dashboard.php               # Admin dashboard
│   ├── index.php                   # Admin login
│   ├── logout.php                  # Logout script
│   ├── posts.php                   # Post management
│   └── users.php                   # User management
├── assets/                         # Static assets
│   ├── css/
│   │   └── style.css               # Custom styles
│   ├── images/
│   │   └── campus-hero.jpg         # Default hero image
│   └── js/
│       └── script.js               # Custom JavaScript
├── config/                         # Configuration files
│   └── db.php                      # Database configuration
├── database/                       # Database files
│   └── unibog.sql                  # Database schema and sample data
├── includes/                       # Frontend includes
│   ├── footer.php                  # Site footer
│   └── header.php                  # Site header
├── uploads/                        # Uploaded images directory
│   ├── 1757775608.php.png
│   ├── 1757775761_python.png
│   ├── 1757776054_java.png
│   └── ... (other uploaded images)
├── .htaccess                       # Apache rewrite rules
├── about.php                       # About page
├── blog.php                        # Blog listing page
├── categories.php                  # Category view page
├── contact.php                     # Contact page
├── index.php                       # Homepage
├── post.php                        # Single post view
├── search.php                      # Search results page
└── README.md                       # This file
```

## 👥 User Roles

### Admin
- Full access to all features
- Manage users, posts, categories, and comments
- Approve/reject comments
- Edit all content

### Author
- Create and edit their own posts
- Upload images
- Manage their own content

## 📝 Usage

### Creating Posts
1. Log in to the admin panel
2. Navigate to Posts → Add New
3. Fill in title, content, excerpt, and category
4. Upload featured image (optional)
5. Set status to "Published" or "Draft"
6. Click "Create Post"

### Managing Comments
1. Go to Comments in admin panel
2. Review pending comments
3. Approve, mark as spam, or delete comments
4. Approved comments appear on the blog

### Adding Categories
1. Navigate to Categories in admin panel
2. Click "Add New Category"
3. Enter category name and description
4. Assign posts to categories when creating/editing

## 🔧 Configuration

### Email Settings
To enable email notifications, update the contact form processing in `contact.php` with your SMTP settings.

### File Uploads
- Maximum file size: 2MB
- Allowed types: JPG, PNG, GIF
- Upload directory: `uploads/`

### Security Features
- Prepared statements for SQL queries
- Input validation and sanitization
- Password hashing
- Session management
- File upload restrictions

## 🚀 Deployment

### Production Deployment Checklist

1. **Environment Configuration**
   ```bash
   # Disable error reporting in production
   # In config/db.php, add:
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

2. **Security Hardening**
   ```bash
   # Set proper file permissions
   chmod 644 config/db.php
   chmod 755 uploads/
   chmod 644 .htaccess
   ```

3. **Database Backup**
   ```bash
   # Create backup script
   mysqldump -u username -p uniblog > backup-$(date +%Y%m%d).sql
   ```

4. **Performance Optimization**
   - Enable Gzip compression
   - Implement caching headers
   - Optimize images

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/db.php`
   - Verify MySQL server is running

2. **File Upload Issues**
   - Check `uploads/` directory permissions
   - Verify PHP file_uploads setting is enabled

3. **Page Not Found Errors**
   - Ensure mod_rewrite is enabled
   - Check .htaccess file exists

4. **Admin Login Issues**
   - Verify user exists in database
   - Check password hashing

### Debug Mode
Enable debug mode by editing `config/db.php`:
```php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Use meaningful commit messages
- Test changes thoroughly
- Update documentation when needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Bootstrap](https://getbootstrap.com/) for the responsive frontend framework
- [Font Awesome](https://fontawesome.com/) for the icon library
- PHP community for excellent documentation

## 📞 Support

For support and questions:
- 📧 Email: support@yourdomain.com
- 🐛 Create an issue on GitHub
- 📚 Documentation: [Wiki](https://github.com/yourusername/university-blog/wiki)

## 🔄 Changelog

### v1.0.0 (2024-01-01)
- Initial release
- Complete blog system with admin panel
- User management and comment system
- Search functionality
- Responsive design

---

## 📊 Database Schema

The system uses the following main tables:

- **users**: User accounts and authentication
- **posts**: Blog posts and content
- **categories**: Post categorization
- **comments**: User comments on posts

For detailed database structure, see `database/unibog.sql`.

---

**Note**: Remember to change all placeholder values (yourdomain.com, yourusername, etc.) with your actual information before deploying.

⭐ **If you find this project useful, please give it a star on GitHub!**