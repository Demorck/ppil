import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/l10n/fr"

export function date_range() {
    const dateInput = document.querySelector("#date-range") as HTMLInputElement;
    const dateDebutElement = document.querySelector("#offre_dateDebut") as HTMLInputElement;
    const dateFinElement = document.querySelector("#offre_dateFin")  as HTMLInputElement;

    if (dateInput)
    {
        let rangeImpossible = JSON.parse(dateInput.dataset.dateImpossible || "[]");
        let dateDebutOffre = new Date(dateInput.dataset.dateDebut);
        let dateFinOffre = new Date(dateInput.dataset.dateFin);

        let disableDate: [] = rangeImpossible.map((range: { dateDebut: { date: string; }; dateFin: { date: string; }; }) => {
            let startDate = new Date(range.dateDebut.date);
            let endDate = new Date(range.dateFin.date);

            return {
                from: startDate,
                to: endDate
            };
        })

        flatpickr(dateInput, {
            altInput: true,
            altFormat: "j F Y",
            locale: "fr",
            mode: "range",
            minDate: dateDebutOffre,
            maxDate: dateFinOffre,
            dateFormat: "d/m/Y",
            disable: disableDate,
            onChange: function (selectedDates, dateStr, instance) {
                let dateDebut = selectedDates[0];
                let dateFin = selectedDates[1];
                dateDebut.setHours(1, 5);
                dateFin.setHours(23, 55);

                if (dateDebut && dateFin) {
                    dateDebutElement.value = dateDebut.toISOString().slice(0, 16);
                    dateFinElement.value = dateFin.toISOString().slice(0, 16);
                }
            }
        });
    }
}
