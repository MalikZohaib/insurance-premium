# Insurance Premium TYPO3 Extension

This TYPO3 extension provides a fully functional frontend & backend interface for managing insurance premium policies, including age-based calculations, Extbase validation, and multilingual support.

---

## 🧩 Features

* Frontend insurance calculator with AJAX
* Record for insurance policies
* JSON-based premium ranges (e.g., `"0-10": 12.5`)
* Validation for custom fields
* Cache optimization by language and policy ID
* PSR-14 and TCEmain hook integration

---

## 🚀 Installation

### 1. Install via Composer:

```bash
composer require vendor/insurance-premium
```

### 2. Activate Extension

Enable in the TYPO3 Extension Manager or via `PackageStates.php`.

### 3. Include Static TypoScript

In your site template:

* Include `Insurance Premium (Main)`

---

## 🔧 Configuration

### PageTSConfig

```ts
TYPO3.backend.siteTitle = Insurance Admin Panel
```

### FlexForm

* Configure plugin settings (AJAX page type, template path overrides)

### Site Settings (Site Sets)

Set values for paths, PIDs, features using:

* `Configuration/Sets/InsuranceCalculatorSet/config.yaml`
* `settings.yaml`

---

## 🧠 Usage

### Backend

1. Go to **Web → List**
2. Select a sysfolder
3. Create a new record: **Insurance Policy**
4. Enter JSON body:

```json
{
  "0-10": "12.5",
  "11-20": "17.0"
}
```

### Frontend

Use `<insurance-calculator>` plugin in your content element.

---

## 🪝 Hooks & Events

### DataHandler Hook:

* Validates JSON field
* Shows flash message instead of exceptions

### Cache Tagging:

* Includes original UID & language UID

---

## 🧪 Testing

(Add instructions for unit and functional tests)

---

## 🛠️ Development

(Add development & contribution guidelines)

---

## ❓ FAQ

(Add common questions and answers)