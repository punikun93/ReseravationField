<script>
    document.addEventListener('DOMContentLoaded', function() {


        // Initialize Flatpickr for daily date picker
        if (document.getElementById('daily-date')) {
            flatpickr("#daily-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('date', now()->format('Y-m-d')) }}",
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('period').value = 'daily';
                    filterAndRefresh('date=' + dateStr + '&period=daily');
                }
            });
        }


        // Handle monthly filter changes
        if (document.getElementById('month') && document.getElementById('year')) {
            ['month', 'year'].forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    const month = document.getElementById('month').value;
                    const year = document.getElementById('year').value;
                    filterAndRefresh(`month=${month}&year=${year}&period=monthly`);
                });
            });
        }

        // Handle yearly filter changes
        if (document.getElementById('year') && !document.getElementById('month')) {
            document.getElementById('year').addEventListener('change', function() {
                filterAndRefresh(`year=${this.value}&period=yearly`);
            });
        }

        function formatDate(date) {
            const d = new Date(date);
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            const year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        function filterAndRefresh(queryString) {
            const currentUrl = new URL(window.location.href);
            const baseUrl = currentUrl.origin + currentUrl.pathname;
            window.location.href = `${baseUrl}?${queryString}`;
        }
        const startDt = document.getElementById('start_date');
        const endDt = document.getElementById('end_date');
        const dateRangePicker = flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: {
                rangeSeparator: ' s/d ',

            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];

                    // Update hidden inputs
                    startDt.value = formatDate(startDate);
                    endDt.value = formatDate(endDate);

                    // Filter transactions
                    filterTransactions(startDate, endDate);
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    // Sort the dates if end date is before start date
                    const [date1, date2] = selectedDates;
                    if (date2 < date1) {
                        instance.setDate([date2, date1]);
                    }
                }
            }
        });

        function formatDate(date) {
            // Create a new Date object from the selected date and adjust it to the local time zone
            const localDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
            return localDate.toISOString().split('T')[0];
        }

        const isTableLayout = document.querySelector('table tbody tr') !== null;
        if (isTableLayout) {
            function filterTransactions(startDate, endDate) {
                const rows = document.querySelectorAll('table tbody tr'); // Mengambil semua baris tabel
                let visibleCount = 0;

                rows.forEach(row => {
                    const dateElement = row.querySelector(
                        'td:nth-child(4)'
                    ); // Mengambil kolom tanggal (sesuaikan dengan posisi kolom tanggal di tabel Anda)
                    if (!dateElement) return;

                    const dateStr = dateElement.textContent.trim();
                    const [day, month, year] = dateStr.split('/');
                    const rowDate = new Date(year, month - 1, day);

                    // Reset the time portion for accurate date comparison
                    rowDate.setHours(0, 0, 0, 0);
                    const compareStartDate = new Date(startDate);
                    compareStartDate.setHours(0, 0, 0, 0);
                    const compareEndDate = new Date(endDate);
                    compareEndDate.setHours(0, 0, 0, 0);

                    if (rowDate >= compareStartDate && rowDate <= compareEndDate) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Handle pagination visibility
                const pagination = document.querySelector('.pagination');
                if (pagination) {
                    pagination.style.display = visibleCount > 0 ? '' : 'none';
                }
            }
        } else {

            function filterTransactions(startDate, endDate) {
                const cards = document.querySelectorAll('.grid > div');

                cards.forEach(card => {
                    const dateElement = card.querySelector('p:nth-child(4) span.font-semibold');
                    const pagination = document.querySelector('.pagination');
                    if (!dateElement) return;

                    const dateStr = dateElement.textContent.trim();
                    const [day, month, year] = dateStr.split('/');
                    const cardDate = new Date(year, month - 1, day);

                    // Reset the time portion for accurate date comparison
                    cardDate.setHours(0, 0, 0, 0);
                    const compareStartDate = new Date(startDate);
                    compareStartDate.setHours(0, 0, 0, 0);
                    const compareEndDate = new Date(endDate);
                    compareEndDate.setHours(0, 0, 0, 0);

                    if (cardDate >= compareStartDate && cardDate <= compareEndDate) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                        pagination.style.display = 'none';
                    }
                });
            }
        }


        const btnExport = document.getElementById('exportForm');

        btnExport.addEventListener('submit', function(e) {
            const period = document.getElementById('period').value;
            let isValid = true;
            let errorMessage = 'Please fill out the required date fields.';

            // Validation based on selected period
            if (period === 'daily') {
                if (!document.getElementById('daily-date').value) {
                    isValid = false;
                }
            } else if (period === 'monthly') {
                if (!document.getElementById('month').value || !document.getElementById('year')
                    .value) {
                    isValid = false;
                }
            } else if (period === 'yearly') {
                if (!document.getElementById('year').value) {
                    isValid = false;
                }
            } else {
                console.log(startDt.value, endDt.value);
                if (!startDt.value || !endDt.value) {
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
            }
        });
    });
</script>
