function showError(message) {
    const errorBox = document.getElementById('errors');
    const errorParagraph = errorBox.querySelector('p');

    errorParagraph.textContent = message;
    errorBox.style.display = 'block';
}

function hideError() {
    const errorBox = document.getElementById('errors');
    const errorParagraph = errorBox.querySelector('p');
    errorParagraph.textContent = '';
    errorBox.style.display = 'none';
}

// Ensure the DOM is fully loaded before attaching event listeners
document.addEventListener('DOMContentLoaded', function () {

    const searchBtn = document.querySelector('#searchButton');

    document.getElementById('InsuranceCalculationForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Disable the button to prevent multiple submissions
        if (searchBtn.disabled) return;

        searchBtn.disabled = true;

        const form = this;
        const url = form.getAttribute('action');
        const formData = new FormData(form);

        fetch(url, {
            method: 'POST',
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                const resultEl = document.getElementById('ajaxResult');
                if (data.message) {
                    showError(data.message);
                } else {
                    hideError();
                    document.getElementById('ajaxResult').innerText = 'Premium: ' + (data.data.contribution);
                }
            })
            .catch(err => {
                showError(err.message);
            })
            .finally(() => {
                searchBtn.disabled = false; // Re-enable button
            });
    });
});

