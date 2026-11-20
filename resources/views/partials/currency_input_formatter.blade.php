<script>
    (function () {
        var selector = '[data-currency-input]';

        function digitValue(value) {
            return String(value || '')
                .trim()
                .replace(/\.00$/, '')
                .replace(/\D/g, '');
        }

        function positionAfterDigits(value, digitCount) {
            var position = 0;
            var found = 0;

            while (position < value.length && found < digitCount) {
                if (/\d/.test(value.charAt(position))) {
                    found++;
                }
                position++;
            }

            return position;
        }

        function formatInput(input) {
            var caret = input.selectionStart;
            var digitsBeforeCaret = caret === null
                ? digitValue(input.value).length
                : digitValue(input.value.slice(0, caret)).length;
            var digits = digitValue(input.value);
            var formatted = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            input.value = formatted;

            if (document.activeElement === input && caret !== null) {
                var nextCaret = positionAfterDigits(formatted, digitsBeforeCaret);
                input.setSelectionRange(nextCaret, nextCaret);
            }
        }

        document.querySelectorAll(selector).forEach(formatInput);

        document.addEventListener('input', function (event) {
            if (event.target.matches(selector)) {
                formatInput(event.target);
            }
        });

        document.addEventListener('submit', function (event) {
            event.target.querySelectorAll(selector).forEach(function (input) {
                input.value = digitValue(input.value);
            });
        }, true);
    })();
</script>
