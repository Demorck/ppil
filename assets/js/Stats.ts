import {Chart, registerables} from "chart.js";


Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', () => {
    let tabs = document.querySelectorAll('[data-id-tab]') as NodeListOf<HTMLElement>;
    let divs = document.querySelectorAll('[id$="charts"]');

    tabs.forEach(element => {
        element.addEventListener('click', () => {
            let id = element.dataset.idTab;
            tabs.forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');
            divs.forEach(div => {
                div.classList.add('hidden')
                if (div.id == id + "_charts")
                    div.classList.remove('hidden');
            });
        });
    });



    init_offres();
    init_users();
});

function init_offres()
{
    init_offres_chart();
    init_offre_prix();
}

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


function init_users()
{
    init_users_roles();
    init_users_months();
}

function init_users_roles()
{
    const canvas = document.getElementById('user_roles_chart') as HTMLCanvasElement;
    const ctx = canvas.getContext("2d");

    const elementdata = document.querySelector('[data-users-roles]') as HTMLElement;
    let data_json = JSON.parse(elementdata.dataset.usersRoles);


    let labels = ["ROLE_ADMIN", "ROLE_LOCATAIRE", "ROLE_PROPRIETAIRE", "ROLE_JURISTE"];
    data_json = data_json.map(line => line.roles);
    let data = [0, 0, 0, 0];
    data_json.forEach((line: string[]) => {
        line.forEach(role => {
            let index = labels.indexOf(role);
            data[index] += 1;
        });
    })

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Répartition des utilisateurs par role',
                    data: data,
                    backgroundColor: ['Red', 'Blue', 'Green', 'Yellow'],
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Utilisateurs par role"
                }
            },
        },
    });
}

function init_users_months()
{
    const canvas = document.getElementById('user_months_chart') as HTMLCanvasElement;
    const ctx = canvas.getContext("2d");

    const elementdata = document.querySelector('[data-users-months]') as HTMLElement;
    let data_json = JSON.parse(elementdata.dataset.usersMonths);

    let labels = data_json.map(line => line.month);
    let data = data_json.map(line => line.count);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Inscription par mois',
                    data: data,
                    backgroundColor: ['Red'],
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Inscription par mois"
                }
            },
        },
    });
}