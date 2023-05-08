const cont = document.querySelector(".vanilla-calendar")
const calendar = new VanillaCalendar(cont,{
    type: 'multiple',
    months: 2,
    date: {
        min: '1970-01-01',
        max: '2470-12-31',
        today: new Date('2023-05-09'),
      },
      settings: {
        iso8601: false,
      }
})
calendar.init()