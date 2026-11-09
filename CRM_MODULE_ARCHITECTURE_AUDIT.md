# 📊 Laravel Portfolio CRM — Complete Architecture, Flow & Audit Report

> **Document Version:** 1.0.0  
> **Environment:** Laravel 11 / PHP 8.2 / MySQL / Tailwind CSS / Alpine.js  
> **Route Base:** `/admin/crm`  
> **Target Audience:** System Architects, Developers, ChatGPT & LLM Context Feeding  

---

## 1. Executive Summary & Core Objective

The **Portfolio CRM Module** is a custom, zero-dependency, production-grade Client Relationship Management and Financial Accounting engine built natively inside the Laravel portfolio project.

It is specifically engineered to solve the **"Multi-Currency Revenue Guessing"** problem faced by freelancers and agencies working across international markets (primarily **India [INR ₹]**, **Dubai / UAE [AED]**, and **Global / USA [USD $]**).

### Primary Capabilities:
1. **Multi-Channel Lead Pipeline (Kanban & Table Views):** Tracks outreach across WhatsApp, Instagram, Email, and Phone with live status updates (`New Lead` → `Contacted` → `Proposal` → `Won` → `Lost`).
2. **Zero-Guessing Earnings Engine:**
   - Native currencies are preserved at all times without artificial blending.
   - **Permanent FX Rate Locking:** Every received payment locks the exact transaction exchange rate on the day of receipt. Future fluctuations in foreign exchange never alter past realized earnings.
3. **Automated Won Deal Settlement:** Marking a deal as **Won (Closed)** mandates an upfront received settlement (full or advance), instantly creating a ledger entry in `crm_payments` and transitioning the deal to **`Paid`** (if 100% settled) or **`Partial`** (if balance is pending).
4. **Permanent Status Guard:** Once a deal is finalized as **`Won`** or **`Lost`**, its status is permanently locked in the database and UI to prevent accidental modification or financial corruption.
5. **Google Sheets 2-Way Sync Engine:** Background asynchronous jobs (`SyncLeadToGoogleSheetJob`, `SyncPaymentToGoogleSheetJob`) sync lead details, earnings dashboards, and payment ledgers with Google Sheets without slowing down web requests.

---

## 2. System Architecture & Database Schema

The module uses 4 dedicated database tables with zero third-party package dependencies:

```
+---------------------------------------------------------------+
|                          crm_leads                            |
+---------------------------------------------------------------+
| id                   : bigint unsigned (PK)                   |
| business_name        : varchar(255) UNIQUE                    |
| market               : enum('India', 'Dubai', 'Other')        |
| service              : enum('Website', 'Laravel', 'Automation')|
| contact_channel      : enum('WhatsApp', 'Instagram', 'Email', |
|                             'Call')                           |
| website_url          : varchar(255) nullable                  |
| problem_found        : text nullable                          |
| message_sent         : boolean (default 0)                    |
| response             : enum('No response', 'Interested',      |
|                             'Not interested')                 |
| follow_up_date       : date nullable                          |
| status               : enum('New', 'Contacted', 'Proposal',   |
|                             'Won', 'Lost')                    |
| currency             : enum('INR', 'AED', 'USD')              |
| deal_amount          : decimal(12,2) default 0.00             |
| notes                : text nullable                          |
| created_at           : timestamp                              |
| updated_at           : timestamp                              |
| deleted_at           : timestamp (SoftDeletes)                |
+---------------------------------------------------------------+
                               | 1
                               | hasMany (ordered by paid_on desc)
                               | *
+---------------------------------------------------------------+
|                         crm_payments                          |
+---------------------------------------------------------------+
| id                   : bigint unsigned (PK)                   |
| lead_id              : bigint unsigned (FK -> crm_leads.id)   |
| paid_on              : date                                   |
| currency             : varchar(10) (INR / AED / USD)          |
| amount               : decimal(12,2) (in native currency)     |
| fx_rate              : decimal(10,4) (locked rate to INR)     |
| received_inr         : decimal(12,2) (amount * fx_rate)       |
| note                 : text nullable                          |
| created_at           : timestamp                              |
| updated_at           : timestamp                              |
+---------------------------------------------------------------+

+---------------------------------------------------------------+
|                         crm_settings                          |
+---------------------------------------------------------------+
| id                   : bigint unsigned (PK)                   |
| key                  : varchar(100) UNIQUE                    |
| value                : text nullable                          |
| created_at           : timestamp                              |
| updated_at           : timestamp                              |
+---------------------------------------------------------------+

+---------------------------------------------------------------+
|                        crm_sync_logs                          |
+---------------------------------------------------------------+
| id                   : bigint unsigned (PK)                   |
| entity_type          : varchar(50) (lead / payment)           |
| entity_id            : bigint unsigned                        |
| action               : varchar(50) (create / update / delete) |
| status               : enum('pending', 'success', 'failed')   |
| payload              : json nullable                          |
| error_message        : text nullable                          |
| created_at           : timestamp                              |
+---------------------------------------------------------------+
```

