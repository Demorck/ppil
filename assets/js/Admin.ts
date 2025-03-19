

document.addEventListener("DOMContentLoaded", () => {
    let divs = document.querySelectorAll('div[data-id]') as NodeListOf<HTMLElement>;

    divs.forEach((div: HTMLElement) => {
        div.addEventListener('click', async () => {
            try {
                let response = await fetch('/admin/ban/' + div.dataset.id, {
                    method: 'GET',
                });

                let text = await response.text();
                let json = JSON.parse(text);
                let parent = div.parentElement.parentElement;
                let test = parent.querySelector('[data-user="status"]');
                test.textContent = json.status;
                parent.classList.add('btn-rainbow');
            } catch (e)
            {
                console.error(e.toString());
            }
        })
    })
});