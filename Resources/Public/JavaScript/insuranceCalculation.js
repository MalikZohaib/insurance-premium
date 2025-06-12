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
                if (data.error) {
                    resultEl.innerText = 'Error: ' + data.error;
                } else {
                    resultEl.innerText = 'Premium: ' + (data.data.contribution);
                }
            })
            .catch(err => {
                document.getElementById('ajaxResult').innerText = 'AJAX error: ' + err.message;
            })
            .finally(() => {
                searchBtn.disabled = false; // Re-enable button
            });
    });
});

