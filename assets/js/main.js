// Validação do formulário via JavaScript

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signo-form');
    if (!form) return;

    const input = document.getElementById('data_nascimento');
    const errorEl = document.getElementById('date-error');

    form.addEventListener('submit', function (e) {
        const value = input.value;

        if (!value) {
            e.preventDefault();
            showError('Por favor, insira sua data de nascimento.');
            return;
        }

        const date = new Date(value);
        const today = new Date();
        today.setHours(23, 59, 59, 999);

        if (isNaN(date.getTime())) {
            e.preventDefault();
            showError('Data inválida. Por favor, verifique o formato.');
            return;
        }

        if (date > today) {
            e.preventDefault();
            showError('A data de nascimento não pode ser no futuro.');
            return;
        }

        const minDate = new Date('1900-01-01');
        if (date < minDate) {
            e.preventDefault();
            showError('Por favor, insira uma data de nascimento válida (após 1900).');
            return;
        }

        hideError();
    });

    input.addEventListener('input', function () {
        hideError();
    });

    function showError(msg) {
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('d-none');
        }
        input.classList.add('is-invalid');
    }

    function hideError() {
        if (errorEl) {
            errorEl.classList.add('d-none');
        }
        input.classList.remove('is-invalid');
    }
});
