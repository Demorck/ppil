import {date_range} from "./js/DateRange";


document.addEventListener('DOMContentLoaded', () => {
    let icon_profile = document.getElementById("user-menu-button") as HTMLButtonElement;
    if (icon_profile != null) {
        icon_profile.addEventListener('click', dropdown_menu_profile);
    }

    let input_change_on_click = document.querySelectorAll('[data-type="change-on-click"]') as NodeListOf<HTMLInputElement>;
    if (input_change_on_click != null)
    {
        input_change_on_click.forEach((input) => {
            let name = input.dataset.for!;
            input.addEventListener('click', () => toggle_enabled_input(name));
        })
    }

    date_range();
})

function dropdown_menu_profile() : void {
    let dropdown = document.getElementById('dropdown-menu-profile')!;
    if (dropdown.classList.contains('active')) {
        dropdown.classList.add('hidden');
        dropdown.classList.remove('active');
    } else {
        dropdown.classList.remove('hidden');
        dropdown.classList.add('active');
    }
}

function toggle_enabled_input(name: string) : void {
    console.log('input[name=" ' + name  + ' "]');
    let input = document.querySelector('input[name="' + name  + '"]') as HTMLInputElement;
    input.disabled = !input.disabled;
}