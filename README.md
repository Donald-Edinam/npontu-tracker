# Npontu Daily Activity Tracker

A Laravel 11 + Livewire 3 (Volt functional style) web application built for Npontu Technologies to track daily activities, shift handovers, and historical reporting for applications support team members.

---

## Setup & Local Installation

Follow these steps to set up and run the application locally from scratch:

```bash
# 1. Clone the repository
git clone https://github.com/Donald-Edinam/npontu-tracker.git
cd npontu-tracker

# 2. Install PHP & Node dependencies
composer install
npm install && npm run build

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run database migrations and seed default data
php artisan migrate --seed

# 5. Start local development server
php artisan serve
```

Access the application at `http://127.0.0.1:8000`.

---

## Demo Credentials

The database seeder provisions default accounts with pre-assigned Spatie roles and seeded sample activities:

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@npontu.test` | `password` | Activity Catalog Management (`/activities`), Today's Board (`/today`), Reports (`/reports`) |
| **Support Agent** | `agent@npontu.test` | `password` | Today's Board (`/today`), Reports (`/reports`), Read-only Catalog (`/activities`) |

---

## Data Model Rationale

The system architecture utilizes a three-table normalized database split to guarantee audit compliance and efficient shift handovers:

1. **`activities` (Catalog)**: Stores master activity templates (name, description, type, category, active status).
2. **`daily_activity_entries` (Daily State)**: Stores one row per activity per calendar day holding the current status (`pending` or `done`), expected metrics, actual metrics, and assigned agent.
3. **`activity_update_logs` (Append-Only Audit Log)**: Immutable audit trail recording every state update, status transition (`old_status` → `new_status`), timestamp, user ID (`updated_by`), and remark/note (`const UPDATED_AT = null;`).

This design separates volatile daily status from append-only activity histories, enabling fast daily status lookups while maintaining a complete, unalterable historical log without requiring auxiliary audit tables.

---

## Explicit Interpretation Calls

1. **Checklist vs. Metric Activity Types**:
   - To support quantitative operational tasks (e.g., *"Daily SMS count in comparison to SMS count from logs"*), activities are classified as either `checklist` or `metric`. Metric activities prompt support personnel for numeric actual values and automatically compute variance against expected targets.
2. **Integrated Shift Handover & Timeline View**:
   - Rather than isolating update histories on a separate page, shift handover notes and complete timeline histories are folded directly into the `/today` board. Support personnel can view the latest note directly on pending cards or inspect the full audit trail inside the update modal.
3. **Role-Based Access Control on Catalog**:
   - The Activity Catalog (`/activities`) is read-visible to all authenticated users so support agents understand activity scope, but mutating operations (creating, editing, toggling active state) are strictly authorized for users with the `admin` role via `ActivityPolicy`.

---

## Application Screenshots

- **Today's Activity Board (`/today`)**: Daily tracking dashboard showing pending and completed activities, bento stats cards, and handover note previews.
- **Update Modal & Shift Handover History**: Interactive dialog for setting status, numeric actual values, remarks, and inspecting prior update history.
- **Reporting & Audit Analytics (`/reports`)**: Date-filtered historical audit logs and daily completion metrics.

---

## License

This project is open-sourced software under the [MIT license](https://opensource.org/licenses/MIT).
