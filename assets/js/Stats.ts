import { Chart, registerables } from "chart.js";


Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', () => {
    init_offres_chart();
    init_offre_prix();
});


function init_offres_chart()
{
    const canvas = document.getElementById('statsChart') as HTMLCanvasElement;
    const ctx = canvas.getContext("2d");

    const elementdata = document.querySelector('[data-offre-status]') as HTMLElement;
    let statsByMonth = JSON.parse(elementdata.dataset.offreStatus);
    statsByMonth = JSON.parse(statsByMonth); // jsp pourquoi je dois le parse 2 fois ?
    const labels = statsByMonth.map(stat => "Statut " + stat.status);
    const data = statsByMonth.map(stat => stat.count);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Répartition des offres par statut',
                    data: data,
                    backgroundColor: ['Red', 'Blue', 'Green', 'Yellow', 'Purple'],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: "Offre par type"
                }
            },
        },
    });
}

function init_offre_prix()
{
    const canvas = document.getElementById('prix_chart') as HTMLCanvasElement;
    const ctx = canvas.getContext("2d");

    const elementdata = document.querySelector('[data-offre-prix]') as HTMLElement;
    let statsByMonth = JSON.parse(elementdata.dataset.offrePrix);

    statsByMonth = statsByMonth.map(line => [line.duree, line.prix])
    let labels = statsByMonth.map(line => line[0] + " jours");
    console.log(statsByMonth);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Prix par durée',
                    data: statsByMonth,
                    backgroundColor: ['Red'],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: "Offre par type"
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: "Prix de l'offre"
                    },
                    ticks: {
                        // Include a dollar sign in the ticks
                        callback: function (value, index, ticks) {
                            return value + ' €';
                        }
                    }
                }
            }
        },
    });
}