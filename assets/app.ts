document.addEventListener('DOMContentLoaded', () => {
    let icon_profile = document.getElementById("user-menu-button") as HTMLButtonElement;
    if (icon_profile != null) {
        icon_profile.addEventListener('click', dropdown_menu_profile);
    }
})

function dropdown_menu_profile() {
    let dropdown = document.getElementById('dropdown-menu-profile')!;
    if (dropdown.classList.contains('active')) {
        dropdown.classList.add('hidden');
        dropdown.classList.remove('active');
    } else {
        dropdown.classList.remove('hidden');
        dropdown.classList.add('active');
    }
}