---

## 3. Financial Model & Dynamic Invariants

### 3.1. Currency Invariants
- **`INR` (Indian Rupee):** Invariant FX Rate = `1.0000` (always fixed, no conversion fee or spread).
- **`AED` (UAE Dirham):** Stored in native AED. Default baseline = `23.0000` INR/AED (cached / configurable in CRM Settings).
- **`USD` (US Dollar):** Stored in native USD. Default baseline = `86.0000` INR/USD (cached / configurable in CRM Settings).

### 3.2. Dynamic Attributes (Calculated on the fly in `CrmLead`)
No financial aggregates (`amount_received`, `pending_payment`, `payment_status`) are stored as static numbers in `crm_leads`. Instead, they are calculated dynamically via Eloquent accessors:

1. **Amount Received (`amount_received`):**
   $$\text{Amount Received} = \sum_{\text{payments}} \text{amount}$$
2. **Total Realized INR (`total_received_inr`):**
   $$\text{Total Realized INR} = \sum_{\text{payments}} \text{received\_inr}$$
3. **Pending Balance (`pending_payment`):**
   $$\text{Pending Balance} = \max(0, \text{deal\_amount} - \text{amount\_received})$$
4. **Payment Status (`payment_status`):**
   $$\text{Payment Status} = \begin{cases} 
   \text{'Pending'} & \text{if } \text{amount\_received} = 0 \\
   \text{'Paid'} & \text{if } \text{deal\_amount} > 0 \text{ and } \text{amount\_received} \ge \text{deal\_amount} \\
   \text{'Partial'} & \text{if } 0 < \text{amount\_received} < \text{deal\_amount}
   \end{cases}$$

### 3.3. Overpayment Guard
When entering a payment in `CrmPaymentStoreRequest`:
$$\text{amount} \le \text{pending\_payment}$$
Any payment attempt exceeding the outstanding balance is strictly blocked at the validator level with an error message: *"Payment amount exceeds the remaining pending balance"*.

---

## 4. End-to-End User Workflows

### Workflow A: Lead Capture & Outreach Pipeline
**Route:** `GET /admin/crm/leads`

1. **Default View:** Defaults to **Table View** (can be toggled to **Kanban Board** via top switcher).
2. **Quick Add Bar:**
   - Inputs: Business Name (required, unique), Market, Service, Channel, Website (optional), Problem/Need (optional), Currency, Deal Amount (optional, blank by default), Pipeline Stage (`New Lead`, `Contacted`, `Proposal`), `+ Add Row` button.
   - Submits directly to `POST /admin/crm/leads`.
3. **Add New Lead Modal:**
   - Full modal with inputs for Client Profile, Problem Found, Currency, blank Deal Amount, Follow-up date, Notes, Outreach message sent checkbox, Lead Response (`No response`, `Interested`, `Not interested`), and **Pipeline Stage dropdown** (`New Lead`, `Contacted`, `Proposal`).
