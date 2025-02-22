import flatpickr from "flatpickr";

flatpickr(".date", {
    dateFormat: "Y-m-d",
    locale: "en",
    maxDate: "today",
});

flatpickr(".datetime", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    locale: "en",
    maxDate: "today",
    minuteIncrement: 1,
});

flatpickr(".range", {
    mode: "range",
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    locale: "en",
    maxDate: "today",
    minuteIncrement: 1,
});
