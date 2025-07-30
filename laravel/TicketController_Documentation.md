# TicketController Documentation

## Overview
The TicketController handles all ticket-related functionality for the Event Hoki Talas89 application, including validation, session management, and admin operations.

## Available Routes

### Public Routes
- `GET /` - Display the main ticket page (home.blade.php)
- `POST /ticket/validate` - Validate a ticket code (AJAX)
- `GET /ticket/check/{kodeTicket}` - Check if a specific ticket is valid
- `GET /ticket/event` - Access event page (requires valid ticket in session)
- `POST /ticket/logout` - Clear ticket session
- `GET /ticket/status` - Get current session status

### Admin Routes
- `GET /admin/tickets` - Get all valid tickets
- `POST /admin/tickets/add` - Add a new valid ticket
- `DELETE /admin/tickets/remove` - Remove a ticket from valid list

## Controller Methods

### 1. `index()`
Returns the main home page view.

**Usage:**
```php
Route::get('/', [TicketController::class, 'index']);
```

### 2. `validateTicket(Request $request)`
Validates a ticket code submitted via AJAX.

**Parameters:**
- `kode_tiket` (string, required, max:10) - The ticket code to validate

**Response:**
```json
// Success
{
    "success": true,
    "message": "Tiket valid! Anda bisa melanjutkan ke event.",
    "redirect": "/ticket/event"
}

// Error
{
    "success": false,
    "message": "Kode yang Anda masukkan salah, harap hubungi Admin untuk mendapatkan kode tiket!"
}
```

### 3. `showEvent()`
Shows the event page for users with valid tickets in session.

**Requirements:**
- Valid ticket must be stored in session

### 4. `checkTicket(string $kodeTicket)`
Checks if a specific ticket code is valid.

**Response:**
```json
{
    "ticket": "123456",
    "is_valid": true,
    "message": "Tiket valid"
}
```

### 5. `addTicket(Request $request)`
Adds a new ticket to the valid tickets list (Admin function).

**Parameters:**
- `kode_tiket` (string, required, max:10, unique) - New ticket code

### 6. `removeTicket(Request $request)`
Removes a ticket from the valid tickets list (Admin function).

**Parameters:**
- `kode_tiket` (string, required, max:10) - Ticket code to remove

## Integration with Frontend

### JavaScript Example for Ticket Validation
```javascript
// Update the existing home.blade.php JavaScript
document.getElementById('submitTiket').addEventListener('click', function () {
    var kodeTiket = document.getElementById('kodeTiket').value;
    
    fetch('/ticket/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            kode_tiket: kodeTiket
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            document.getElementById('notice').style.display = 'block';
            document.getElementById('notice').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('notice').style.display = 'block';
    });
});
```

## Default Valid Tickets
The controller comes with these default valid tickets:
- `123456`
- `789012` 
- `345678`

## Security Features
- CSRF protection on all POST/DELETE routes
- Input validation on all requests
- Session-based access control for event page
- Admin routes can be protected with middleware (recommended)

## Future Enhancements
1. Move valid tickets to database instead of array
2. Add admin authentication middleware
3. Implement ticket expiration
4. Add logging for ticket validation attempts
5. Create admin dashboard for ticket management

## Database Migration (Future)
Consider creating a tickets table:
```sql
CREATE TABLE tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```