4. **Pipeline Status Rules:**
   - For leads in `New`, `Contacted`, or `Proposal`, the status dropdown in the table is active and clickable.
   - For leads in **`Won`** or **`Lost`**, the status dropdown is **permanently disabled (`opacity-70 cursor-not-allowed`)** with tooltip *"Won/Lost status is permanently locked"*.
   - In Kanban view, cards in **`Won`** and **`Lost`** columns have drag-and-drop disabled (`draggable="false"`, `cursor-default`).

---

### Workflow B: Deal Closing & Won Confirmation Modal
**Trigger:**
- Table View: Changing status dropdown to **"Won (Closed)"**.
- Kanban View: Dragging a card to the **"Won"** column or clicking the **"Won →"** advance button.

1. **Modal Interception:**
   - The status change is **not** immediately sent. Instead, the interactive **Won Confirmation Modal** opens.
   - If the user cancels the modal, the dropdown reverts back to its previous status in the DOM.
2. **Modal Form Fields:**
   - **Deal Amount (Pre-filled & Editable):** Pre-filled with current deal amount in native currency (`₹`, `AED `, `$`).
   - **Amount Received (Required, > 0):** Mandates an upfront settlement. Cannot be empty or 0.
   - **Shortcut Buttons:**
     - **`100% Full Payment`**: Fills 100% of the deal amount.
     - **`50% Advance`**: Fills 50% of the deal amount.
   - **Live Calculated Badge:**
     - If `amount_received == deal_amount`: shows `Paid (100% Settled)` in green.
     - If `amount_received < deal_amount`: shows `Partial (Pending: ...)` in yellow.
   - **Bank Realization Preview:** Displays locked FX rate and INR converted equivalent (`1 AED = ₹23.00`, Converted INR: `₹...`).
3. **Submission & Execution:**
   - Sends `PATCH /admin/crm/leads/{id}/status` with payload:
     `{ status: 'Won', deal_amount: ..., amount_received: ... }`.
   - Backend automatically creates a record in `crm_payments` with today's locked FX rate:
     $$\text{received\_inr} = \text{round}(\text{amount\_received} \times \text{fx\_rate}, 2)$$
   - Dispatches `SyncPaymentToGoogleSheetJob` and `SyncLeadToGoogleSheetJob`.
   - Reloads view to reflect refreshed financial KPI cards and payment ledgers.

---

### Workflow C: Closed Won Earnings Dashboard
**Route:** `GET /admin/crm` or `GET /admin/crm/dashboard`

The dashboard functions as the executive financial cockpit:

```
+---------------------------------------------------------------------------------------------------+
|  [Live Sheets Connected]  Zero-Guessing Earnings Engine        [1 AED = ₹23]  [$1 = ₹86]  [+ Add Pay] |
+---------------------------------------------------------------------------------------------------+
|  [CLOSED WON REVENUE]    [AMOUNT RECEIVED]         [PENDING PAYMENT]        [AVG WON DEAL VALUE]  |
|  ₹3,03,000               ₹2,73,000                 ₹30,000                  ₹1,01,000             |
|  IN: ₹1,65,000           IN: ₹1,35,000             IN: ₹30,000              IN Avg: ₹82,500       |
|  AE: AED 6,000           AE: AED 6,000             AE: AED 0                AE Avg: AED 6,000     |
+---------------------------------------------------------------------------------------------------+
|  EARNINGS BY SERVICE                             |  EARNINGS BY MARKET                            |
|  Automation : ₹1,65,000 (54%) | Pipe: ₹0         |  INDIA (INR)   : ₹1,65,000 (Rec: ₹1.35k, Pend) |
|  Website    : ₹1,38,000 (46%) | Pipe: ₹2,40,500  |  DUBAI (AED)   : AED 6,000 (Rec: 6k, Pend: 0)  |
|  Laravel    : ₹0        (0%)  | Pipe: ₹5,46,000  |  USA (USD)     : $0        (Rec: $0, Pend: $0) |
+---------------------------------------------------------------------------------------------------+
|  CLOSED WON DEALS BREAKDOWN (Individual contract cards with Paid/Partial badge, + Add Payment)   |
|  [Apex Logistics Hub - Paid]    [Oasis Dental - Paid]    [Nova Health - Partial]                  |
+---------------------------------------------------------------------------------------------------+
|  LEAD PIPELINE & DEAL VALUES TABLE (Live status dropdowns, search bar, filters)                  |
+---------------------------------------------------------------------------------------------------+
```

