# RoomKey – PG & Hostel Finder (PHP + MySQL)

Live site: [https://pgfinder.gt.tc/](https://pgfinder.gt.tc/)

## Run it locally (XAMPP / WAMP / Laragon)
1. Copy the `pg-finder` folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open http://localhost/phpmyadmin -> Import -> choose `database.sql` -> Go.
4. If your MySQL has a password, edit `config.php`.
5. Visit http://localhost/pg-finder/

## Features
- Students: search by city/area, type (PG/Hostel), boys/girls, max rent; view details; call or WhatsApp the owner. No login needed.
- Owners: register, log in, add / edit / delete listings, upload a photo, mark rooms full.

## Files
| File | Purpose |
|---|---|
| database.sql | Tables: users, listings |
| config.php | DB connection, session, helper functions, CSRF |
| index.php | Home + search + results |
| listing.php | Single listing details |
| register.php / login.php / logout.php | Owner accounts |
| dashboard.php | Owner's listings |
| add_listing.php / edit_listing.php / delete_listing.php | CRUD |
| includes/ | Header, footer, shared form, validation + upload |
| assets/style.css | All styling |
| uploads/ | Listing photos (PHP execution blocked) |

## Security used (good to mention in viva)
PDO prepared statements (SQL injection), password_hash/password_verify, htmlspecialchars (XSS), CSRF tokens, session_regenerate_id, MIME-checked image uploads, ownership checks on edit/delete.

## Ideas to extend
Admin approval of listings, multiple photos, reviews/ratings, map location, favourites, pagination.
