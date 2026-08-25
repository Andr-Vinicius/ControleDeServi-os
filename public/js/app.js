document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (alertEl) {
        setTimeout(function () {
            alertEl.style.transition = 'opacity 0.4s ease';
            alertEl.style.opacity = '0';
            setTimeout(function () { alertEl.remove(); }, 400);
        }, 4000);
    });

    document.querySelectorAll('.form-box').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const description = form.querySelector('input[name="description"]');
            const price = form.querySelector('input[name="price"]');

            if (description && description.value.trim() === '') {
                alert('Informe a descrição do serviço.');
                event.preventDefault();
                return;
            }

            if (price) {
                const normalized = price.value.replace(',', '.');
                if (normalized.trim() === '' || isNaN(parseFloat(normalized)) || parseFloat(normalized) <= 0) {
                    alert('Informe um valor válido para o serviço.');
                    event.preventDefault();
                }
            }
        });
    });
});