#### Multi-Currency Metric Integrity Rules:
- **Top Cards:**
  - `Closed Won Revenue`: Sum of INR deals + AED deals $\times$ AED rate + USD deals $\times$ USD rate. Shows native currency breakdown subtitles (`IN: ₹1,65,000 • AE: AED 6,000`).
  - `Amount Received`: **Exact sum of locked `received_inr`** from all payments recorded in DB. No speculative estimation.
  - `Pending Payment`: Exact collectible remaining sum across contracts.
- **Earnings by Market:**
  - Separate colored cards for **India (INR)**, **Dubai (AED)**, and **USA (USD)**.
  - Displays figures in **native currency only**. Converted INR equivalent is shown purely as an informational subtitle (`= ₹1,38,000 (@ ₹23/AED)`).
- **Earnings by Service:**
  - Breakdown across Automation, Website, and Laravel. Shows won amount, won percentage bar, and active pipeline potential in progress (`New`, `Contacted`, `Proposal`).

---

### Workflow D: Global "+ Add Payment" Modal
**Trigger:** Top bar "+ Add Payment" button or "+ Add Payment" on any Won contract card.

1. **Client Filter Guard:**
   - Dropdown loop uses `@foreach($wonLeads as $l)` — **only Won deals are shown**. All Lost leads and unclosed pipeline leads are strictly excluded.
2. **HTML5 Data Attributes:**
   Every option includes:
   `data-deal`, `data-received`, `data-pending`, `data-currency`, `data-symbol`, `data-fx-rate`.
3. **Interactive Client Selection:**
   - Selecting a client immediately reveals the **Financial Summary Bar**:
     - **Deal Value** (`data-deal`)
     - **Received So Far** (`data-received`)
     - **Pending Balance** (`data-pending`)
   - Currency prefix (`₹`, `AED `, `$`) updates automatically.
   - Locks today's FX rate (`data-fx-rate`) in the background.
4. **Shortcut Payment Fillers:**
   - **"Pay Full Pending"**: Fills `pending_payment` into the amount input.
   - **"50%"**: Fills $50\%$ of the total deal amount (`deal * 0.5`).
5. **Live Realized Cash Preview:**
   - Typing into the amount input executes `recalcGlobalInr()` in real time:
     $$\text{Realized Cash in Bank} = \text{amount} \times \text{fx\_rate}$$
6. **Collapsible Advanced Section:**
   - Allows optional override of the payment date (`paid_on`) and manual FX rate adjustment before locking.
7. **Submission:**
   - Submits to `POST /admin/crm/leads/{leadId}/payments`.
   - Automatically recomputes lead dynamic status to `Paid` or `Partial`.

---

### Workflow E: Lead Details Page
**Route:** `GET /admin/crm/leads/{id}`

- Displays complete client profile, contact channel, outreach status, follow-up calendar, and audit URL.
- **Payment Installments Table:** Lists all historical payments with Date, Native Amount, Locked FX Rate, Realized INR, and Notes.
- **Lost Deal Invariant:** If `status === 'Lost'`, both the top **`+ Record Payment`** button and the installments **`+ Add Installment`** button are **completely hidden**.
- **Edit Lead Modal:** Status dropdown is disabled and locked for `Won` and `Lost` leads.

---

### Workflow F: Google Sheets Sync Engine
**Service:** `App\Services\CrmGoogleSheetService`  
**Jobs:** `App\Jobs\SyncLeadToGoogleSheetJob`, `App\Jobs\SyncPaymentToGoogleSheetJob`

