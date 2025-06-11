document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('InsuranceCalculationForm').addEventListener('submit', function (e) {
    e.preventDefault();

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
        resultEl.innerText = 'Results: ' + JSON.stringify(data.data);
        }
    })
    .catch(err => {
        document.getElementById('ajaxResult').innerText = 'AJAX error: ' + err.message;
    });
    });
});

