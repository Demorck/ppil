document.addEventListener("DOMContentLoaded", () => {
    let divs = document.querySelectorAll('div[data-id]') as NodeListOf<HTMLElement>;

    divs.forEach((div: HTMLElement) => {
        div.addEventListener('click', async () => {
            try {
                let type = document.querySelector('div[data-type]') as HTMLElement;
                let string = "";
                let test;

                let parent = div.parentElement.parentElement;

                if (type.dataset.type === "offres")
                {
                    string = "/admin/suspendre/";
                    test = parent.querySelector('[data-offre="status"]');
                } else if (type.dataset.type === "user")
                {
                    string = "/admin/ban/";
                    test = parent.querySelector('[data-user="status"]');
                }

                let response = await fetch(string + div.dataset.id, {
                    method: 'GET',
                });

                let text = await response.text();
                let json = JSON.parse(text);
                test.textContent = json.status;

                div.classList.remove("bg-red-400", "bg-green-40");
                div.classList.add(json.parent_class);
                div.innerText = json.text;
            } catch (e)
            {
                console.error(e.toString());
            }
        })
    })
});