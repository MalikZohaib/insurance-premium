# Insurance Premium TYPO3 Extension

This TYPO3 extension provides a fully functional frontend & backend interface for managing insurance premium policies, including age-based calculations, Extbase validation, and multilingual support.

---

## 🧩 Features

* Frontend insurance calculator with AJAX
* JSON-based premium ranges (e.g., `"0-10": 12.5`)
* Validation for custom fields
* Cache optimization by language and policy ID
* PSR-14 and TCEmain hook integration

---

## 📦 Installation Requirements

* TYPO3 v13 or later
* PHP 8.4+
* Composer (for managing dependencies if required)
* A working site configuration with Site Sets enabled

---

## 🚀 Installation

### 1. Download Extension:

Download the ZIP file or clone the repository into your TYPO3 `extensions` folder:

````bash
cd public/typo3conf/ext
wget https://your-download-url.com/insurance-premium.zip
unzip insurance_premium.zip
# or
git clone https://your-git-repo.com/insurance-premium.git
```bash
composer require vendor/insurance-premium
````

### 2. Site Configuration

In your site configuration `config.yaml`:

* Add to `siteSets.dependencies`:

  ```yaml
    dependencies:
        - Insurance Premium
        - main set
  ```

---

## 🔧 Configuration

### FlexForm

* Contains a single dropdown field: **Select Insurance Policy**
* Used to link a specific policy record to the plugin instance

### Site Settings (Site Sets)

Set values for paths, PIDs, features, and AJAX page type using:

* `Configuration/Sets/InsuranceCalculatorSet/config.yaml`
* `settings.yaml`

---

## 🧠 Usage

### Backend

You can manage policies by creating new **Insurance Policy** records manually:

1. Go to **Web → List**
2. Select a sysfolder
3. Create a new record of type **Insurance Policy** with fields like `title` and `body`
4. In the `body` field, enter a JSON value such as:

```json
{
  "0-10": "12.5",
  "11-20": "17.0"
}
```

### Frontend

Insert the content element of CType `insurnacepremium_insurancecalculator` into your page using the "Create new content" wizard.

This plugin will render the insurance calculator linked to the selected policy from FlexForm.

---

## 🪝 Hooks & Events Used

### DataHandler Hook:

* Validates JSON field
* Shows flash message instead of exceptions

### Cache Clearing Hook:

* Clears cache automatically on create, update, or delete of insurance policy records
* Uses the original (default language) UID for tag-based cache invalidation

### Cache Tagging:

* Includes original UID & language UID

---

## 🧪 Testing

---

## 🛠️ Development

---
