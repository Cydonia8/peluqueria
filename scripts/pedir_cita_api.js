"use strict"
const calendario = document.querySelectorAll("#calendario td:not(:empty)")
const flechas = document.querySelectorAll("caption a")
const select_servicio = document.getElementById("select-servicio")
const select_trabajador = document.getElementById("select-empleado")
let fiestas

festivos()
async function festivos(){
    const respuesta = await fetch('https://date.nager.at/api/v3/PublicHolidays/2023/ES')
    const datos = await respuesta.json()
    fiestas = datos.filter(festivo => festivo.counties == null || festivo.counties.includes("ES-AN"))
    calendario.forEach(cal=>{
        let fecha = cal.getAttribute("data-date")
        fiestas.forEach(festivo=>{
            if(festivo.date == fecha){
                cal.classList.add("festivo")
            }
        })
    })
}
flechas.forEach(flecha=>{
    flecha.addEventListener("click", comprobarFestivos)
})

select_trabajador.addEventListener("click", async ()=>{
    const servicio = select_servicio.value
    const res = await fetch(`../php/api_empleados_servicios.php?id=${servicio}`)
    const datos = await res.json()
    console.log(datos)
})

function comprobarFestivos(){
    calendario.forEach(cal=>{
        let fecha = cal.getAttribute("data-date")
        fiestas.forEach(festivo=>{
            if(festivo.date == fecha){
                cal.classList.add("festivo")
            }
        })
    })
}

const cont = document.querySelector(".vanilla-calendar")
const calendar = new VanillaCalendar(cont,{
    type: 'default',
    months: 1,
    date: {
        min: '1970-01-01',
        max: '2470-12-31',
        today: new Date('2023-05-09'),
    },
    settings: {
        iso8601: true,
        visibility: {
            // hightlights weekends
            weekend: true,
            // highlights today
            today: true,
            // abbreviated names of months in the month selection
            monthShort: true,
            // show week numbers of the year
            weekNumbers: false,
            // show all days, including disabled ones.s
            disabled: true,
            // show the days of the past and next month.
            daysOutside: true,
        },
        selected: {
            dates: ['2022-05-01', '2022-05-02', '2022-05-03', '2022-05-04'],
            time: '03:44 AM',
            month: 5,
            year: 2022,
            holidays: ['2022-12-24', '2022-12-25'],
        },
        selection: {
            day: 'single', // 'single' | 'multiple' | 'multiple-ranged'
            month: true,
            year: true,
            time: false,
            controlTime: 'all', // 'all' | 'range'
            stepHours: 1,
            stepMinutes: 1,
        },
        lang: 'es',
    },
    DOMTemplates: {
        month: `
          <div class="vanilla-calendar-header">
            <div class="vanilla-calendar-header__content">
              <#Month />
              <#Year />
            </div>
          </div>
          <div class="vanilla-calendar-wrapper">
            <div class="vanilla-calendar-content">
              <#Months />
            </div>
          </div>
        `
    },
    popups: {
        '2022-06-28': {
          modifier: 'bg-red',
          html: 'Meeting at 9:00 PM',
        },
      },
    CSSClasses: {
        calendar: 'vanilla-calendar',
        calendarDefault: 'vanilla-calendar_default',
        calendarMultiple: 'vanilla-calendar_multiple',
        calendarMonth: 'vanilla-calendar_month',
        calendarYear: 'vanilla-calendar_year',
        controls: 'vanilla-calendar-controls',
        grid: 'vanilla-calendar-grid',
        column: 'vanilla-calendar-column',
        header: 'vanilla-calendar-header',
        headerContent: 'vanilla-calendar-header__content',
        month: 'vanilla-calendar-month',
        monthDisabled: 'vanilla-calendar-month_disabled',
        year: 'vanilla-calendar-year',
        yearDisabled: 'vanilla-calendar-year_disabled',
        arrow: 'vanilla-calendar-arrow',
        arrowPrev: 'vanilla-calendar-arrow_prev',
        arrowNext: 'vanilla-calendar-arrow_next',
        wrapper: 'vanilla-calendar-wrapper',
        content: 'vanilla-calendar-content',
        week: 'vanilla-calendar-week',
        weekDay: 'vanilla-calendar-week__day',
        weekDayWeekend: 'vanilla-calendar-week__day_weekend',
        days: 'vanilla-calendar-days',
        daysSelecting: 'vanilla-calendar-days_selecting',
        months: 'vanilla-calendar-months',
        monthsSelecting: 'vanilla-calendar-months_selecting',
        monthsMonth: 'vanilla-calendar-months__month',
        monthsMonthSelected: 'vanilla-calendar-months__month_selected',
        monthsMonthDisabled: 'vanilla-calendar-months__month_disabled',
        years: 'vanilla-calendar-years',
        yearsSelecting: 'vanilla-calendar-years_selecting',
        yearsYear: 'vanilla-calendar-years__year',
        yearsYearSelected: 'vanilla-calendar-years__year_selected',
        yearsYearDisabled: 'vanilla-calendar-years__year_disabled',
        time: 'vanilla-calendar-time',
        timeContent: 'vanilla-calendar-time__content',
        timeHours: 'vanilla-calendar-time__hours',
        timeMinutes: 'vanilla-calendar-time__minutes',
        timeKeeping: 'vanilla-calendar-time__keeping',
        timeRanges: 'vanilla-calendar-time__ranges',
        timeRange: 'vanilla-calendar-time__range',
        day: 'vanilla-calendar-day',
        daySelected: 'vanilla-calendar-day_selected',
        daySelectedFirst: 'vanilla-calendar-day_selected-first',
        daySelectedLast: 'vanilla-calendar-day_selected-last',
        daySelectedIntermediate: 'vanilla-calendar-day_selected-intermediate',
        dayPopup: 'vanilla-calendar-day__popup',
        dayBtn: 'vanilla-calendar-day__btn',
        dayBtnPrev: 'vanilla-calendar-day__btn_prev',
        dayBtnNext: 'vanilla-calendar-day__btn_next',
        dayBtnToday: 'vanilla-calendar-day__btn_today',
        dayBtnSelected: 'vanilla-calendar-day__btn_selected',
        dayBtnHover: 'vanilla-calendar-day__btn_hover',
        dayBtnDisabled: 'vanilla-calendar-day__btn_disabled',
        dayBtnIntermediate: 'vanilla-calendar-day__btn_intermediate',
        dayBtnWeekend: 'vanilla-calendar-day__btn_weekend',
        dayBtnHoliday: 'vanilla-calendar-day__btn_holiday',
        weekNumbers: 'vanilla-calendar-week-numbers',
        weekNumbersTitle: 'vanilla-calendar-week-numbers__title',
        weekNumbersContent: 'vanilla-calendar-week-numbers__content',
        weekNumber: 'vanilla-calendar-week-number',
        isFocus: 'vanilla-calendar-is-focus',
      },    
})
calendar.init()