- **Configuration:** Managed via `/admin/crm/settings` or `.env` (`CRM_GOOGLE_SYNC_ENABLED`, `CRM_GOOGLE_SHEET_ID`, `CRM_GOOGLE_SERVICE_ACCOUNT_JSON`).
- **Sheet Tabs:**
  1. `lead`: Raw data sheet with columns `ID`, `Business Name`, `Market`, `Service`, `Channel`, `Status`, `Deal Amount`, `Currency`, `Amount Received`, `Pending Payment`, `Payment Status`.
  2. `Lead Acquisition and Outreach Kanban Pipeline`: Pipeline tracking tab.
  3. `Closed Won Earnings Dashboard`: Executive dashboard mirror.
  4. `Payments`: Complete transaction ledger.
- **Resilience:**
  - If Google credentials are not configured or network fails, all database transactions succeed uninterrupted.
  - The job catches errors, logs them in `crm_sync_logs`, and marks them for batch retry.
  - Admin settings provides a **"Full Re-sync"** button (`POST /admin/crm/settings/resync`) to push all historical leads and payments in one batch.

---

## 5. Security & Business Logic Invariants Matrix

| # | Business Rule | Enforcement Mechanism | Failure Behavior |
|---|---|---|---|
| **1** | Duplicate Business Name Blocked | `CrmLeadStoreRequest` unique rule on `crm_leads.business_name` | Form submission rejected with message *"A lead with this business name already exists"*. |
| **2** | Negative Deal / Payment Amount Blocked | Validator `numeric \| min:0` on `deal_amount` and `amount` | Blocked with message *"Deal amount cannot be negative"*. |
| **3** | Overpayment Guard | `CrmPaymentStoreRequest` after-validation hook | Blocked if `amount > pending_payment`. |
| **4** | Won Deal Payment Mandate | `CrmLeadController@updateStatus` | Moving to Won without `amount_received > 0` is blocked by confirmation modal. |
| **5** | Won / Lost Status Permanently Locked | UI `disabled` attribute + Backend `updateStatus` and `update` check | Dropdown is greyed out (`cursor-not-allowed`). Direct API requests return `422 Unprocessable Entity`. |
| **6** | Lost Leads Cannot Receive Payments | UI conditional `@if($lead->status !== 'Lost')` + Client dropdown filter | "+ Record Payment" and "+ Add Installment" buttons are completely hidden. |
| **7** | INR Exchange Rate Invariance | `CrmFxRateService@getRate` strictly returns `1.0000` | Invariant constant. Cannot be modified. |
| **8** | Historical Payment Immutability | `crm_payments.fx_rate` & `received_inr` stored on insert | Changing daily market FX rates does not alter historical payment records. |

---

## 6. Codebase File Index & Directory Structure

