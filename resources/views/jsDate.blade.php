document.addEventListener('alpine:init', () => {
    Alpine.data('calendar', () => ({
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        selectedDate: '{{ request('tanggal') ?? \Carbon\Carbon::now()->format('Y-m-d') }}',
        months: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        
        get calendarDays() {
            const days = [];
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            
            // Get the day of week for the first day (0-6, 0 = Sunday)
            let firstDayOfWeek = firstDay.getDay();
            // Convert to Monday-based week (0-6, 0 = Monday)
            firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;
            
            // Add previous month's days
            const prevMonthLastDay = new Date(this.currentYear, this.currentMonth, 0).getDate();
            for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                const date = prevMonthLastDay - i;
                days.push({
                    date,
                    month: this.currentMonth - 1,
                    year: this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear,
                    currentMonth: false,
                    id: `prev-${date}` // Unique ID for each day
                });
            }
            
            // Add current month's days
            for (let date = 1; date <= lastDay.getDate(); date++) {
                days.push({
                    date,
                    month: this.currentMonth,
                    year: this.currentYear,
                    currentMonth: true,
                    id: `current-${date}` // Unique ID for each day
                });
            }
            
            // Calculate remaining days needed to complete the grid
            const remainingDays = Math.ceil((days.length) / 7) * 7 - days.length;
            
            // Add next month's days
            for (let date = 1; date <= remainingDays; date++) {
                days.push({
                    date,
                    month: this.currentMonth + 1,
                    year: this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear,
                    currentMonth: false,
                    id: `next-${date}` // Unique ID for each day
                });
            }
            
            return days;
        },
        
        previousMonth() {
            if (this.currentMonth === 0) {
                this.currentYear--;
                this.currentMonth = 11;
            } else {
                this.currentMonth--;
            }
        },
        
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentYear++;
                this.currentMonth = 0;
            } else {
                this.currentMonth++;
            }
        },
        
        selectDate(day) {
            const date = new Date(day.year, day.month, day.date);
            this.selectedDate = date.toISOString().split('T')[0];
            this.triggerDateChange();
        },
        
        isSelectedDate(day) {
            const date = new Date(day.year, day.month, day.date);
            return date.toISOString().split('T')[0] === this.selectedDate;
        },
        
        isToday(day) {
            const today = new Date();
            return day.date === today.getDate() && 
                   day.month === today.getMonth() && 
                   day.year === today.getFullYear();
        },
        
        triggerDateChange() {
            if (typeof window.triggerDateChange === 'function') {
                window.triggerDateChange();
            }
        }
    }));
});