```
portfolio-laravel/
├── app/
│   ├── Console/Commands/
│   │   └── CrmTestPaymentsCommand.php       # 7-step automated acceptance test suite
│   ├── Http/
│   │   ├── Controllers/Admin/Crm/
│   │   │   ├── CrmDashboardController.php   # Earnings dashboard & financial KPI calculations
│   │   │   ├── CrmLeadController.php        # Leads CRUD, status updates, Won confirmation
│   │   │   ├── CrmPaymentController.php     # Payments store, update, destroy, CSV export
│   │   │   └── CrmSettingController.php     # FX rates, Google Sheets config & sync triggers
│   │   └── Requests/
│   │       ├── CrmLeadStoreRequest.php      # Validation: unique business, stage, deal amount
│   │       ├── CrmLeadUpdateRequest.php    # Validation: update rules & unique ignore self
│   │       └── CrmPaymentStoreRequest.php   # Validation: overpayment guard & positive amount
│   ├── Jobs/
│   │   ├── SyncLeadToGoogleSheetJob.php     # Background sync job for leads
│   │   └── SyncPaymentToGoogleSheetJob.php  # Background sync job for payments
│   ├── Models/
│   │   ├── CrmLead.php                      # Lead model, relations, dynamic accessors
│   │   ├── CrmPayment.php                   # Payment model, locked FX, received_inr
│   │   ├── CrmSetting.php                   # Key-value setting store
│   │   └── CrmSyncLog.php                   # Sheet sync audit trail
│   └── Services/
│       ├── CrmFxRateService.php             # Exchange rate provider (INR=1, AED, USD)
│       └── CrmGoogleSheetService.php        # Google Sheets API client & batch synchronizer
├── database/migrations/
│   ├── 2026_11_08_000001_create_crm_leads_table.php
│   ├── 2026_11_08_000002_create_crm_payments_table.php
│   ├── 2026_11_08_000003_create_crm_settings_table.php
│   └── 2026_11_08_000004_create_crm_sync_logs_table.php
├── resources/views/admin/crm/
│   ├── dashboard.blade.php                  # Financial KPI cards, breakdown, + Add Payment modal
│   ├── leads/
│   │   ├── index.blade.php                  # Pipeline table, Kanban board, Quick Add, Won modal
│   │   └── show.blade.php                   # Lead detail view, installments table, locked edit
│   ├── payments/
│   │   └── index.blade.php                  # Full payments ledger & CSV export
│   └── settings/
│       └── index.blade.php                  # Google credentials & FX rates manager
├── routes/web.php                           # Prefixed 'admin/crm' route group
└── tests/Feature/
    ├── CrmPaymentLogicTest.php              # Automated test for rate locking & Won flow
    └── CrmGoogleSheetSyncTest.php           # Automated test for Sheet sync handling
```

---

## 7. Automated Acceptance Test Suite

To verify the entire CRM financial engine, run:
```bash
php artisan crm:test-payments
```

### Verified Test Cases:
1. **Test 1: AED Rate Lock Verification:**
   - 1,000 AED payment at rate 23 locks `received_inr = ₹23,000`.
   - Rate changes to 24 in market $\rightarrow$ historical payment remains locked at ₹23,000.
2. **Test 2: Cumulative Realized Earnings:**
   - Adding 200 AED at new rate 24 locks `received_inr = ₹4,800`.
   - Total Realized Earnings for deal evaluates to exactly `₹27,800` (`₹23,000 + ₹4,800`).
3. **Test 3: Nova Health Dynamic Accounting:**
   - Deal = ₹90,000, Initial payment = ₹60,000.
   - Recording ₹10,000 installment $\rightarrow$ Received = ₹70,000, Pending = ₹20,000, Status = `Partial`.
4. **Test 4: Overpayment Guard Validation:**
   - Attempting to record ₹25,000 when pending balance is ₹20,000 is strictly blocked.
5. **Test 5: INR Rate Invariance:**
   - Asserts INR FX Rate is invariant at `1.0000`.
6. **Test 6: Google Sheets Sync Engine Graceful Degradation:**
   - Confirms job queue handles unconfigured or disabled Google credentials gracefully without breaking UI.
7. **Test 7: Won Confirmation Flow & Automatic Payment Recording:**
   - Moving a proposal deal to Won with $50\%$ advance automatically inserts payment in `crm_payments` and marks status as `Partial`.

---

## 8. Summary for Sharing with ChatGPT / LLMs

When prompting ChatGPT about this project, you can provide this quick summary:

> *"The system is a Laravel 11 CRM module located at `/admin/crm`. It manages multi-currency agency leads (INR, AED, USD) using a zero-guessing architecture. When a lead moves to Won, a confirmation modal mandates an upfront payment amount, which is recorded in `crm_payments` with that day's locked FX rate (`received_inr = amount * fx_rate`). Financial fields (`amount_received`, `pending_payment`, `payment_status`) are dynamic accessors on `CrmLead`. Statuses for `Won` and `Lost` leads are permanently locked and disabled in all UI dropdowns and backend APIs. Payments cannot be recorded for `Lost` leads, and overpayment beyond pending balance is strictly guarded. Google Sheets synchronization runs via background jobs (`SyncLeadToGoogleSheetJob`, `SyncPaymentToGoogleSheetJob`)."*
